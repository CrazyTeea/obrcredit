import Router from '../Router';

export default () => {
    new Router(['create', 'update']).then(() => {

        let osn = $('input[type="radio"][name="Students[osnovanie]"]:checked');
        let ed = $('input[type="radio"][name="Students[education_status]"]');

        if (osn.val() !== '0') {
            ed.attr('disabled', true);
        }

        $('button[href="#clean"]').click(()=>{
            osn.prop('checked',false);
            ed.attr('disabled', false);
            $('input[type="radio"][name="Students[osnovanie]"][value="0"]').prop('checked',true);
            $('input[type="radio"][name="Students[education_status]"][value="1"]').prop('checked',true);
        });

        $('button[href="#clean2"]').click(()=>{
            ed.attr('disabled', false);
            $('input[type="radio"][name="Students[grace_period]"][value="0"]').prop('checked',true);
            $('input[type="radio"][name="Students[education_status]"][value="1"]').prop('checked',true);
        });

    }).catch();
};