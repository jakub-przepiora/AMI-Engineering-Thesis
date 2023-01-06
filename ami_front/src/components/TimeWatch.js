import React, { useState, useEffect } from "react";


import Typography from "@mui/material/Typography";
import Box from "@mui/material/Box";

import PlayCircleIcon from '@mui/icons-material/PlayCircle';
import PauseIcon from '@mui/icons-material/Pause';
import RestartAltIcon from '@mui/icons-material/RestartAlt';
import IconButton from "@mui/material/IconButton";
import Cookies from "js-cookie";

const Stopwatch = (props) => {
    const [time, setTime] = useState(0);
    const [running, setRunning] = useState(false);



    useEffect(() => {
        const addTime = () => {
            const token = Cookies.get('current_token');
            const userId = Cookies.get('current_id');
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");

            var raw = JSON.stringify({
                "user_id": userId,
                "token": token,
                "time_value": time,
            });

            var requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };

            fetch("http://localhost:8000/timer/task/"+props.taskid+"/add", requestOptions)
                .then(response => response.text())
                .then(result => {

                    console.log(result);
                })
                .catch(error => console.log('error', error));
        };

        let interval;
        if (running) {
            interval = setInterval(() => {
                setTime((prevTime) => prevTime + 10);
            }, 10);
        } else if (!running) {
            clearInterval(interval);
            if(time != 0){
                addTime();
                setTime(0);
            }
        }
        return () => clearInterval(interval);
    }, [running]);
    return (
        <Box
            sx={{ padding: '20px' }}
        >
            <div className="stopwatch">
                <Typography variant="h6" gutterBottom>
                    <div className="numbers">
                        <span>{("0" + Math.floor((time / 60000) % 60)).slice(-2)}:</span>
                        <span>{("0" + Math.floor((time / 1000) % 60)).slice(-2)}:</span>
                        <span>{("0" + ((time / 10) % 100)).slice(-2)}</span>
                    </div>
                </Typography>
                <div className="buttons">
                    <IconButton onClick={() => setRunning(true)}>
                        <PlayCircleIcon/>
                    </IconButton>
                    <IconButton onClick={() => setRunning(false)}><PauseIcon/></IconButton>
                    {/*<IconButton onClick={() => setTime(0)}><RestartAltIcon/></IconButton>*/}
                </div>
            </div>
        </Box>
    );
};



export default Stopwatch;
