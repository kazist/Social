<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Addons\Groups\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class GroupsModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getLatestGroups() {

        $query = $this->getQuery();

        $query->setFirstResult(0);
        $query->setMaxResults(4);

        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $records[$key]->total_members = $this->getTotalGroupMembers($record->id);
                $records[$key]->is_member = $this->getIsMembers($record->id);
            }
        }

        return $records;
    }

    public function getIsMembers($group_id) {

        $factory = new KazistFactory;
        $user = $factory->getUser();

        $query = new Query();
        $query->select('*');
        $query->from('#__social_groups_members', 'sgm');
        $query->where('sgm.group_id=:group_id');
        $query->andWhere('sgm.user_id=:user_id');
        $query->setParameter('group_id', (int) $group_id);
        $query->setParameter('user_id', (int) $user->id);
        $query->orderBy('sgm.id', 'DESC');

        $record = $query->loadObject();

        if (is_object($record)) {
            return true;
        } else {
            return false;
        }
    }

    public function getTotalGroupMembers($group_id) {

        $query = new Query();
        $query->select('COUNT(*) as total');
        $query->from('#__social_groups_members', 'sgm');
        $query->where('sgm.group_id=:group_id');
        $query->setParameter('group_id', $group_id);

        $record = $query->loadObject();

        return $record->total;
    }

    public function getQuery() {


        $query = new Query();
        $query->select('sg.*, uu.name as author_name, mm.file as group_file, mm.title as group_title');
        $query->from('#__social_groups', 'sg');
        $query->leftJoin('sg', '#__users_users', 'uu', 'uu.id = sg.created_by');
        $query->leftJoin('sg', '#__media_media', 'mm', 'mm.id = sg.image');
        $query->orderBy('sg.id ', 'DESC');

        return $query;
    }

}
