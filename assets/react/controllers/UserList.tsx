// assets/react/controllers/UserList.jsx
import React from 'react';

export default function (props) {

    return  <div>{this.props.map((user, i) => {
        console.log("Entered");
        // Return the element. Also pass key
        return (user)
    })}</div>;
}


