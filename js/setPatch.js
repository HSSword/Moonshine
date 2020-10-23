//This file should probably not be altered

$(document).ready(function () {
    ParseAllJsPatches();
});

function ParseAllJsPatches () {
    function parseJsPatch (){
        let self = $(this);

        self.unbind();

        let data = uriToObj(self.data('patch'))
        let trigger = self.data('js-trigger');

        bindJsTrigger(self, trigger, data, '', data.patch);
    }

    $('[data-patch]').each(parseJsPatch);
}

function executePatch (patch, data = {}){
    data.patch = patch;
    bindJsTrigger(null, 'instant', data, '', patch)
}