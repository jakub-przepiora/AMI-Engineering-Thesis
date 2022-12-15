import * as React from 'react';
import Box from '@mui/material/Box';
import TextField from '@mui/material/TextField';
import Container from "@mui/material/Container";
import Button from '@mui/material/Button';


export default function BasicTextFields() {
    return (
        <Container maxWidth="xl" className="main-content login-page">
           <Box
               component="form"
               sx={{
                   '& > :not(style)': { m: 1, width: '50ch' },
               }}
               noValidate
               autoComplete="off"
           >
               <Box>
                   <TextField id="outlined-basic" label="E-mail" variant="outlined" fullWidth="true" />
               </Box>
               <Box>
                   <TextField id="outlined-basic" label="Password" variant="outlined" fullWidth="true" />
               </Box>
               <Box className="button-center">
                    <Button variant="contained" fullWidth="true">Login</Button>
               </Box>
           </Box>
        </Container>
    );
}