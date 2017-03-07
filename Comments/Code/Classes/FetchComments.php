<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Comments\Code\Classes;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Social\Likes\Code\Classes\FetchLikes;
use Kazist\Service\Database\Query;

/**
 * Description of FetchLikes
 *
 * @author sbc
 */
class FetchComments {

    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }

    public function fetchComments($post_id) {
        $data_obj = new \stdClass();

        $data_obj->list = $this->getCommentList($post_id);
        $data_obj->count = $this->getCommentCount($post_id);

        return $data_obj;
    }

    public function getCommentCount($post_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('COUNT(sc.id) AS total');
        $query->from('#__social_comments', 'sc');

        if ($post_id) {
            $query->where('sc.item_id=:item_id');
            $query->andWhere('sc.type=:type');
            $query->setParameter('item_id', $post_id);
            $query->setParameter('type', 'post');
        } else {
            $query->where('1=-1');
        }

        $count = $query->loadObject();

        return $count->total;
    }

    public function getCommentList($post_id, $parent_id = '') {
        $factory = new KazistFactory();

        $fetchlikes = new FetchLikes();

        $query = new Query();

        $query->select('sc.*, uu.name, uu.avatar');
        $query->from('#__social_comments', 'sc');
        $query->leftJoin('sc', '#__users_users', 'uu', 'uu.id = sc.created_by');

        if ($post_id) {
            $query->where('sc.item_id=:item_id');
            $query->andWhere('sc.type=:type');
            $query->setParameter('item_id', $post_id);
            $query->setParameter('type', 'post');
        } else {
            $query->where('1=-1');
        }

        if ($parent_id) {
            $query->andWhere('sc.parent_id=:parent_id');
            $query->setParameter('parent_id', $parent_id);
        }


        $comments_list = $query->loadObjectList();

        if (!empty($comments_list)) {
            foreach ($comments_list as $key => $comment) {
                $comments_list[$key]->likes = $fetchlikes->fetchLikes($comment->id, 'comment');
                $comments_list[$key]->children = $this->getCommentList($post_id, $comment->id);
            }
        }

        return $comments_list;
    }

}
