/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


social_events = function () {
    return {
        loadAdditionalFields: function (html) {

            var event_form = html.find('.events-form-sample').clone(true);

            event_form.css('display', '')
                    .removeAttr('class')
                    .addClass('events-form')
                    ;

            if (html.find('.additional_fields .events-form').length == 0) {
                event_form.prependTo('.additional_fields');
            }
        }
    };
}();