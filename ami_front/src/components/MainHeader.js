import * as React from "react";
import MenuItem from '@mui/material/MenuItem';

import AppBar from "@mui/material/AppBar";
import Container from "@mui/material/Container";
import Toolbar from "@mui/material/Toolbar";
import AdbIcon from "@mui/icons-material/Adb";
import Typography from "@mui/material/Typography";
import Box from "@mui/material/Box";
import IconButton from "@mui/material/IconButton";
import MenuIcon from "@mui/icons-material/Menu";
import Menu from "@mui/material/Menu";
import Link from '@mui/material/Link';

import Tooltip from "@mui/material/Tooltip";
import Avatar from "@mui/material/Avatar";
import {useEffect, useState} from "react";
import Cookies from "js-cookie";
import Err404 from "../pages/Error404";

const pagesBasic = [
        {
            name:"About App",
            url:"/about-app"
        },
        {
            name: "Get start",
            url: "/get-start"
        }
];
const pagesLoged = [
    {
        name:"My Tables",
        url:"/my-tables"
    },
    {
        name: "Table",
        url: "/table"
    }
];
const pagesSettings = [
    {
        name:"Log in",
        url:"/login"
    },
    {
        name: "Register",
        url: "/register"
    }
];
const pagesUserSettings = [

    {
        name: "Settings",
        url: "/settings"
    },
    {
        name: "Logout",
        url: "/logout"
    }
];
const pages = ['About app', 'Get start'];
const settings = ['Profile', 'Settings', 'Tables', 'Logout'];

export default function MainHeader(){
    const [anchorElNav, setAnchorElNav] = React.useState(null);
    const [anchorElUser, setAnchorElUser] = React.useState(null);
    const [hasPermission, setHasPermission] = React.useState('');
    const [menuToRender, setMenuToRender] =  React.useState([]);
    const [menuUserToRender, setMenuUserToRender] =  React.useState([]);

    const handleOpenNavMenu = (event) => {
        setAnchorElNav(event.currentTarget);
    };
    const handleOpenUserMenu = (event) => {
        setAnchorElUser(event.currentTarget);
    };

    const handleCloseNavMenu = () => {
        setAnchorElNav(null);
    };

  // function GoPage(){
  //
  //       console.log("test");
  //   }

    const handleCloseUserMenu = () => {
        setAnchorElUser(null);
    };



    useEffect(() => {
        const checkPermission = async () => {
            const token = Cookies.get('current_token');
            const userId = Cookies.get('current_id');
            if(!token) {
                setMenuToRender(pagesBasic);
                setMenuUserToRender(pagesSettings);
                return;
            }
            const response = await fetch('http://127.0.0.1:8000/api/checkjwt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: userId,
                    token: token
                })
            });
            const data = await response.json();
            setHasPermission(data.status);
            console.log(data);
            if (!data.status ) {
                setMenuToRender(pagesBasic);
                setMenuUserToRender(pagesSettings);
            }
            else {
                setMenuToRender(pagesLoged);
                setMenuUserToRender(pagesUserSettings);
            }
        }
        checkPermission();

    }, []);


    return (
        <AppBar position="static">
            <Container maxWidth="xl">
                <Toolbar disableGutters>
                    <AdbIcon sx={{ display: { xs: 'none', md: 'flex' }, mr: 1 }} />
                    <Typography
                        variant="h6"
                        noWrap
                        component="a"
                        href="/"
                        sx={{
                            mr: 2,
                            display: { xs: 'none', md: 'flex' },
                            fontFamily: 'monospace',
                            fontWeight: 700,
                            letterSpacing: '.3rem',
                            color: 'inherit',
                            textDecoration: 'none',
                        }}
                    >
                        LOGO
                    </Typography>

                    <Box sx={{ flexGrow: 1, display: { xs: 'flex', md: 'none' } }}>
                        <IconButton
                            size="large"
                            aria-label="account of current user"
                            aria-controls="menu-appbar"
                            aria-haspopup="true"
                            onClick={handleOpenNavMenu}
                            color="inherit"
                        >
                            <MenuIcon />
                        </IconButton>
                        <Menu
                            id="menu-appbar"
                            anchorEl={anchorElNav}
                            anchorOrigin={{
                                vertical: 'bottom',
                                horizontal: 'left',
                            }}
                            keepMounted
                            transformOrigin={{
                                vertical: 'top',
                                horizontal: 'left',
                            }}
                            open={Boolean(anchorElNav)}
                            onClose={handleCloseNavMenu}
                            sx={{
                                display: { xs: 'block', md: 'none' },
                            }}
                        >
                            {pages.map((page,index) => (
                                <MenuItem key={index}  to={page}>
                                    <Typography textAlign="center">{index}.{page}</Typography>
                                </MenuItem>
                            ))}
                        </Menu>
                    </Box>
                    <AdbIcon sx={{ display: { xs: 'flex', md: 'none' }, mr: 1 }} />
                    <Typography
                        variant="h5"
                        noWrap
                        component="a"
                        href=""
                        sx={{
                            mr: 2,
                            display: { xs: 'flex', md: 'none' },
                            flexGrow: 1,
                            fontFamily: 'monospace',
                            fontWeight: 700,
                            letterSpacing: '.3rem',
                            color: 'inherit',
                            textDecoration: 'none',
                        }}
                    >
                        LOGO
                    </Typography>
                    <Box sx={{ flexGrow: 1, display: { xs: 'none', md: 'flex' } }}>
                        {menuToRender.map((page) => (
                            <Link
                                href={page.url}
                                onClick={handleCloseNavMenu}
                                sx={{ m: 2, color: 'white', display: 'block' }}
                            >
                                {page.name}
                            </Link>
                        ))}
                    </Box>

                    <Box sx={{ flexGrow: 0 }}>
                        <Tooltip title="Open settings">
                            <IconButton onClick={handleOpenUserMenu} sx={{ p: 0 }}>
                                <Avatar alt="Remy Sharp"  />
                            </IconButton>
                        </Tooltip>
                        <Menu
                            sx={{ mt: '45px' }}
                            id="menu-appbar"
                            anchorEl={anchorElUser}
                            anchorOrigin={{
                                vertical: 'top',
                                horizontal: 'right',
                            }}
                            keepMounted
                            transformOrigin={{
                                vertical: 'top',
                                horizontal: 'right',
                            }}
                            open={Boolean(anchorElUser)}
                            onClose={handleCloseUserMenu}
                        >
                            {menuUserToRender.map((setting) => (
                                <Link
                                    href={setting.url}
                                    onClick={handleCloseNavMenu}
                                    sx={{ m: 2, color: 'black', display: 'block' }}
                                >
                                    <Typography textAlign="center">{setting.name}</Typography>
                                </Link>


                            ))}
                        </Menu>
                    </Box>
                </Toolbar>
            </Container>
        </AppBar>


    );
}