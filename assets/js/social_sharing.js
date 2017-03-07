/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


social_sharing = function () {
    return {
        submitPostSharing: function (this_element) {

            var msg = '';
            var item_id = this_element.attr('item_id');
            var post = this_element.closest('.post-sharing-wrapper').find('#post_sharing').val();
            var top_sharing = this_element.closest('.social-post').find('.user-wrapper').html();
            var content_sharing = this_element.closest('.post-sharing-wrapper').find('.post-sharing-content').html();
            var sharing = content_sharing; // var sharing = top_sharing + content_sharing;

            data_object = {sharing: sharing, post: post, item_id: item_id, action: 'sharing'};

            kazist.addSpinningIcon(this_element);
            msg = kazist.callAjaxByRoute('social.posts.save', data_object, false);
            kazist.removeSpinningIcon(this_element);

            location.reload();

        }
    };
}();