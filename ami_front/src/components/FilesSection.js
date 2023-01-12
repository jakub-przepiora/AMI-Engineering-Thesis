import React, {useEffect, useState} from "react";
import Button from '@mui/material/Button';
import Modal from '@mui/material/Modal';
import Box from '@mui/material/Box';

import Cookies from "js-cookie";


import Paper from '@mui/material/Paper';
import { styled } from '@mui/material/styles';
import Grid from "@mui/material/Grid";
import PublishIcon from '@mui/icons-material/Publish';
import InsertDriveFileIcon from '@mui/icons-material/InsertDriveFile';
import Typography from "@mui/material/Typography";
import Link from "@mui/material/Link";



const Item = styled(Paper)(({ theme }) => ({
    backgroundColor: theme.palette.mode === 'dark' ? '#1A2027' : '#fff',
    ...theme.typography.body2,
    padding: theme.spacing(1),
    textAlign: 'center',
    color: theme.palette.text.secondary,
}));
const style = {
    position: 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: 400,
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    pt: 2,
    px: 4,
    pb: 3,
};

const FilesSection = ( props) => {
    const [filesList, setFilesList] = useState([]);
    const [sendedFile, setSendedFile] = useState("");
    const [sFi, setSFi] = useState("");

    const [open, setOpen] = useState(false);

    const handleOpen = () => {
        setOpen(true);

    };

    const handleClose = () => {
        setOpen(false);
    };


    const sendFile = (e) => {
        e.preventDefault();

        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');
        const formdata = new FormData();
        formdata.append("file", sendedFile, sendedFile.name);
        formdata.append("token", token);
        formdata.append("user_id", userId);
        formdata.append("task_id", props.taskid);

        const requestOptions = {
            method: 'POST',
            body: formdata,
            redirect: 'follow'
        };

        fetch("http://localhost:8000/file/task/1/add", requestOptions)
            .then(response => response.text())
            .then(result => {
                alert("Added file");
                setSFi("added");
                setOpen(false);
            })
            .catch(error => console.log('error', error));




    };
    const onFileClick = (url) => {
        window.location = url;
    }
    useEffect(() => {
        const getFilesTotask = () => {
            const token = Cookies.get('current_token');
            const userId = Cookies.get('current_id');
            const myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");

            const raw = JSON.stringify({
                "user_id": userId,
                "token": token,


            });

            const requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };

            fetch("http://localhost:8000/file/task/"+props.taskid, requestOptions)
                .then(response => response.json())
                .then(result => {

                    setFilesList(result);
                })
                .catch(error => console.log('error', error));
        };
        getFilesTotask();
    },[sFi]);
    useEffect(() => {
       setSFi("1");
    },[]);
    return (
        <div >
            <Grid container spacing={2} sx={{marginTop:"20px"}}>
                {filesList.map((filet) => (
                    <Grid item xs={6} md={2}>
                        <Link sx={{
                            display:"flex",
                            flexDirection:"column",
                            alignItems:"center"

                        }}
                        href={filet.url}
                        >
                            <InsertDriveFileIcon sx={{ fontSize: 40 }}/>

                            <Typography variant="subtitl2" gutterBottom>
                                {filet.filename.substring(0, 17) + '...'}
                            </Typography>
                        </Link>

                    </Grid>
                ))}


                <Grid item xs={6} md={2}>
                    <Item onClick={handleOpen} sx={{cursor: "pointer"}}>
                        <div>
                            <PublishIcon sx={{ fontSize: 40 }} />
                        </div>
                        <Typography variant="subtitl2"  gutterBottom>
                           <b> Add File</b>
                        </Typography>
                    </Item>
                </Grid>
            </Grid>
            <div className="controlBtn">
                {/*<Button variant="contained" color="primary" onClick={handleOpen} >*/}
                {/*    Add Files*/}
                {/*</Button>*/}
            </div>

            <Modal
                open={open}
                onClose={handleClose}
                aria-labelledby="parent-modal-title"
                aria-describedby="parent-modal-description"
            >
                <Box sx={{ ...style, width: 400 }}>


                    <div>
                        <h2 id="simple-modal-title">Add file</h2>
                        <form onSubmit={sendFile}>
                            <input type="file" onChange={(event) => {
                                // Update the selected file state
                                setSendedFile(event.target.files[0]);
                            }}/>
                            <Button Button variant="contained" color="primary" sx={{margin:'10px'}} type="submit">Upload</Button>
                        </form>

                    </div>
                </Box>
            </Modal>
        </div>

    );
};

export default FilesSection;
