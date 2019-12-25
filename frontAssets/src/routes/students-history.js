import Router from '../Router'

export default ()=>{
    Router(['get-by-number-and-year','index']).then(()=>{
        let add_click = $('.add_click');
        add_click.click(()=>{
            let modal = $('#update-modal');
            modal.modal('show')
                .find('#updateModalContent')
                .load($(this).attr('value'));
        });
    });
}