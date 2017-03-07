/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


social_posts = function () {
    return {
        getPostById: function (post_id) {

            data_object = {post_id: post_id};

            var post_data = kazist.callAjaxByRoute('social.posts.postajax', data_object, true);

            jQuery('#social-post_' + post_id).html(post_data);

        }
    };
}();