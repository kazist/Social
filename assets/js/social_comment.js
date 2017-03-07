/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


social_comments = function () {
    return {
        submitPostComments: function (this_element) {
            var id = '';
            var msg = '';
            var type = 'post';
            var comment = this_element.closest('.post-comment-form').find('#post_comment').val();
            var item_id = this_element.closest('.post-comment-form').attr('item_id');
            var comment_id = this_element.closest('.post-comment-single').attr('comment_id');
            var edit = this_element.closest('.post-comment-form').attr('edit');

            if (typeof comment_id === 'undefined') {
                comment_id = 0;
            }

            if (edit) {
                id = comment_id;
                comment_id = 0;
            }

            data_object = {id: id, item_id: item_id, comment: comment, parent_id: comment_id, type: type};

            kazist.addSpinningIcon(this_element.closest('.post-right'));
            msg = kazist.callAjaxByRoute('social.comments.savecommentajax', data_object);
            kazist.removeSpinningIcon(this_element.closest('.post-right'));
            this_element.closest('.post-comment-form').find('textarea').val('');

            social_comments.updateHtml(msg, item_id);

        }, deleteComment: function (this_element) {

            var type = 'post';
            var item_id = this_element.attr('item_id');
            var comment_id = this_element.attr('comment_id');

            kazist.addSpinningIcon(this_element);

            var can_delete = confirm('Are you sure you want to delete this record.');

            if (can_delete) {
                data_object = {id: comment_id, item_id: item_id, type: type};
                msg = kazist.callAjaxByRoute('social.comments.deletecommentajax', data_object, true);
                social_comments.updateHtml(msg, item_id);
            }
            kazist.removeSpinningIcon(this_element);
        }, updateHtml: function (msg, item_id) {

            likeshare = social.addEvents(jQuery(msg.data.likeshare));
            comments = social.addEvents(jQuery(msg.data.comments));

            jQuery('#social-post_' + item_id + ' .like-share-wrapper').replaceWith(likeshare);
            jQuery('#social-post_' + item_id + ' .post-comment-list').replaceWith(comments);

        },
        showCommentForm: function (this_element, comment) {
            var item_id = this_element.attr('item_id');
            var html = jQuery(jQuery('.sample_comment_form').html());

            if (this_element.closest('.post-comment-single').find('.post-comment-form').length) {
                return false;
            }

            if (typeof comment !== 'undefined') {
                html.attr('edit', true);
                html.find('textarea').val(comment);
                // alert(comment);
            }

            html.attr('item_id', item_id);
            html = social.addEvents(html);


            this_element.closest('.post-comment-single').append(html);

        },
        showEditCommentForm: function (this_element) {
            var comment = this_element.closest('.post-comment-single').find('.post-comment-content input').val();

            console.log(comment);

            social_comments.showCommentForm(this_element, comment);

        }
    };
}();