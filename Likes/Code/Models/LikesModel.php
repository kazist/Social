<?php

/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Social\Likes\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Social\Posts\Code\Models\PostsModel;
use Kazist\Service\Database\Query;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class LikesModel extends BaseModel {

    public function saveLikes() {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();


        $data_obj->item_id = $this->request->request->get('item_id');
        $data_obj->type = $this->request->request->get('type');

        $like_id = $this->getLikeId($data_obj);

        if (!$like_id) {
            $factory->saveRecord('#__social_likes', $data_obj);
        } else {
            $this->deleteLike($data_obj->item_id, $data_obj->type);
        }

        if ($data_obj->type == 'post') {
            return $this->getPostLikeshareHtml($data_obj->item_id);
        } elseif ($data_obj->type == 'comment') {
            return $this->getPostCommentsHtml($this->request->request->get('post_id'));
        }
    }

    private function getLikeId($data_obj) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('sl.*');
        $query->from('#__social_likes', 'sl');
        $query->where('sl.item_id=:item_id');
        $query->andWhere('sl.type=:type');
        $query->setParameter('item_id', $data_obj->item_id);
        $query->setParameter('type', $data_obj->type);

        $record = $query->loadObject();

        if (is_object($record)) {
            return $record->id;
        } else {
            return false;
        }
    }

    private function deleteLike($item_id, $type) {

        $factory = new KazistFactory();


        $query = new Query();

        $query->select('*');
        $query->delete('#__social_likes');
        $query->where('item_id=:item_id');
        $query->andWhere('type=:type');
        $query->setParameter('type', $type);
        $query->setParameter('item_id', $item_id);

        $query->execute();
    }

    private function getPostLikeshareHtml($post_id) {
        $item_arr = array();
        $paths = array();

        $factory = new KazistFactory();

        $postModel = new PostsModel();
        $item_arr['post'] = $postModel->getPostById($post_id);
        $item_arr['user'] = $factory->getUser();

        $paths[] = JPATH_ROOT . 'applications/Social/generalviews/post';
        $html = $factory->renderData('Social:generalviews:post:social.posts.likeshare.twig', $item_arr, $paths);

        return $html;
    }

    private function getPostCommentsHtml($post_id) {
        $item_arr = array();
        $paths = array();

        $factory = new KazistFactory();



        $postModel = new PostsModel();
        $item_arr['post'] = $postModel->getPostById($post_id);
        $item_arr['user'] = $factory->getUser();

        $paths[] = JPATH_ROOT . 'applications/Social/generalviews/post';
        $html = $factory->renderData('Social:generalviews:post:social.posts.comments.twig', $item_arr, $paths);

        return $html;
    }

}
