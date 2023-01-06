import * as React from 'react';
import Box from '@mui/material/Box';
import TextField from '@mui/material/TextField';
import Typography from "@mui/material/Typography";
import Button from "@mui/material/Button";

export default function CommentsForm() {
    const addComment = () => {

    };

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
                <TextField fullWidth label="Write Comment" id="fullWidth" />
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
        </Box>
    );
}