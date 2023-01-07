import React, {useEffect, useState} from "react";
import AddTask from "../components/AddTask";
import TasksContainer from "../components/TasksContainer";

import AddColumn from "../components/AddColumn";
import {Stack} from "@mui/material";

import Container from "@mui/material/Container";
import AddUser from "../components/AddUser";

import ListUser from "../components/ListUser";
import Cookies from "js-cookie";
import Err404 from "./Error404";


const Task = () => {
    const [hasPermission, setHasPermission] =useState('');
    const [hasOwner, setHasOwner] =useState('');
    const ws = new WebSocket('ws://localhost:3001');

    useEffect(() => {
        const checkPermission = async () => {
            const token = Cookies.get('current_token');
            const userId = Cookies.get('current_id');

            const response = await fetch('http://127.0.0.1:8000/api/checkjwt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: userId,
                    token: token
                })
            });
            const data = await response.json();
            setHasPermission(data.status);
        }
        const checkOwner = async () => {
            const token = Cookies.get('current_token');
            const userId = Cookies.get('current_id');
            const searchParams = new URLSearchParams(window.location.search);
            const response = await fetch('http://127.0.0.1:8000/api/table/'+searchParams.get('id')+'/owner/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    token: token
                })
            });
            const data = await response.json();
            console.log(data.status);
            setHasOwner(data.status);
        }
        checkPermission();
        checkOwner();
    }, []);
    if (!hasPermission ) {
        return (<Err404 />);
    }
    if (hasOwner ) {
        return (
            <Container maxWidth="xl">
                <Stack
                    direction="row"

                    spacing={2}>
                    <AddTask  socket={ws}/>
                    <AddColumn  socket={ws}  />
                    <AddUser  socket={ws}  />
                    <ListUser socket={ws}/>

                </Stack>

                <TasksContainer   socket={ws}/>
            </Container>
        );

    }
    else{
        return (
            <Container maxWidth="xl">
                <Stack
                    direction="row"

                    spacing={2}>
                    <AddTask  socket={ws}/>


                </Stack>

                <TasksContainer   socket={ws}  />
            </Container>
        );
    }
};

export default Task;