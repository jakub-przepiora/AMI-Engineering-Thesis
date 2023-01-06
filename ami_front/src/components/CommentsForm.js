import * as React from 'react';
import Box from '@mui/material/Box';
import TextField from '@mui/material/TextField';
import Typography from "@mui/material/Typography";
import Button from "@mui/material/Button";
import CommentsSection from "./SingleComment";
import {useEffect, useState} from "react";
import Cookies from "js-cookie";

export default function CommentsForm(props) {

    const [comments, setComments] = useState([]);
    const [upCom, setUpCom] = useState("");
    const [newComment, setNewComment] = useState("");
    const addComment = (e) => {
        e.preventDefault();
        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');


        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");

        var raw = JSON.stringify({
            "user_id": userId,
            "token": token,
            "task_id": props.taskid,
            "content": newComment
        });

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };
        const searchParams = new URLSearchParams(window.location.search);
        fetch("http://localhost:8000/api/table/"+searchParams.get('id')+"/comment/add", requestOptions)
            .then(response => response.json())
            .then(result => {

                setUpCom("update");
            })
            .catch(error => console.log('error', error));
    };
    useEffect(() => {
        const getAllComments = () => {

            const token = Cookies.get('current_token');
            const userId = Cookies.get('current_id');


            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");

            var raw = JSON.stringify({
                "user_id": userId,
                "token": token,
                "task_id": props.taskid
            });

            var requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };
            const searchParams = new URLSearchParams(window.location.search);
            fetch("http://localhost:8000/api/table/"+searchParams.get('id')+"/comments", requestOptions)
                .then(response => response.json())
                .then(result => {

                    setComments(result);
                })
                .catch(error => console.log('error', error));
        };
        getAllComments();
    }, [upCom]);
    useEffect(() => {setUpCom("1");}, []);

    return (
        <Box
            sx={{
                width: 500,
                maxWidth: '100%',
                paddingTop:'20px'
            }}
        >
            <Typography variant="h6" gutterBottom>
                Comments:
            </Typography>
            <form className='form__input' onSubmit={addComment}>
                <TextField fullWidth
                           label="Write Comment"
                           id="fullWidth"
                           onChange={(e) => setNewComment(e.target.value)}
                />
                <Button variant="contained"
                        type="submit"
                        size="medium"
                        sx={{
                            marginTop:'10px'
                        }}

                >
                    Add comment
                </Button>
            </form>
            <div>
                <CommentsSection comments={comments} />
            </div>
        </Box>
    );
}