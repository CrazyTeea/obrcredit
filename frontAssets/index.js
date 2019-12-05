import "./src/styles/index.scss";
import Router from "./src/Router";
import students from "./src/routes/students";

$(document).ready(()=>{
    $('[data-toggle="tooltip"]').tooltip();
    Router('students').then(()=>{
        students();
    }).catch(e=>{});
});