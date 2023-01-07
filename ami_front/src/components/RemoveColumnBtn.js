import React, { useState } from "react";
import Button from '@mui/material/Button';


import DoDisturbIcon from '@mui/icons-material/DoDisturb';
import Cookies from "js-cookie";


const RemoveColumnBtn = (props) => {




    const removeColumn = () => {
        if (!window.confirm('Are you sure you want to delete this item?')) {
            return;
        }

        const token = Cookies.get('current_token');
        const userId = Cookies.get('current_id');
        var myHeaders = new Headers();


        myHeaders.append("Content-Type", "application/json");


        var raw = JSON.stringify({
            "user_id": userId,
            "token": token,
            "col_id": props.colid,

        });

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };
        const searchParams = new URLSearchParams(window.location.search);
        fetch("http://127.0.0.1:8000/api/table/"+searchParams.get('id')+"/column/remove", requestOptions)
            .then(response => response.json())
            .then(data => {
                alert("Removed Column");
                window.location.reload();
            })
            .catch(error => console.log('error', error));


    };
    if(!props.ownerStatus) {
        return (
            <div>
                <div className="controlBtn">
                    <Button variant="contained" color="error">
                        <DoDisturbIcon onClick={removeColumn}/>
                    </Button>
                </div>

            </div>

        );
    }

};

export default RemoveColumnBtn;
