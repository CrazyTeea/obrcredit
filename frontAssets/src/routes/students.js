import Router from "../Router";

export default ()=>{
    let ed_s = $("#students-education_status");
    let e_status = document.getElementById('students-education_status');
    function disable(e_status,check){

        let elements = document.getElementsByTagName('input');
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].type === 'radio'&& elements[i].value !== "0" && elements[i].name ==='Students[osnovanie]'
                || (elements[i].type === 'file' && elements[i].name ==='Students[rasp_act0]')
                || (elements[i].type === 'file' && elements[i].name ==='Students[dogovor]')
                || (elements[i].type === 'file' && elements[i].name ==='Students[rasp_act_otch]')  ) {
                elements[i].disabled = check;
            }
            if (elements[i].type === 'radio' && elements[i].name ==='Students[osnovanie]' && elements[i].value === "0" && !e_status.value)
                elements[i].checked = true;

        }
    }
    Router(['create','update']).then(()=>{

        $('div').on('change','.status_callback',function (e) {
            let dis  = true;
            if (e.target.type == 'checkbox'){
                dis = !!e.target.checked;
            }
            if (dis){
                ed_s.find('[value=0]').checked = true;
                ed_s.find('input').each(function () {
                    this.disabled = true;
                })
            }
            else
                ed_s.find('input').each(function () {
                    this.disabled = false;
                })


        });

        let form = document.getElementById('w0');
        let input_osn = $(":input[name='Students[education_status]']");
        $("button[href='#clean']").click(()=>{
            input_osn.prop('disabled',false);
            input_osn.prop('checked',true);
            $(":input[name='Students[osnovanie]'][value='0']").prop('checked',true);
        });
        $("button[href='#clean2']").click(()=>{
            input_osn.prop('disabled',false);
            input_osn.prop('checked',true);
            $(":input[name='Students[grace_period]'][value='0']").prop('checked',true);
        });
        ed_s.change(e=>{
            if(e.target.value == 0)
                disable(e_status,false);
            else
                disable(e_status,true);
        });

    }).catch(e=>{console.log(e)});
    Router('create').then(()=>{

        if(!ed_s.find(':checked').val())
            disable(e_status,true);
        else disable(e_status,false);
    })
}