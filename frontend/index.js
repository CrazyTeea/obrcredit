import './src/styles/index.scss';
import Router from './src/Router.js';
import students from './src/routes/students.js';
import main_page from './src/routes/main_page.js';


$(document).ready(() => {
    $('[data-toggle="tooltip"]').tooltip();
    new Router('app').then(() => {
        new Router('students').then(() => {
            students();
            console.log('afd');
        }).catch(()=>console.log(''));
        new Router('main').then(() => {
            main_page();
        }).catch(()=>console.log(''));
    }).catch(()=>console.log(''));

});