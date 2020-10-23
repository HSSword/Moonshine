//This file should probably not be altered
var ckeditor = {};

//create ckeditor

function uriToObj (uriParameters){
    return JSON.parse('{"' + decodeURI(uriParameters.replace(/&/g, "\",\"").replace(/=/g,"\":\"")) + '"}')
}

function bindJsTrigger (element, trigger, payload, a = "", patch = "") {
    switch (trigger){
        case 'click':
            element.click(sendActPatchPost);
            break;
        case 'submit':
            element.submit(function (event) {
                let newPayload = new FormData(element[0]);
                let toDelete = [];
                for(let pair of newPayload.entries()){
                    if($(this).find('[name=' + pair[0] + ']').hasClass('js-form-ignore')){
                        toDelete.push(pair[0]);
                    }
                }
                toDelete.forEach(function (element) {
                    newPayload.delete(element);
                });
                let oldPayload = payload;
                for (const [key, value] of Object.entries(payload)) {
                    newPayload.append(key, value);
                }
                for (const [key, value] of Object.entries(ckeditor)){
                    newPayload.append(key, value.getData());
                }
                ckeditor = {};
                payload = newPayload;
                sendActPatchPost(event, true);
                payload = oldPayload;
            });
            break;
        case 'mouseover':
            element.mouseenter(sendActPatchPost);
            break;
        case 'value-change':
            element.change(function (event){
               payload.newValue = element.val();
               sendActPatchPost(event);
            });
            break;
        case 'keyup':
            element.keyup(function (event){
                payload.newValue = element.val();
                sendActPatchPost(event);
            });
            break;
        case 'instant':
            sendActPatchPost(null);
            break;
    }

    function sendActPatchPost (event, sendForm = false) {
        if(sendForm){
            $.ajax({
                url: _URL + 'dispatch.php',
                type: "POST",
                data: payload,
                processData: false,
                contentType: false,
                success: function(returnData) {
                    if(returnData.forceReload === true){
                        location.reload();
                        return;
                    }
                    a !== "" ? handleActAnswer(returnData, a) : handlePatchAnswer(returnData, patch);
                },
                dataType: 'json'
            });
        }else{
            $.post(
                _URL + 'dispatch.php',
                payload,
                function(returnData) {
                    if(returnData.forceReload === true){
                        location.reload();
                        return;
                    }
                    a !== "" ? handleActAnswer(returnData, a) : handlePatchAnswer(returnData, patch);
                },
                'json'
            )
        }
        if(event != null) event.preventDefault();
    }
}
