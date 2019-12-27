import "./src/styles/index.scss";
import Router from "./src/Router";
import students from "./src/routes/students";
import students_history from "./src/routes/students-history";
import main_page from "./src/routes/main_page"

$(document).ready(()=>{
    $('[data-toggle="tooltip"]').tooltip();
    Router('app').then(()=>{
        Router('students').then(()=>{

            students();
        }).catch(e=>{});
        Router('students-history').then(()=>{

            students_history();
        }).catch(e=>{});
        Router('main').then(()=>{

            main_page();
        })
    });

});