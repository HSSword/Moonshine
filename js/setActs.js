//This file should probably not be altered

$(document).ready(function () {
    ParseAllJsActs();
});

function ParseAllJsActs () {
    function parseJsAct (){
        let self = $(this);

        self.unbind();

        let data = uriToObj(self.data('act'))
        let trigger = self.data('js-trigger');

        bindJsTrigger(self, trigger, data, data.a);
    }



    $('[data-act]').each(parseJsAct);
}

function executeAct (a, path, data = {}){
    data.a = a;
    data.path = path;
    bindJsTrigger(null, 'instant', data, a)
}