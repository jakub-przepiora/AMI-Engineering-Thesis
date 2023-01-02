import * as React from 'react';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import CardMedia from '@mui/material/CardMedia';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import Grid from '@mui/material/Grid';

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
        };
    }




    render() {



        return (

            <Grid item xs={3}>
                <Card sx={{maxWidth: 345}}>
                    <CardMedia
                        component="img"
                        height="140"
                        image="/static/images/cards/contemplative-reptile.jpg"
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
                        <Button size="small">Share</Button>
                        <Button size="small">Learn More</Button>
                    </CardActions>
                </Card>
            </Grid>
        );

    }
}

export default TableIcon;
