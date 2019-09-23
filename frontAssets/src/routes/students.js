import Router from "../Router";

export default ()=>{
    Router('index').then(()=>{

    }).catch(e=>{
        console.log("index не найден");
    });
    function disable(e_status){

        let elements = document.getElementsByTagName('input');
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].type === 'radio' && elements[i].name ==='Students[osnovanie]'
                || (elements[i].type === 'file' && elements[i].name ==='Students[rasp_act0]')
                || (elements[i].type === 'file' && elements[i].name ==='Students[dogovor]')
                || (elements[i].type === 'file' && elements[i].name ==='Students[rasp_act_otch]')) {
                elements[i].disabled = !elements[i].disabled;
            }
            if (elements[i].type === 'radio' && elements[i].name ==='Students[osnovanie]' && elements[i].value === "0" && !e_status.value)
                elements[i].checked = true;

        }
    }
    Router('update').then(()=>{
        let e_status = document.getElementById('students-education_status');
        if (!e_status.value)
        disable(e_status);
        e_status.onchange = e=>{
            disable(e_status);
        };
    }).catch(e=>{
        console.log(e);
    });
}