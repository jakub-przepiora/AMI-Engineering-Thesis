import React from "react";
import AddTask from "../components/AddTask";
import TasksContainer from "../components/TasksContainer";
import socketIO from "socket.io-client";

const socket = socketIO.connect("http://127.0.0.1:8000");

const Task = () => {
    return (
        <div>

            <AddTask socket={socket} />
            <TasksContainer socket={socket} />
        </div>
    );
};

export default Task;