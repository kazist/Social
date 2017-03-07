/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


social_likes = function () {
    return {
        submitPostLikes: function (this_element, type) {
            var msg = '';
            var item_id = this_element.attr('item_id');
            var post_id = this_element.attr('post_id');

            if (type === 'comment') {
                data_object = {item_id: item_id, type: type, post_id: post_id};
            } else {
                data_object = {item_id: item_id, type: type};
            }

            kazist.addSpinningIcon(this_element);
            msg = kazist.callAjaxByRoute('social.likes.savelikeajax', data_object, false);

            if (type === 'comment') {
                comment = social.addEvents(jQuery(msg.data));
                jQuery('#social-post_' + post_id + ' .post-comment-list').replaceWith(comment);
            } else {
                likeshare = social.addEvents(jQuery(msg.data));
                jQuery('#social-post_' + item_id + ' .like-share-wrapper').replaceWith(likeshare);
            }

            kazist.removeSpinningIcon(this_element);

        }
    };
}();