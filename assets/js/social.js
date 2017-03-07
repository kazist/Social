/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function () {
    social.init();
});

social = function () {
    return {
        init: function () {

            social.addEvents(jQuery('body'));
        },
        addEvents: function (html) {

            html = social.addCommentsEvents(html);
            html = social.addPostsEvents(html);
            html = social.addVideoPostingEvents(html);
            html = social.addAudioPostingEvents(html);

            html.find('.close-field').on('click', function () {
                social_general.closeFieldType(jQuery(this));
            });

            html.find("textarea").each(function () {
                social_general.autoResizeTextarea(jQuery(this));
            });

            return html;
        },
        addVideoPostingEvents: function (html) {

            html.find('.video_youtube_tab').on('click', function () {
                html.find('#video_type').val('youtube');
            });

            html.find('.video_embed_tab').on('click', function () {
                html.find('#video_type').val('embed');
            });

            html.find('.video_file_tab').on('click', function () {
                html.find('#video_type').val('file');
            });

            return html;
        },
        addAudioPostingEvents: function (html) {

            html.find('.audio_embed_tab').on('click', function () {
                html.find('#audio_type').val('embed');
            });

            html.find('.audio_file_tab').on('click', function () {
                html.find('#audio_type').val('file');
            });

            return html;
        }, addPostsEvents: function (html) {

            html.find('.post_activities').on('click', function () {
                jQuery(this).closest('.social-post').find('.post-comment-wrapper').show();
            });

            html.find('.post-like-link').on('click', function () {
                social_likes.submitPostLikes(jQuery(this), 'post');
            });

            html.find('#social-post-status').on('click', function () {
                social.addResetNHidden(html, '.posts-form');
            });

            html.find('#social-post-photos').on('click', function () {
                social.addResetNHidden(html, '.photos-form');
                html.find('#has_photos').val('1');
            });

            html.find('#social-post-videos').on('click', function () {
                social.addResetNHidden(html, '.videos-form');
                html.find('#has_videos').val('1');
            });

            html.find('#social-post-audios').on('click', function () {
                social.addResetNHidden(html, '.audios-form');
                html.find('#has_audios').val('1');
            });

            html.find('#social-post-events').on('click', function () {
                social.addResetNHidden(html, '.events-form');
                html.find('#has_events').val('1');
            });

            html.find('.additional_fields .photo').each(function () {
                social_photos.photoClickEvent(jQuery(this));
            });

            return html;

        }, addCommentsEvents: function (html) {

            html.find('.post_comment').on('click', function () {
                social_general.commentPost(jQuery(this));
            });

            html.find('.post-comment-link').on('click', function () {
                jQuery(this).closest('.social-post').find('.post-sharing-wrapper').hide();
                jQuery(this).closest('.social-post').find('.post-comment-wrapper').show();
            });

            html.find('.post-share-link').on('click', function () {

                jQuery(this).closest('.social-post').find('.post-comment-wrapper').hide();
                jQuery(this).closest('.social-post').find('.post-sharing-wrapper').show();

                var sharing_content = jQuery(this).closest('.social-post').find('.post-wrapper').html();

                jQuery(this).closest('.social-post').find('.post-sharing-content').html(sharing_content);
            });

            html.find('.like-to-comment').on('click', function () {
                social_likes.submitPostLikes(jQuery(this), 'comment');
            });

            html.find('.reply-to-comment').on('click', function () {
                social_comments.showCommentForm(jQuery(this));
            });

            html.find('.edit-to-comment').on('click', function () {
                social_comments.showEditCommentForm(jQuery(this));
            });

            html.find('.delete-to-comment').on('click', function () {
                social_comments.deleteComment(jQuery(this));
            });

            html.find('.post-comment-save').on('click', function () {
                social_comments.submitPostComments(jQuery(this));
            });

            html.find('.post-sharing-save').on('click', function () {
                social_sharing.submitPostSharing(jQuery(this));
            });

            return html;

        }, addResetNHidden: function (html, class_name) {

            html.find('.photos-form').hide();
            html.find('.videos-form').hide();
            html.find('.audios-form').hide();
            html.find('.events-form').hide();

            html.find('#has_photos').val('');
            html.find('#has_videos').val('');
            html.find('#has_audios').val('');
            html.find('#has_events').val('');

            if (class_name !== '') {
                html.find(class_name).show();
            }

        }

    };
}();









