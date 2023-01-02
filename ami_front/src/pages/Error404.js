import * as React from 'react';
import Container from "@mui/material/Container";



export default function Error404() {
    return (
        <Container maxWidth="xl" className="main-content login-page">
            <h2>Permission denied</h2>
            <a href="/login">Back to login page</a>
        </Container>
    );
}