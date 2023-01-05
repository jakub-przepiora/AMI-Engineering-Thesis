import React from "react";
import AddTask from "./AddTask";
import TasksContainer from "./TasksContainer";
import Nav from "./Nav";
import socketIO from "socket.io-client";

const socket = "ws://localhost:3001";

const Task = () => {
	return (
		<div>
			{/*<Nav />*/}
			{/*<AddTask socket={socket} />*/}
			<TasksContainer socket={socket} />
		</div>
	);
};

export default Task;
