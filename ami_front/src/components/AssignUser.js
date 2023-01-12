import React, {useEffect, useState} from "react";
import Button from '@mui/material/Button';
import Modal from '@mui/material/Modal';
import Box from '@mui/material/Box';

import Cookies from "js-cookie";

import DeleteIcon from '@mui/icons-material/Delete';
import IconButton from '@mui/material/IconButton';
import Tooltip from '@mui/material/Tooltip';
import {Chip, Stack} from "@mui/material";
import Paper from '@mui/material/Paper';
import { styled } from '@mui/material/styles';
import PersonAddIcon from '@mui/icons-material/PersonAdd';
import Typography from "@mui/material/Typography";

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

const AssignUser = (props) => {
    const [users, setUsers] = useState([]);
    const [assignedUser, setAssignedUser] = useState([]);
    const [selectedUser, setSelectedUser] = useState("");

    const [open, setOpen] = useState(false);

    const handleOpen = () => {
        setOpen(true);
        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');


        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");

        var raw = JSON.stringify({
            "user_id": userId,
            "token": token,
        });

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };
        const searchParams = new URLSearchParams(window.location.search);
        fetch("http://localhost:8000/api/table/"+searchParams.get('id')+"/team", requestOptions)
            .then(response => response.json())
            .then(result => {
                console.log(result);
                setUsers(result);
            })
            .catch(error => console.log('error', error));
    };

    const handleClose = () => {
        setOpen(false);
    };


    const assignClick = (id) => {
        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');
        var myHeaders = new Headers();


        myHeaders.append("Content-Type", "application/json");

        const searchParams = new URLSearchParams(window.location.search);
        var raw = JSON.stringify({
            "user_id": userId,
            "token": token,
            "id_assign": id,
            "table_id": searchParams.get('id')

        });

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };

        fetch("http://127.0.0.1:8000/task/"+props.taskid+"/user/assign", requestOptions)
            .then(response => response.json())
            .then(data => {

                alert(data.status);
                handleClose();
            })
            .catch(error => console.log('error', error));




    };
    // const handleListUser = (e) => {
    // 	e.preventDefault();
    //
    // 	const token = Cookies.get('current_token');
    // 	const userId = Cookies.get('current_id');
    // 	var myHeaders = new Headers();
    //
    //
    // 	myHeaders.append("Content-Type", "application/json");
    //
    //
    // 	var raw = JSON.stringify({
    // 		"user_id": userId,
    // 		"token": token,
    //
    //
    // 	});
    //
    // 	var requestOptions = {
    // 		method: 'POST',
    // 		headers: myHeaders,
    // 		body: raw,
    // 		redirect: 'follow'
    // 	};
    // 	const searchParams = new URLSearchParams(window.location.search);
    // 	fetch("http://127.0.0.1:8000/api/table/"+searchParams.get('id')+"/user/add", requestOptions)
    // 		.then(response => response.json())
    // 		.then(data => {
    // 			// const response = JSON.parse(data);
    // 			// console.log(data);
    // 			alert(data.state);
    // 		})
    // 		.catch(error => console.log('error', error));
    //
    //
    // };
    useEffect(() => {
        const checkAssignedUsers = () => {
            const token = Cookies.get('current_token');
            const userId = Cookies.get('current_id');


            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");
            const searchParams = new URLSearchParams(window.location.search);
            var raw = JSON.stringify({
                "user_id": userId,
                "token": token,

            });

            var requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };

            fetch("http://localhost:8000/task/"+props.taskid+"/user", requestOptions)
                .then(response => response.json())
                .then(result => {
                    if(!result.status)
                    setAssignedUser(result);
                })
                .catch(error => console.log('error', error));
        };
        checkAssignedUsers();
    },[]);
if(assignedUser.email){
    return (

        <Typography gutterBottom variant="subtitle2" component="div" sx={{margin:"20px"}}>
            <b>Assign to:</b> <Chip label={assignedUser.email} variant="outlined"  color="primary" sx={{marginLeft:"10px",width:"autos"}}/>
        </Typography>

    );
}
    return (
        <div >
            <div className="controlBtn">
                <Button variant="contained" color="primary" onClick={handleOpen} >
                    Assign user
                    <PersonAddIcon sx={{marginLeft:"10px"}}/>
                </Button>



            </div>

            <Modal
                open={open}
                onClose={handleClose}
                aria-labelledby="parent-modal-title"
                aria-describedby="parent-modal-description"
            >
                <Box sx={{ ...style, width: 400 }}>


                    <div>
                        <h2 id="simple-modal-title">List users</h2>
                        {users.map((user) => (
                            <div>
                                <Stack direction="row" spacing={15} className='userControl'>
                                    <div>
                                        {user.email}
                                    </div>
                                    <div>
                                        <Tooltip title="Delete">
                                            {/*<IconButton onClick={setSelectedUser(user.id)} >*/}
                                            <IconButton onClick={(e) => {
                                                // setSelectedUser(user.id);
                                                assignClick(user.id);
                                            }} >
                                                <PersonAddIcon />
                                            </IconButton>
                                        </Tooltip>
                                    </div>



                                </Stack>

                            </div>
                        ))}

                    </div>
                </Box>
            </Modal>
        </div>

    );
};

export default AssignUser;
