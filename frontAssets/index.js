import "./src/styles/index.scss";
import Router from "./src/Router.js";
import students from "./src/routes/students.js";
import students_history from "./src/routes/students-history.js";
import main_page from "./src/routes/main_page.js";
import Import from './src/routes/import.js';

$(document).ready(()=>{
    $('[data-toggle="tooltip"]').tooltip();
    Router('app').then(()=>{
        Router('import').then(()=>{
            Import();
        }).catch(e=>{console.log('Путь import не найден')});
        Router('students').then(()=>{
            students();
        }).catch(e=>{console.log('Путь students не найден')});
        Router('students-history').then(()=>{
            students_history();
        }).catch(e=>{console.log('Путь students-history не найден')});
        Router('main').then(()=>{
            main_page();
        }).catch(e=>{console.log('Путь main не найден')});
    }).catch(e=>{console.log('Путь app не найден')});

});