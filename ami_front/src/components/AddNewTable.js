import React, { useState } from "react";
import Button from '@mui/material/Button';
import Modal from '@mui/material/Modal';
import Box from '@mui/material/Box';
import Container from "@mui/material/Container";
import TextField from '@mui/material/TextField';
import Cookies from "js-cookie";
import Card from "@mui/material/Card";
import CardContent from "@mui/material/CardContent";
import Typography from "@mui/material/Typography";
import Grid from "@mui/material/Grid";

import AddCircleIcon from '@mui/icons-material/AddCircle';

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

const AddNewTable = ({ socket }, props) => {
    const [tableTitle, setTableTitle] = useState("");
    const [tableDesc, setTableDesc] = useState("");
    const [newTable, setNewTable] = useState(0);

    const [open, setOpen] = useState(false);

    const handleOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    };

    const handleAddNewTable = (e) => {
        e.preventDefault();

        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');
        const myHeaders = new Headers();


        myHeaders.append("Content-Type", "application/json");


        const raw = JSON.stringify({
            "user_id": userId,
            "token": token,
            "description": tableDesc,
            "name": tableTitle

        });

        const requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };

        fetch("http://127.0.0.1:8000/api/table/add", requestOptions)
            .then(response => response.json())
            .then(data => {
                // const response = JSON.parse(data);
                // console.log(data);
                alert("Added table");
                window.location.reload();
            })
            .catch(error => console.log('error', error));

        // setUserEmail("");
    };
    return (
        <Grid item xs={3}>


                <Card sx={{maxWidth: 345}} onClick={handleOpen} sx={{cursor: 'pointer'}}>
                    <div style={{
                        height: '185px',
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems:'center'

                    }}>
                        <AddCircleIcon sx={{fontSize: 120}}/>
                    </div>
                    <CardContent>

                        <Typography gutterBottom variant="h4" component="div">
                            Add new table
                        </Typography>

                    </CardContent>

                </Card>

            <Modal
                open={open}
                onClose={handleClose}
                aria-labelledby="parent-modal-title"
                aria-describedby="parent-modal-description"
            >
                <Box sx={{ ...style, width: 400 }}>


                    <div>
                        <h2 id="simple-modal-title">Modal Add table</h2>

                        <form className='form__input' onSubmit={handleAddNewTable}>
                            <Box
                                sx={{
                                    width: 300,
                                    height: 300,

                                }}
                            >
                                <TextField className='input' id="table_title" onChange={(e) => setTableTitle(e.target.value)} label="Table title" value={tableTitle}   variant="outlined" required/>
                                <TextField className='input' id="table_title" onChange={(e) => setTableDesc(e.target.value)} label="Table description" value={tableDesc}   variant="outlined" required/>


                                <Button className='addTodoBtn' variant="contained" type='submit'>Add table</Button>

                            </Box>
                        </form>
                    </div>
                </Box>
            </Modal>
        </Grid>
    );
};

export default AddNewTable;
