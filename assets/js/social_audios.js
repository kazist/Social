/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


social_audios = function () {
    return {
        loadAdditionalFields: function (html) {

            var audio_form = html.find('.audios-form-sample').clone(true);

            audio_form.css('display', '')
                    .removeAttr('class')
                    .addClass('audios-form')
                    ;

            if (html.find('.additional_fields .audios-form').length == 0) {
                audio_form.prependTo('.additional_fields');
            }
        }
    };
}();