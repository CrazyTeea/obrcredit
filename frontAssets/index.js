import "./src/styles/index.scss";
import Router from "./src/Router";
import students from "./src/routes/students";
import students_history from "./src/routes/students-history";

$(document).ready(()=>{
    $('[data-toggle="tooltip"]').tooltip();
    Router('students').then(()=>{
        students();
    }).catch(e=>{});
    Router('students-history').then(()=>{
        students_history();
    }).catch(e=>{});
});