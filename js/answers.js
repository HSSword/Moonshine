function handleActAnswer (data, a){
    switch (a){
        default:
            if(data.message){
                notification(data.message, 3500, data.error ? _ERROR_COLOR : _INFO_COLOR);
            }
            break;
    }
    if(data.js){
        eval(data.js);
    }
}

function handlePatchAnswer (data, patch){
    switch (patch) {
        default:
            if(data.html && data.selector && data.operation){
                eval('$(data.selector).' + data.operation + '(data.html);');
            }
            break;
    }
    if(data.js){
        eval(data.js);
    }
    ParseAllJsActs();
    ParseAllJsPatches();
    ParseAllPlUploads();
}

function handlePlUploadAnswer (data, a){
    switch (a){
        case 'updateImg':
            if(data.message){
                notification(data.message, 3500, data.error ? _ERROR_COLOR : _INFO_COLOR);
            }
            if(data.newImageUrl){
                $('#logo img').attr('src', data.newImageUrl);
                $('#main .left img').attr('src', data.newImageUrl);
            }
            break;
        default:
            if(data.message){
                notification(data.message, 3500, data.error ? _ERROR_COLOR : _INFO_COLOR);
            }
            break;
    }
    if(data.js){
        eval(data.js);
    }
}