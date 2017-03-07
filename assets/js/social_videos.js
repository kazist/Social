/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

social_videos = function () {
    return {
        loadAdditionalFields: function (html) {

            var video_form = html.find('.vidoes-form-sample').clone(true);

            video_form.css('display', '')
                    .removeAttr('class')
                    .addClass('vidoes-form')
                    ;

            if (html.find('.additional_fields .videos-form').length == 0) {
                video_form.prependTo('.additional_fields');
            }
        }
    };
}();