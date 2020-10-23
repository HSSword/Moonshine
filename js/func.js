/** The base URL of the site */
const _URL = window.location.origin + "/";
const _CK_UPLOAD_PATH = _URL + 'ckedit_upload.php';

const _INFO_COLOR = '#0066ff';
const _WARNING_COLOR = '#bf9401';
const _ERROR_COLOR = '#b42035';

/*Shamelessly stolen from https://www.w3resource.com/javascript-exercises/javascript-math-exercise-23.php
All credits to them*/
/** Returns a GUID string */
function create_UUID() {
    var dt = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (dt + Math.random() * 16) % 16 | 0;
        dt = Math.floor(dt / 16);
        return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    return uuid;
}