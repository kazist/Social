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
class Groups {

    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }

    public function getGroupList() {

        $factory = new KazistFactory();

        $id = $this->request->query->get('id');
        $user_id = $this->request->query->get('user_id');
        $limit = $this->request->get('limit', 10);
        $offset = $this->request->get('offset', 0);

        if ($user_id) {
            $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $user_id));
        } elseif ($id) {
            $member = $factory->getRecord('#__social_members', 'sm', array('sm.id=:id'), array('id' => $id));
            $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $member->user_id));
        } else {
            $document = $this->container->get('document');
            $user = $document->user;
        }

        $query = $factory->getQueryBuilder('#__social_groups_members', 'sgm');
        $query->select('sg.*');
        if ($user->id) {
            $query->where('sgm.user_id=:user_id');
            $query->setParameter('user_id', $user->id);
        } else {
            $query->where('1=-1');
        }

        $query->orderBy('sgm.id', 'DESC');

        $query->setFirstResult($offset);
        $query->setMaxResults($limit);

        $records = $query->loadObjectList();

        foreach ($records as $key => $record) {
            $records[$key]->is_in_group = $this->getIsInGroup($record->id);
        }
        
       

        return $records;
    }

    public function getIsInGroup($group_id) {

        global $sc;

        $factory = new KazistFactory();

        $document = $sc->get('document');
        $user = $document->user;

        $data = new \stdClass();
        $data->user_id = $user->id;
        $data->group_id = $group_id;

        $group = $factory->getRecord('#__social_groups_members', 'smf', array('user_id=:user_id', 'group_id=:group_id'), $data);
 
        return (is_object($group)) ? true : false;
    }

}
