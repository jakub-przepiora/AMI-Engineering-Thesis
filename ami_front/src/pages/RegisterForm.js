import React, { useState } from "react";
import Box from '@mui/material/Box';
import TextField from '@mui/material/TextField';
import Container from "@mui/material/Container";
import Button from '@mui/material/Button';



export default function BasicTextFields() {

    const [emailNew, setEmailNew] = useState('');
    const [passwordNew, setPasswordNew] = useState('');

    const registerNew = (e) => {
        e.preventDefault();
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
                alert(result.status);
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
                   <TextField id="outlined-basic" label="Password" variant="outlined" fullWidth="true" onChange={(e) => setPasswordNew(e.target.value)}/>
               </Box>
                <Box>
                    <TextField id="outlined-basic" label="Re-Password" variant="outlined" fullWidth="true" />
                </Box>

                <Box className="button-center">

                    <Button variant="contained" type="submit" fullWidth="true">Register</Button>
                </Box>
           </Box>
        </Container>
    );
}