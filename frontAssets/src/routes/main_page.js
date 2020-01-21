import Router from "../Router";

export default ()=>{
    Router('month').then(()=>{
        $(".modal").on("shown.bs.modal", function () {
            let mb = $(".modal-backdrop");
            mb.not(':first').remove();
        });
        $('button[type="submit"]').click(function (e) {
            e.preventDefault();
            let form = $(e.target.parentElement.parentElement);

            if (form.find('.has-error').length)
            {
                return
            }
            let response_message = form.find('#response_message');
            let response_div = response_message.parent().parent();


            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: form.serialize(),
                success: function (response) {

                    let data = JSON.parse(response);
                    console.log(data);

                    switch (data.status) {
                        case 'error':{
                            let errors ='';
                            for (let item in data.errors){
                                errors+=data.errors[item]+'<br>';
                            }
                            response_message.html("Исправьте следующие ошибки <br>"
                                + errors
                            );
                            response_div.attr('class','alert alert-danger');
                            break;
                        }
                        case 'success':{
                            response_div.attr('class','alert alert-success');
                            response_message.html("Данные сохранены успешно");
                            break;
                        }
                    }
                    response_div.show();

                }
            });
        })
    }).catch(e=>{})
}