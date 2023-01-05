import React from "react";
import AddTask from "../components/AddTask";
import TasksContainer from "../components/TasksContainer";

import AddColumn from "../components/AddColumn";
import {Stack} from "@mui/material";

import Container from "@mui/material/Container";
import AddUser from "../components/AddUser";


const Task = () => {

    const ws = new WebSocket('ws://localhost:3001');
    return (
        <Container maxWidth="xl">
            <Stack
                direction="row"

                spacing={2}>
                <AddTask  socket={ws}/>
                <AddColumn  socket={ws}  />
                <AddUser  socket={ws}  />
            </Stack>

            <TasksContainer   socket={ws} />
        </Container>
    );
};

export default Task;