import * as React from 'react';
import './App.css';
import { styled } from '@mui/material/styles';

// own import
import MainHeader from './components/MainHeader';
import ContentMainPage from './components/ContentMainPage';
import MyTablesView from './components/MyTables';

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
                </Routes>
            </Router>
            {/*<ContentMainPage/>*/}
        </div>
    );
}

export default App;
