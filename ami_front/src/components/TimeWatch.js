import React, { useState, useEffect } from "react";


import Typography from "@mui/material/Typography";
import Box from "@mui/material/Box";

import PlayCircleIcon from '@mui/icons-material/PlayCircle';
import PauseIcon from '@mui/icons-material/Pause';
import RestartAltIcon from '@mui/icons-material/RestartAlt';

const Stopwatch = () => {
    const [time, setTime] = useState(0);
    const [running, setRunning] = useState(false);

    useEffect(() => {
        let interval;
        if (running) {
            interval = setInterval(() => {
                setTime((prevTime) => prevTime + 10);
            }, 10);
        } else if (!running) {
            clearInterval(interval);
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
                    <button onClick={() => setRunning(true)}><PlayCircleIcon/></button>
                    <button onClick={() => setRunning(false)}><PauseIcon/></button>
                    <button onClick={() => setTime(0)}><RestartAltIcon/></button>
                </div>
            </div>
        </Box>
    );
};



export default Stopwatch;
