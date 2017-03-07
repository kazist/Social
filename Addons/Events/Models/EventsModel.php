<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Addons\Events\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class EventsModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getLatestEvents() {

        $query = $this->getQuery();

        $query->setFirstResult(0);
        $query->setMaxResults(4);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getQuery() {

        $query = new Query();
        $query->select('se.*, uu.name as author_name');
        $query->from('#__social_events', 'se');
        $query->leftJoin('se', '#__users_users', 'uu', 'uu.id = se.created_by');
        $query->orderBy('se.id', 'DESC');

        return $query;
    }

}
