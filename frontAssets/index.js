import "./src/styles/index.scss";
import Router from "./src/Router";
import students from "./src/routes/students";

$(()=>{
    Router('students').then(()=>{
       // $('[data-toggle="tooltip"]').tooltip();
        students();
    }).catch(e=>{
        console.log(e);
    });
});