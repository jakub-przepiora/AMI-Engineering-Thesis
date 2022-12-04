import {styled} from "@mui/material/styles";
import Paper from "@mui/material/Paper";
import Container from "@mui/material/Container";
import Grid from "@mui/material/Grid";
import * as React from "react";

const Item = styled(Paper)(({ theme }) => ({
    backgroundColor: theme.palette.mode === 'dark' ? '#1A2027' : '#fff',
    ...theme.typography.body2,
    padding: theme.spacing(1),
    textAlign: 'center',
    color: theme.palette.text.secondary,
}));




export default function ContentMainPage() {
    return(
        <Container maxWidth="xl" className="main-content">
            <h1>AMI Tasks Manager</h1>
            <p>If you have your own projects you should use this app. This tasks manager help you separate tasks to small. This app make you more prodactive.</p>

            <h2>Quick start with us</h2>
            <Grid container spacing={2}>
                <Grid item xs={4}>
                    <Item>


                        <h3>Create account</h3>

                    </Item>
                </Grid>
                <Grid item xs={4}>
                    <Item>
                        <h3>Invite your collaboraties</h3>
                    </Item>
                </Grid>
                <Grid item xs={4}>
                    <Item>
                        <h3>Start projects</h3>
                    </Item>
                </Grid>

            </Grid>
        </Container>
    );
}