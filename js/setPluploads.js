//This file should probably not be altered

var uploaders = [];

$(document).ready(function () {
    ParseAllPlUploads();
});

function ParseAllPlUploads () {
    function parsePlUpload (){
        let self = $(this);
        if(! self.attr('id')) {
            self.attr('id', create_UUID());
        }



        let data = uriToObj(self.data('plupload'))
        let a = data.a;
        let path = data.path;
        let id = self.attr('id');

        let multiselect = false;
        let maxsize = 0;
        let mimetypes = [];

        if(data.multi){
            multiselect = (data.multi == 'true' || data.multi == '1');
        }
        if(data.maxsize){
            maxsize = data.maxsize;
        }
        if(data.mimetypes){
            let types = data.mimetypes.split(',');
            types.forEach (function (type){
                let parts = type.split(':');
                mimetypes.push({title: parts[1], extensions: parts[0]});
            });
        }

        if(uploaders[id]){
            uploaders[id].destroy();
            uploaders[id] = null;
        }

        uploaders[id] = new plupload.Uploader({
            browse_button: id,
            url: _URL + 'dispatch.php',
            multipart_params : data,
            filters: {
                mime_types: mimetypes,
                max_file_size: maxsize
            },
            multi_selection: multiselect
        });
        uploaders[id].init();
        uploaders[id].bind('FilesAdded', function (up, files) {
            setLoadingOverlay(true);
            uploaders[id].start();
        });
        uploaders[id].bind('FileUploaded', function (up, files, result){
            setLoadingOverlay(false);
            handlePlUploadAnswer(JSON.parse(result.response), a);
        });
        uploaders[id].bind('Error', function (up, error){
            handlePlUploadAnswer({message: error.message, error: true}, a);
        });
    }



    $('[data-plupload]').each(parsePlUpload);
}