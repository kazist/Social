<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Members\Code\Classes;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

/**
 * Description of Friends
 *
 * @author sbc
 */
class Events {

    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }

    public function getEventList() {

        $factory = new KazistFactory();



        $user_id = $this->request->request->get('user_id');
        $limit = $this->request->request->get('limit', 10);
        $offset = $this->request->request->get('offset', 0);

        $query = new Query();
        $query->select('se.*');
        $query->from('#__social_events', 'se');

        if ($user_id) {
            $query->where('se.created_by=:user_id');
            $query->setParameter('user_id', (int) $user_id);
        } else {
            $query->where('1=-1');
        }


        $records = $query->loadObjectList();

        $query->orderBy('se.id ', 'DESC');

        $query->setFirstResult($offset);
        $query->setMaxResults($limit);

        $record = $query->loadObjectList();


        return $records;
    }

}
