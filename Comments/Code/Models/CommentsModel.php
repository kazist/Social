<?php

/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Social\Comments\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\ BaseModel;
use Kazist\KazistFactory;
use Social\Posts\Code\Models\PostsModel;
use Kazist\Service\Database\Query;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class CommentsModel extends BaseModel {

    public function saveComment() {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();


        $data_obj->id = $this->request->request->get('id');
        $data_obj->item_id = $this->request->request->get('item_id');
        $data_obj->type = $this->request->request->get('type');
        $data_obj->comment = $this->request->get('comment');
        $data_obj->parent_id = ($this->request->request->get('id') == $this->request->request->get('parent_id')) ? '' : $this->request->request->get('parent_id');

        $factory->saveRecord('#__social_comments', $data_obj);

        $html_obj = $this->getHtmlObject($data_obj->item_id, $data_obj->type);

        return $html_obj;
    }

    public function deleteComment() {

        $factory = new KazistFactory();

        $id = $this->request->request->get('id');
        $item_id = $this->request->request->get('item_id');
        $type = $this->request->request->get('type');

        $query = new Query();

        $query->select('*');
        $query->delete('#__social_comments');
        $query->where('id=:id');
        $query->setParameter('id', $id);

        $query->execute();

        $html_obj = $this->getHtmlObject($item_id, $type);

        return $html_obj;
    }

    private function getHtmlObject($post_id, $type) {

        $item_arr = array();
        $html_obj = new \stdClass();

        $factory = new KazistFactory();

        if ($type == 'post') {
            $postModel = new PostsModel();
            $item_arr['post'] = $postModel->getPostById($post_id);
            $item_arr['user'] = $factory->getUser();

            $html_obj->likeshare = $this->getPostLikeshareHtml($item_arr);
            $html_obj->comments = $this->getPostCommentsHtml($item_arr);
        }

        return $html_obj;
    }

    private function getPostLikeshareHtml($item_arr) {
        $paths = array();

        $factory = new KazistFactory();

        $paths[] = JPATH_ROOT . 'applications/Social/generalviews/post';
        $html = $factory->renderData('Social:generalviews:post:social.posts.likeshare.twig', $item_arr, $paths);

        return $html;
    }

    private function getPostCommentsHtml($item_arr) {
        $paths = array();

        $factory = new KazistFactory();

        $paths[] = JPATH_ROOT . 'applications/Social/generalviews/post';
        $html = $factory->renderData('Social:generalviews:post:social.posts.comments.twig', $item_arr, $paths);

        return $html;
    }

}
