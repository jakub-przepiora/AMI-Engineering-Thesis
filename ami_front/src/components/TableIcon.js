import * as React from 'react';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import CardMedia from '@mui/material/CardMedia';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import Grid from '@mui/material/Grid';
import Cookies from "js-cookie";

class TableIcon extends React.Component {

    // state = {
    //     hasPermission: false
    // }
    //
    // componentDidMount() {
    //     this.checkPermission();
    // }



    constructor(props) {
        super(props);
        this.state = {
            title: this.props.title,
            desc: this.props.desc,
            id: "/table?id="+this.props.id,
            tableid: this.props.id,
            raportUrl: "http://127.0.0.1:8000/raport/table/"+this.props.id
        };
    }

    removeTable(id) {
        if (!window.confirm('Are you sure you want to delete this table?')) {
            return;
        }

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

        fetch("http://127.0.0.1:8000/table/"+id+"/remove", requestOptions)
            .then(response => response.json())
            .then(data => {
                alert("Removed table");
                window.location.reload();
            })
            .catch(error => console.log('error', error));


    }


    render() {



        return (

            <Grid item xs={3}>
                <Card sx={{maxWidth: 345}}>
                    <CardMedia
                        component="img"
                        height="140"
                        image={`${process.env.PUBLIC_URL}/vintage-table.jpg`}
                        alt="green iguana"
                    />
                    <CardContent>
                        <Typography gutterBottom variant="h5" component="div">
                            {this.state.title}
                        </Typography>
                        <Typography variant="body2" color="text.secondary">
                            {this.state.desc}
                        </Typography>
                    </CardContent>
                    <CardActions>
                        <Button size="small" href={this.state.id}>Open</Button>
                        <Button size="small" onClick={()=>this.removeTable(this.state.tableid)}>Delete</Button>
                        <Button size="small" href={this.state.raportUrl}>Raport</Button>
                    </CardActions>
                </Card>
            </Grid>
        );

    }
}

export default TableIcon;
