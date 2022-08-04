/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import brand1 from '/image/l8/brand-logo-1.svg';
import brand2 from '/image/l8/brand-logo-2.svg';
import brand3 from '/image/l8/brand-logo-3.svg';
import clien3 from '/image/l8/client-img-3.png';
import clien2 from '/image/l8/client-img-2.png';
import clien1 from '/image/l8/client-img-1.png';
import herobg from '/image/l8/hero-bg.png';
import video from '/image/l8/video-bg.png';
import brand4 from '/image/l8/brand-logo-4.svg';
import quote from '/image/l8/quote.png';
import lineSvg from '/image/l8/line.svg';
import heroImg from '/image/l8/hero-img.png';
import foote3 from '/image/l8/footer-shape.svg';
import foote2 from '/image/l8/footer-shape-2.svg';
import foote1 from '/image/l8/footer-shape-1.svg';
import conte1 from '/image/l8/content-image-1.png';
import conte2 from '/image/l8/content-image-2.png';
import conte3 from '/image/l8/content-image-3.png';
import conte4 from '/image/l8/content-image-4.png';



// any CSS you import will output into a single css file (app.css in this case)
import '/node_modules/bootstrap/dist/css/bootstrap.css';
import '/custom_modules/fonts/icon-font/css/style.css';
import '/custom_modules/fonts/typography-font/typo.css';
import '/custom_modules/fonts/fontawesome-5/css/all.css';
import './styles/app.css';

// start the Stimulus application
// import 'bootstrap';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});