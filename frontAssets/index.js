import "./src/styles/index.scss";
import Router from "./src/Router";
import students from "./src/routes/students";
import students_history from "./src/routes/students-history";
import main_page from "./src/routes/main_page"

$(document).ready(()=>{
    $('[data-toggle="tooltip"]').tooltip();
    Router('app').then(()=>{
        console.log('app');
        Router('students').then(()=>{
            students();
            console.log('students')
        }).catch(e=>{});
        Router('students-history').then(()=>{
            students_history();
            console.log('students-history')
        }).catch(e=>{});
        Router('main').then(()=>{
            console.log('main');
            main_page();
        }).catch(e=>{});
    });

});