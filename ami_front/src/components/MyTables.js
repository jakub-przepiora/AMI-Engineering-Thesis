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
import Link from "@mui/material/Link";
import AddNewTable from "./AddNewTable";
import CircularProgress from "@mui/material/CircularProgress";

class MyTablesView extends React.Component {

    state = {
        hasPermission: null,
        tables: [],
        teamTables: []
    }

    componentDidMount() {
        this.checkPermission();
        this.getOwnerTables();
        this.getTeamTables();

    }

    checkPermission = async () => {
        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');
        if(!userId || !token) {
            this.setState({ hasPermission: false});
            return;
        }
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
    getOwnerTables = async () => {
        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');
        if(!userId || !token) {
            this.setState({ hasPermission: false});
            return;
        }
        const response = await fetch('http://127.0.0.1:8000/api/tables', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: userId,
                token: token
            })
        });
        const data = await response.json();
        this.setState({ tables: data });

    }

    getTeamTables = async () => {
        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');
        if(!userId || !token) {
            this.setState({ hasPermission: false});
            return;
        }
        const response = await fetch('http://127.0.0.1:8000/api/teamtables', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: userId,
                token: token
            })
        });
        const data = await response.json();

        if(!data.status)
        this.setState({ teamTables: data });

    }




    render() {
        if (this.state.hasPermission == null ) {

            return (
                <Container maxWidth="xl">
                    <Box sx={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        minHeight:'100%',
                        height:'500px',

                    }}>
                        <CircularProgress />
                    </Box>
                </Container>
            );
        }

        if (!this.state.hasPermission || !this.state.teamTables) {
            return (<Err404 />);
        }

        return (

            <Container maxWidth="xl" className="main-content">
                <h3>My tables</h3>
                <Grid container spacing={2}>

                    {this.state.tables.map((tab) => (
                        <TableIcon
                            title={tab.tab_name}
                            id={tab.id}
                            desc={tab.description}
                        />

                    ))}



                    {/*<Grid item xs={3}>*/}
                    {/*    <Card sx={{maxWidth: 345}}>*/}
                    {/*        <div style={{ height: '185px'}}></div>*/}
                    {/*        <CardContent>*/}
                    {/*            <Typography gutterBottom variant="h4" component="div">*/}
                    {/*                Add new table*/}
                    {/*            </Typography>*/}

                    {/*        </CardContent>*/}

                    {/*    </Card>*/}
                    {/*</Grid>*/}
                    <AddNewTable/>
                </Grid>
                <h3>Teammates tables</h3>
                <Grid container spacing={2}>

                    {this.state.teamTables.map((tab) => (
                        <TableIcon
                            title={tab.tab_name}
                            id={tab.id}
                            desc={tab.description}
                        />

                    ))}




                </Grid>
            </Container>
        );

    }
}

export default MyTablesView;
