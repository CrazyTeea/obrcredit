import "./src/styles/index.scss";
import Router from "./src/Router";
import students from "./src/routes/students";
import students_history from "./src/routes/students-history";
import main_page from "./src/routes/main_page"
import Import from './src/routes/import'

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