import Router from "../Router";

export default ()=>{
    Router('index').then(()=>{

    }).catch(e=>{
        console.log("index не найден");
    });
    function disable(e_status){

        let elements = document.getElementsByTagName('input');
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].type === 'radio'&& elements[i].value !== "0" && elements[i].name ==='Students[osnovanie]'
                || (elements[i].type === 'file' && elements[i].name ==='Students[rasp_act0]')
                || (elements[i].type === 'file' && elements[i].name ==='Students[dogovor]')
                || (elements[i].type === 'file' && elements[i].name ==='Students[rasp_act_otch]')  ) {
                elements[i].disabled = !elements[i].disabled;
            }
            if (elements[i].type === 'radio' && elements[i].name ==='Students[osnovanie]' && elements[i].value === "0" && !e_status.value)
                elements[i].checked = true;

        }
    }
    Router('update').then(()=>{
        let form = document.getElementById('w0');
        let e_status = document.getElementById('students-education_status');
        console.log(!e_status.value);
        if (form.elements['Students[education_status]'].value==1)
            disable(e_status);
        e_status.onchange = e=>{
            disable(e_status);
        };
    }).catch(e=>{
        console.log(e);
    });
}