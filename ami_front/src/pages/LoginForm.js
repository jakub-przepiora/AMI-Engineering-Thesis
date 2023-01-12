import * as React from 'react';
import Box from '@mui/material/Box';
import TextField from '@mui/material/TextField';
import Container from "@mui/material/Container";
import Button from '@mui/material/Button';
import {useState} from "react";
import Cookies from 'js-cookie';

function sendLoginRequest(email, password) {
    const data = { email: email, password: password };

    var myHeaders = new Headers();
    myHeaders.append("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9");
    // myHeaders.append("Upgrade-Insecure-Requests", "1");
    myHeaders.append("Content-Type", "application/json");
    // myHeaders.append("Cookie", "PHPSESSID=6hjaqe1il9bk97gd3s2lr491li");

    var raw = JSON.stringify({
        "email": email,
        "password": password
    });

    var requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: raw,
        redirect: 'follow'
    };

    fetch("http://127.0.0.1:8000/api/login", requestOptions)
        .then(response => response.json())
        .then(data => {
            if(data.error){
                alert(data.error);
                return;
            }
            Cookies.set('current_id', data["user"], { expires: 1 });
            Cookies.set('current_token', data["token"], { expires: 1 });
            checkToken();
        })


        .catch(error => {
            console.log('error', error);
            return false;
        });



}

function checkToken(){
    const token = Cookies.get('current_token');
    const userId = Cookies.get('current_id');
    var myHeaders = new Headers();

    myHeaders.append("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9");

    myHeaders.append("Content-Type", "application/json");


    var raw = JSON.stringify({
        "id": userId,
        "token": token
    });

    var requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: raw,
        redirect: 'follow'
    };

    fetch("http://127.0.0.1:8000/api/checkjwt", requestOptions)
        .then(response => response.json())
        .then(data => {
            if(data["status"] == true){
                window.location = "http://localhost:3000/my-tables";
            }
            else{
                console.log(data["status"])
            }
        })
        .catch(error => console.log('error', error));
}

export default function BasicTextFields() {
    // Declare a state variable for the input value
    const [password, setPassword] = useState('');
    const [email, setEmail] = useState('');

    // Update the state when the input value changes
    const handleChangeEmail = (event) => {
        setEmail(event.target.value);
    };
    const handleChangePassword = (event) => {
        setPassword(event.target.value);
    };

    // Handle form submission
    const handleSubmit = (event) => {
        event.preventDefault();
        sendLoginRequest(email,password)


    };
    return (
        <Container maxWidth="xl" className="main-content login-page">
            <form onSubmit={handleSubmit}>
               <Box

                   sx={{
                       '& > :not(style)': { m: 1, width: '50ch' },
                   }}
                   noValidate
                   autoComplete="off"

               >
                   <Box>
                       <TextField id="email" value={email} label="E-mail" variant="outlined" fullWidth="true" onChange={handleChangeEmail}/>
                   </Box>
                   <Box>
                       <TextField id="password" value={password} label="Password" variant="outlined" type="password" fullWidth="true" onChange={handleChangePassword}/>
                   </Box>
                   <Box className="button-center">
                        <Button type="submit" variant="contained" fullWidth="true">Login</Button>
                   </Box>
               </Box>
            </form>
        </Container>
    );
}