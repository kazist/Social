/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


social_general = function () {
    return {
        closeFieldType: function (this_element) {
            this_element.closest('div').hide();
        },
        commentPost: function (this_element) {
            jQuery('.social-post .post-comment-addition').hide();
            this_element.closest('.social-post').find('.post-comment-addition').show();
        },
        autoResizeTextarea: function (this_element) {
            var offset = this.offsetHeight - this.clientHeight;

            var resizeTextarea = function (el) {
                jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
            };

            this_element.on('keyup input', function () {
                resizeTextarea(this);
            });
        }
    };
}();