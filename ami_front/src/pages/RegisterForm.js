import React, { useState } from "react";
import Box from '@mui/material/Box';
import TextField from '@mui/material/TextField';
import Container from "@mui/material/Container";
import Button from '@mui/material/Button';
import Cookies from "js-cookie";



export default function BasicTextFields() {

    const [emailNew, setEmailNew] = useState('');
    const [passwordNew, setPasswordNew] = useState('');
    const [passwordRNew, setPasswordRNew] = useState('');
    const startLogin = () => {
        var myHeaders = new Headers();
        myHeaders.append("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9");

        myHeaders.append("Content-Type", "application/json");


        var raw = JSON.stringify({
            "email": emailNew,
            "password": passwordNew
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
                Cookies.set('current_id', data["user"], { expires: 1 });
                Cookies.set('current_token', data["token"], { expires: 1 });
                checkToken();
            })


            .catch(error => {
                console.log('error', error);
                return false;
            });

    }
    const checkToken = () =>{

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
    const registerNew = (e) => {
        e.preventDefault();
        if(passwordNew != passwordRNew) {
            alert("Your passwords are different");
            return;
        }
        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/x-www-form-urlencoded");


        var urlencoded = new URLSearchParams();
        urlencoded.append("user[email]", emailNew);
        urlencoded.append("user[password][first]", passwordNew);
        urlencoded.append("user[password][second]", passwordNew);

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: urlencoded,
            redirect: 'follow'
        };

        fetch("http://127.0.0.1:8000/register", requestOptions)
            .then(response => response.json())
            .then(result =>{
                if(result.status == "Registered"){
                    alert(result.status);
                    startLogin();
                }

            })
            .catch(error => console.log('error', error));
    }


    return (
        <Container maxWidth="xl" className="main-content login-page">
           <Box
               component="form"
               sx={{
                   '& > :not(style)': { m: 1, width: '50ch' },
               }}
               noValidate
               autoComplete="off"
               onSubmit={registerNew}
           >
               <Box>
                   <TextField id="outlined-basic" label="E-mail" variant="outlined" fullWidth="true" onChange={(e) => setEmailNew(e.target.value)}/>
               </Box>
               <Box>
                   <TextField id="outlined-basic" label="Password" variant="outlined" fullWidth="true" type="password" onChange={(e) => setPasswordNew(e.target.value)}/>
               </Box>
                <Box>
                    <TextField id="outlined-basic" label="Re-Password" type="password" variant="outlined" onChange={(e) => setPasswordRNew(e.target.value)} fullWidth="true" />
                </Box>

                <Box className="button-center">

                    <Button variant="contained" type="submit" fullWidth="true">Register</Button>
                    
                </Box>
           </Box>
        </Container>
    );
}