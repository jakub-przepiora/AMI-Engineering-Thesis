import * as React from 'react';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import CardMedia from '@mui/material/CardMedia';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import Grid from '@mui/material/Grid';
import Box from '@mui/material/Box';
import Paper from '@mui/material/Paper';
import Container from "@mui/material/Container";
import Cookies from "js-cookie";
import Err404 from "../pages/Error404";
import TableIcon from "../components/TableIcon";

class MyTablesView extends React.Component {

    state = {
        hasPermission: false
    }

    componentDidMount() {
        this.checkPermission();
    }

    checkPermission = async () => {
        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');

        const response = await fetch('http://127.0.0.1:8000/api/checkjwt', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: userId,
                token: token
            })
        });
        const data = await response.json();
        this.setState({ hasPermission: data.status });
    }






    render() {


        if (!this.state.hasPermission) {
            return (<Err404 />);
        }
        return (

            <Container maxWidth="xl" className="main-content">

                <Grid container spacing={2}>

                    <TableIcon title={"test"} desc={"desc"} />


                    <Grid item xs={3}>
                        <Card sx={{maxWidth: 345}}>
                            <div style={{ height: '205px'}}></div>
                            <CardContent>
                                <Typography gutterBottom variant="h3" component="div">
                                    Add new table
                                </Typography>

                            </CardContent>

                        </Card>
                    </Grid>

                </Grid>

            </Container>
        );

    }
}

export default MyTablesView;
