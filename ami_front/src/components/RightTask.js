import * as React from 'react';
import Box from '@mui/material/Box';
import Drawer from '@mui/material/Drawer';
import Button from '@mui/material/Button';

import Typography from "@mui/material/Typography";
import TextField from "@mui/material/TextField";

import CommentsForm from '../components/CommentsForm'
import Stopwatch from "./TimeWatch";
import FilesSection from "./FilesSection";
import AssignUser from "./AssignUser";

export default function TemporaryDrawer(props) {
    const [state, setState] = React.useState({
        top: false,
        left: false,
        bottom: false,
        right: false,
    });
    const [clickedTask, setClickedTask] =  React.useState(props.taskid);
    const [titleTask, setTitleTask] =  React.useState(props.title);
    const [contentTask, setContentTask] =  React.useState(props.content);

    const toggleDrawer = (anchor, open) => (event) => {
        if (event.type === 'keydown' && (event.key === 'Tab' || event.key === 'Shift')) {
            return;
        }

        setState({ ...state, [anchor]: open });
    };

    const list = (anchor) => (

        <Box
            sx={{ width: anchor === 'top' || anchor === 'bottom' ? 'auto' : 700 }}
            role="presentation"
            // onClick={toggleDrawer(anchor, false)}

            className="taskSlide"
        >

            <Typography variant="h3" gutterBottom>
                {clickedTask}.{titleTask}
            </Typography>
            <div className="desc-task">
                <b>Description:</b>
                <Typography variant="subtitle1" gutterBottom>
                    {contentTask}
                </Typography>
            </div>
            <div>
                <FilesSection taskid={clickedTask}/>
            </div>
            <div>
                <CommentsForm taskid={clickedTask}/>
            </div>
        </Box>
    );

    return (
        <div>

                <React.Fragment key="right">

                    <a onClick={toggleDrawer("right", true)}>
                        <Typography variant="h5" gutterBottom>
                            {titleTask}
                        </Typography>
                        {contentTask}
                    </a>
                    <Drawer
                        anchor={"right"}
                        open={state["right"]}

                    >
                        <Button variant="contained"
                                onClick={toggleDrawer("right", false)}
                                size="medium"
                        >
                            Close
                        </Button>
                        <AssignUser/>
                        <Stopwatch taskid={clickedTask}/>
                        {list("right",)}
                    </Drawer>
                </React.Fragment>

        </div>
    );
}