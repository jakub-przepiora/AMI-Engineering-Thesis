import React from 'react';

const CommentsSection = ({ comments }) => {
    return (
        <div >
            {comments.map((comment) => (
                <div key={comment.id} className="single--omment">
                    <p>{comment.text}</p>
                    <p><i> By <b>{comment.creator}</b></i></p>

                </div>
            ))}
        </div>
    );
};

export default CommentsSection;