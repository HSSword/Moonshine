var alertHtml = $(
    ' <div class="notif-box">\n' +
    '    <span class="closebtn" onclick="closeNotification($(this).parent());">&times;</span>\n' +
    '</div>'
);

var modalHtml = $(
    '<div id="notification-modal" style="display: none;"></div>'
);

var loadingDiv = $(
    '<div id="loading-div">\n' +
    '   <div class="loader"></div>\n' +
    '</div>'
);

$(document).ready(function (){
    $('body').append(modalHtml);
});

function setLoadingOverlay (enabled) {
    if(enabled){
        if($('#loading-div').length == 0){
            $('body').append(loadingDiv.clone());
        }
    }else{
        if($('#loading-div').length > 0){
            $('#loading-div').remove();
        }
    }
}

function notification (message, autoClose = 5000, color = _INFO_COLOR){
    let alertT_obj = alertHtml.clone();
    alertT_obj.append(message);
    alertT_obj.css('background-color', color);

    $('#notification-modal').css('display', 'block');
    $('#notification-modal').append(alertT_obj);

    if(autoClose !== 0){
        setTimeout(function () {
            closeNotification(alertT_obj);
        }, autoClose);
    }
}

function closeNotification (element, animationTime = 300) {
    element.animate({
        paddingTop: 0,
        paddingBottom: 0,
        height: 0
    }, animationTime);
    setTimeout(function (){
        $('#notification-modal').css('display', 'none');
        element.remove();
    }, animationTime);
}

function closeAllModal () {
    $('.htmlfactory-modal').remove();
}

function closeModal (modal) {
    modal.remove();
}

function startLoading () {
    let loadingDOM = $(
        '<div id="loading-modal"><div id="loading"></div></div>'
    );
    $('body').after(loadingDOM.clone());
}

function stopLoading () {
    $('#loading-modal').remove();
}