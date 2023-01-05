import React, { useState, useEffect } from "react";
// import { Link } from "react-router-dom";
import { DragDropContext, Draggable, Droppable, useDrop } from "react-beautiful-dnd";

import Cookies from "js-cookie";


// const ws = new WebSocket('ws://localhost:3001');

// const tasks = [
// 	{ id: "1", content: "First task" },
// 	{ id: "2", content: "Second task" },
// 	{ id: "3", content: "Third task" },
// 	{ id: "4", content: "Fourth task" },
// 	{ id: "5", content: "Fifth task" }
// ];
//
// const taskStatus = {
// 	requested: {
// 		name: "Requested",
// 		items: tasks
// 	},
// 	toDo: {
// 		name: "To do",
// 		items: []
// 	},
// 	inProgress: {
// 		name: "In Progress",
// 		items: []
// 	},
// 	done: {
// 		name: "Done",
// 		items: []
// 	}
// };







function App(props) {
	const [wss, setWs, getWs] = useState(null);
	const [message, setMessage] = useState('');
	const [messages, setMessages] = useState([]);

	const [tasks, setTasks] = useState([]);
	const [taskStatus, setTaskStatus] = useState({});
	const [columns, setColumns] = useState(taskStatus);
	const [columnsCurr, setColumnsCurr] = useState(columns);
	const [emptyTable, setEmptyTable] =useState('');
	useEffect(() => {
		//WEB SOCKETS

		const updateTask = (data) => {
			const token = Cookies.get('current_token');
			const userId = Cookies.get('current_id');
			var myHeaders = new Headers();
			myHeaders.append("Content-Type", "application/json");
			var raw = JSON.stringify({
				"user_id": userId,
				"token": token,
				"cols": data
			});

			var requestOptions = {
				method: 'POST',
				headers: myHeaders,
				body: raw,
				redirect: 'follow'
			};
			const searchParams = new URLSearchParams(window.location.search);
			fetch("http://127.0.0.1:8000/api/table/"+searchParams.get('id')+"/update", requestOptions)
				.then((res) => res.json())
				.then((data) => {

					var getTab = JSON.stringify(data).slice(1,-1);

					var inToJson = JSON.parse(getTab.replaceAll("'",'"'));

					setColumns(inToJson);

				});
		}

		const fetchTasks = () => {
			const token = Cookies.get('current_token');
			const userId = Cookies.get('current_id');
			var myHeaders = new Headers();
			myHeaders.append("Content-Type", "application/json");
			var raw = JSON.stringify({
				"user_id": userId,
				"token": token
			});

			var requestOptions = {
				method: 'POST',
				headers: myHeaders,
				body: raw,
				redirect: 'follow'
			};
			const searchParams = new URLSearchParams(window.location.search);
			fetch("http://127.0.0.1:8000/api/table/"+searchParams.get('id'), requestOptions)
				.then((res) => res.json())
				.then((data) => {
					console.log("dasdasd");
					console.log(data);
					if(data == '{}') {
						setEmptyTable("Empty table");
						alert("P");
					}
					else {

						var getTab = JSON.stringify(data).slice(1, -1);

						setColumns(JSON.parse(getTab.replaceAll("'", '"')));
					}
				});
		}

		fetchTasks();
		const wss = new WebSocket('ws://localhost:3001');

		wss.onopen = () => {
			console.log('WebSocket connection established');
		};

		wss.onmessage = (event) => { // recipient
			const data = JSON.parse(event.data);

			const sCol = JSON.stringify(columns);
			const sData = JSON.stringify(data);
			if(sCol != sData){

				setColumns(data);
				updateTask(data);
				wss.ontest("tests");
			}



		};

		wss.onerror = (error) => {
			console.error('WebSocket error:', error);
		};

		wss.onclose = () => {
			console.log('WebSocket connection closed');
		};
		wss.ontest = () => {
			console.log('WebSocket test');

		};

		setWs(wss);


	}, []);



	useEffect(() => {

		if(wss !== null){

			wss.send(JSON.stringify(columns));

		}

	}, [columnsCurr]);

// =====WEB SOCKETS

	const onDragEnd = (result, columns, setColumns, ws, columnsCurr, setColumnsCurr) => {
		if (!result.destination) return;
		const { source, destination } = result;

		if (source.droppableId !== destination.droppableId) {
			const sourceColumn = columns[source.droppableId];
			const destColumn = columns[destination.droppableId];
			const sourceItems = [...sourceColumn.items];
			const destItems = [...destColumn.items];
			const [removed] = sourceItems.splice(source.index, 1);
			destItems.splice(destination.index, 0, removed);
			setColumns({
				...columns,
				[source.droppableId]: {
					...sourceColumn,
					items: sourceItems
				},
				[destination.droppableId]: {
					...destColumn,
					items: destItems
				}
			});
			setColumnsCurr({
				...columns,
				[source.droppableId]: {
					...sourceColumn,
					items: sourceItems
				},
				[destination.droppableId]: {
					...destColumn,
					items: destItems
				}
			});


		} else {
			const column = columns[source.droppableId];
			const copiedItems = [...column.items];
			const [removed] = copiedItems.splice(source.index, 1);
			copiedItems.splice(destination.index, 0, removed);
			setColumns({
				...columns,
				[source.droppableId]: {
					...column,
					items: copiedItems
				}
			});
			setColumnsCurr({
				...columns,
				[source.droppableId]: {
					...column,
					items: copiedItems
				}
			});

		}


	};

	return (
		<div>

			<div
				style={{ display: "flex", justifyContent: "center", height: "100%" }}
			>
				<DragDropContext
					onDragEnd={(result) => onDragEnd(result, columns, setColumns, wss, columnsCurr, setColumnsCurr)}>
					{/*{Object.entries(columns).map(([columnId, column], index) => {*/}
					{Object.entries(columns).map(([columnId, column], index) => {
						return (
							<div
								style={{
									display: "flex",
									flexDirection: "column",
									alignItems: "center"
								}}
								key={index}
								index={columnId}
								className={"first"}
							>
								<h2>{column.name}</h2>
								<div style={{ margin: 8 }}>
									{/*<Droppable droppableId={columnId} key={columnId} className={"drops"}>*/}
									<Droppable droppableId={columnId} key={columnId}  index={columnId} className={"drops"} >
										{(provided, snapshot) => {
											return (
												<div
													{...provided.droppableProps}
													ref={provided.innerRef}
													style={{
														background: snapshot.isDraggingOver
															? "lightblue"
															: "lightgrey",
														padding: 4,
														width: 250,
														minHeight: 500
													}}
												>
													{column.items.map((item, index) => {
														return (
															<Draggable
																key={item.id}
																draggableId={item.id}
																index={index}

															>
																{(provided, snapshot) => {
																	return (
																		<div
																			ref={provided.innerRef}
																			{...provided.draggableProps}
																			{...provided.dragHandleProps}
																			style={{
																				userSelect: "none",
																				padding: 16,
																				margin: "0 0 8px 0",
																				minHeight: "50px",
																				backgroundColor: snapshot.isDragging
																					? "#263B4A"
																					: "#456C86",
																				color: "white",
																				...provided.draggableProps.style
																			}}
																		>
																			{item.content}
																		</div>
																	);
																}}
															</Draggable>
														);
													})}
													{provided.placeholder}
												</div>
											);
										}}
									</Droppable>
								</div>
							</div>
						);
					})}
				</DragDropContext>
			</div>
		</div>
	);
}

export default App;
