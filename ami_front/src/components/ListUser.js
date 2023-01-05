import React, {useEffect, useState} from "react";
import Button from '@mui/material/Button';
import Modal from '@mui/material/Modal';
import Box from '@mui/material/Box';

import Cookies from "js-cookie";

import DeleteIcon from '@mui/icons-material/Delete';
import IconButton from '@mui/material/IconButton';
import Tooltip from '@mui/material/Tooltip';
import {Stack} from "@mui/material";
import Paper from '@mui/material/Paper';
import { styled } from '@mui/material/styles';

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

const ListUser = ({ socket }) => {
	const [users, setUsers] = useState([]);
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


	const deleteClick = (id) => {
		const token = Cookies.get('current_token');
		const userId = Cookies.get('current_id');
		var myHeaders = new Headers();


		myHeaders.append("Content-Type", "application/json");


		var raw = JSON.stringify({
			"user_id": userId,
			"token": token,
			"id_del": id

		});

		var requestOptions = {
			method: 'POST',
			headers: myHeaders,
			body: raw,
			redirect: 'follow'
		};
		const searchParams = new URLSearchParams(window.location.search);
		fetch("http://127.0.0.1:8000/api/table/"+searchParams.get('id')+"/user/remove", requestOptions)
			.then(response => response.json())
			.then(data => {

				alert(data.state);
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
	return (
		<div >
			<div className="controlBtn">
				<Button variant="contained" color="primary" onClick={handleOpen} >
					Team Table
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
												deleteClick(user.id);
											}} >
												<DeleteIcon />
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

export default ListUser;
