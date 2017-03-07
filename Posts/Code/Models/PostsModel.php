<?php

/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Social\Posts\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Social\Posts\Code\Classes\SavePost;
use Social\Posts\Code\Classes\FetchPost;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class PostsModel extends BaseModel {

    public function savePost($form) {
        $post = new SavePost();
        $post->savePostData($form);
    }

    public function getPostById($post_id) {
        $fetchpost = new FetchPost();
        return $fetchpost->fetchPostById($post_id);
    }

    public function getPostList($post_id) {
        $fetchpost = new FetchPost();
        return $fetchpost->fetchPostList();
    }

}
