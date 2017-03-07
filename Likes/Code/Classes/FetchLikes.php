<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Likes\Code\Classes;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

/**
 * Description of FetchLikes
 *
 * @author sbc
 */
class FetchLikes {
    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }
    public function fetchLikes($post_id, $type) {
        $data_obj = new \stdClass();

        if ($type == 'post') {
            $data_obj->list = $this->getLikeList($post_id, $type);
        }
        $data_obj->has_liked = $this->hasLiked($post_id, $type);
        $data_obj->count = $this->getLikeCount($post_id, $type);

        return $data_obj;
    }

    public function getLikeList($post_id, $type) {
        $factory = new KazistFactory();

        $user = $factory->getUser();

        $query = new Query();
        $query->select('sl.*, uu.name');
        $query->from('#__social_likes', 'sl');
        $query->leftJoin('sl', '#__users_users', 'uu', 'uu.id = sl.created_by');

        if ($post_id) {
            $query->where('sl.item_id=:item_id');
            $query->andWhere('sl.type=:type');
            $query->setParameter('item_id', $post_id);
            $query->setParameter('type', $type);
        } else {
            $query->where('1=-1');
        }
        $query->andWhere('sl.created_by<>' .(int) $user->id);
        // $query->where('sl.created_by>0');
        $query->setFirstResult(0);
        $query->setMaxResults(2);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getLikeCount($post_id, $type) {
        $factory = new KazistFactory();

        $user = $factory->getUser();

        $query = new Query();
        $query->select('COUNT(sl.id) AS total');
        $query->from('#__social_likes', 'sl');

        if ($post_id) {
            $query->where('sl.item_id=:item_id');
            $query->andWhere('sl.type=:type');
            $query->setParameter('item_id', $post_id);
            $query->setParameter('type', $type);
        } else {
            $query->where('1=-1');
        }

        $count = $query->loadObject();

        return $count->total;
    }

    public function hasLiked($post_id, $type) {
        $factory = new KazistFactory();

        $user = $factory->getUser();

        $query = new Query();
        $query->select('sl.*, uu.name');
        $query->from('#__social_likes', 'sl');
        $query->leftJoin('sl', '#__users_users', 'uu', 'uu.id = sl.created_by');

        if ($post_id) {
            $query->where('sl.item_id=:item_id');
            $query->andWhere('sl.type=:type');
            $query->setParameter('item_id', $post_id);
            $query->setParameter('type', $type);
        } else {
            $query->where('1=-1');
        }
        $query->where('sl.created_by=' . (int) $user->id);

        $record = $query->loadObject();

        if (is_object($record)) {
            return true;
        } else {
            return false;
        }
    }

}
