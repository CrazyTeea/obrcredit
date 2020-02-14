import Router from "../Router";

export default ()=>{

    let grace_period = $('input[type="radio"][name="Students[grace_period]"]');
    let education_status = $('input[type="radio"][name="Students[education_status]"]');
    let osnovanie = $('input[type="radio"][name="Students[osnovanie]"]');
    let perevod = $('input[type="checkbox"][name="Students[perevod]"]');
    let ender = $('input[type="checkbox"][name="Students[isEnder]"]');

    function getElement(element,val=0){
        let e_s_0 = null;
        element.each((index,item)=>{if ($(item).val()==val) {e_s_0 = $(item);return false}});
       return e_s_0;
    }
    function getChecked(element){
        let e_s_0 = null;
        element.each((index,item)=>{if ($(item).prop('checked')) {e_s_0 = $(item);return false}});
        return e_s_0;
    }

    Router(['create','update']).then(()=>{
        console.log(getElement(grace_period));
        console.log(getElement(education_status));
        console.log(getElement(osnovanie));

        let kek = getChecked(osnovanie).val();
        let kek2 = getChecked(grace_period).val();

        if (kek != 0 || kek2!=0 || ender.is('checked')){
            let ed = getElement(education_status);
            ed.prop('checked',true);
            education_status.prop('disabled', true);
        }

        $(osnovanie).add(ender).change(e=>{
            let ed = getElement(education_status);
            ed.prop('checked',true);
            education_status.prop('disabled', true);
            perevod.prop('checked',false)
        });
        $(perevod).add(grace_period).change(e=>{
            let ed = getElement(education_status,1);
            ed.prop('checked',true);
            education_status.prop('disabled', true);
        });

    }).catch(e=>{console.log(e)});
    Router('create').then(()=>{

    }).catch(e=>{console.log("Путь create не найден")});
}