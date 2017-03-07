<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Addons\Members\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class MembersModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getLatestMembers() {

        $query = $this->getQuery();

        $query->setFirstResult(0);
        $query->setMaxResults(4);

        $records = $query->loadObjectList();

        foreach ($records as $key => $record) {
            if ($record->avatar_image == '' || file_exists(JPATH_ROOT . $record->avatar_image)) {
                $records[$key]->avatar_image = 'assets/images/users/users/avatar.png';
            }
        }


        return $records;
    }

    public function getQuery() {

        $query = new Query();
        $query->select('sm.*, amm.file as avatar_image, cmm.file as cover_image, uu.name as user_name, uu.name as author_name');
        $query->from('#__social_members', 'sm');
        $query->leftJoin('sm', '#__media_media', 'amm', 'amm.id = sm.avatar');
        $query->leftJoin('sm', '#__media_media', 'cmm', 'cmm.id = sm.cover');
        $query->leftJoin('sm', '#__users_users', 'cuu', 'cuu.id = sm.created_by');
        $query->leftJoin('sm', '#__users_users', 'uu', 'uu.id = sm.user_id');
        $query->leftJoin('sm', '#__setup_countries', 'swc', 'swc.id = sm.country_id');
        $query->leftJoin('sm', '#__setup_regions', 'swr', 'swr.id = sm.location_id');
        $query->orderBy('sm.id', ' DESC');

        return $query;
    }

}
