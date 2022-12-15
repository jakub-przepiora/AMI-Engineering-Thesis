import * as React from 'react';
import './App.css';
import { styled } from '@mui/material/styles';

// own import
import MainHeader from './components/MainHeader';
import ContentMainPage from './components/ContentMainPage';
import MyTablesView from './components/MyTables';
import LoginForm from './pages/LoginForm';
import RegisterForm from './pages/RegisterForm';

// own import

import {
    BrowserRouter as Router,
    Routes,
    Route,
    Link
} from "react-router-dom";


function Home() {
    return (
        <div>
            <ContentMainPage/>
        </div>
    );
}




function AboutApp() {
    return (
        <div className='data'>
            <h1>About App</h1>
        </div>
    );
}


function GetStart() {
    return (
        <div className='data'>
            <h1>sData Page</h1>
        </div>
    );
}

function MyTables() {
    return (
        <div className='data'>
            <MyTablesView/>
        </div>
    );
}

function Table() {
    return (
        <div className='data'>
            <MyTablesView/>
        </div>
    );
}




function App() {
    return(
        <div>
            <MainHeader/>
            <Router>
                {/*<Sidebar />*/}
                <Routes>
                    <Route exact path='/' element={<Home/>} />
                    <Route  path='/about-app' element={<AboutApp/>} />
                    <Route path='/get-start' element={<GetStart/>} />
                    <Route path='/my-tables' element={<MyTables/>} />
                    <Route path='/login' element={<LoginForm/>} />
                    <Route path='/register' element={<RegisterForm/>} />

                </Routes>
            </Router>
            {/*<ContentMainPage/>*/}
        </div>
    );
}

export default App;
