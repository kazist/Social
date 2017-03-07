<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Addons\Post\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class PostModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getLatestPosts() {

        $query = $this->getQuery();

        $query->setFirstResult(0);
        $query->setMaxResults(4);

        $records = $query->loadObjectList();
        
        foreach ($records as $key => $record) {
            if ($record->user_avatar == '' || !file_exists(JPATH_ROOT . $record->user_avatar)) {
                $records[$key]->user_avatar = 'assets/images/users/users/avatar.png';
            }
        }
        
        return $records;
    }

    public function getQuery() {

        $query = new Query();

        $query->select('sp.*, mm.file as user_avatar, mm.title as user_title, cuu.name as author_name, uu.name as user_name');
        $query->from('#__social_posts', 'sp');
        $query->leftJoin('sp', '#__users_users', 'cuu', 'cuu.id = sp.created_by');
        $query->leftJoin('sp', '#__users_users', 'uu', 'uu.id = sp.user_id');
        $query->leftJoin('sp', '#__media_media', 'mm', 'mm.id = uu.avatar');
        //$query->where('se.published=1');
        $query->orderBy('sp.id', 'DESC');

        return $query;
    }

}
