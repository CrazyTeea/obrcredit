//import Router from "../Router";


export default ()=>{

        console.log('import');
        let form = $('#import-form');

        $('#csv_input').on('fileuploaderror', function(event, data, msg) {
                console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
        }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
                if (!Object.keys(preview).length){
                        console.log('SUCCESS!!!!');
                }
        });
        /*form.on('beforeSubmit',function (e) {

            return false;
        });*/

}