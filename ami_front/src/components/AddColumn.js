import React, { useState } from "react";
import Button from '@mui/material/Button';
import Modal from '@mui/material/Modal';
import Box from '@mui/material/Box';
import Container from "@mui/material/Container";
import TextField from '@mui/material/TextField';

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

const AddColumn = ({ socket }) => {
	const [columnTitle, setColumnTitle] = useState("");
	const [columnDesc, setColumnDesc] = useState("");
	const [open, setOpen] = useState(false);

	const handleOpen = () => {
		setOpen(true);
	};

	const handleClose = () => {
		setOpen(false);
	};

	const handleAddTodo = (e) => {
		e.preventDefault();
		//socket.emit("createTask", { column });
		setColumnTitle("");
	};
	return (
		<div >
			<div className="controlBtn">
				<Button variant="contained" color="primary" onClick={handleOpen} >
					Add column +
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
						<h2 id="simple-modal-title">Modal Add column</h2>

						<form className='form__input' onSubmit={handleAddTodo}>
							<Box
								sx={{
									width: 300,
									height: 300,

								}}
							>
								<TextField className='input' id="column_title" onChange={(e) => setColumnTitle(e.target.value)} label="Title" value={columnTitle}  variant="outlined" required/>

								<TextField className='input' id="column_desc" name='column_desc' onChange={(e) => setColumnDesc(e.target.value)}  label="Short Description" value={columnDesc}  variant="outlined" required/>

								<Button className='addTodoBtn' variant="contained">Add Task</Button>

							</Box>
						</form>
					</div>
				</Box>
			</Modal>
		</div>

	);
};

export default AddColumn;
