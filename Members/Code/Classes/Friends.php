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
class Friends {

    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }

    public function getFriendList() {

        $factory = new KazistFactory();

        $id = $this->request->query->get('id');
        $user_id = $this->request->query->get('user_id');
        $limit = $this->request->request->get('limit', 10);
        $offset = $this->request->request->get('offset', 0);

        if ($user_id) {
            $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $user_id));
        } elseif ($id) {
            $member = $factory->getRecord('#__social_members', 'sm', array('sm.id=:id'), array('id' => $id));
            $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $member->user_id));
        } else {
            $document = $this->container->get('document');
            $user = $document->user;
        }

        $query = $factory->getQueryBuilder('#__social_members', 'sm');
        $query->addSelect('uu.name as member_name, uu.email as member_email');
        $query->leftJoin('sm', '#__social_members_friends', 'smf', 'smf.friend_id = sm.user_id');

        if ($user->id) {
            $query->where('smf.user_id=:user_id');
            $query->setParameter('user_id', $user->id);
        } else {
            $query->where('1=-1');
        }

        $query->orderBy('smf.id ', 'DESC');

        $query->setFirstResult($offset);
        $query->setMaxResults($limit);

        $records = $query->loadObjectList();
        
        foreach ($records as $key => $record) {
              $records[$key]->is_friend = $this->getIsFriend($record->friend_id);
        }

        return $records;
    }

    public function getIsFriend($friend_id) {

        global $sc;
        
        $factory = new KazistFactory();

        $document = $sc->get('document');
        $user = $document->user;

        $data = new \stdClass();
        $data->user_id = $user->id;
        $data->friend_id = $friend_id;

        $friend = $factory->getRecord('#__social_members_friends', 'smf', array('user_id=:user_id', 'friend_id=:friend_id'), $data);

        return (is_object($friend)) ? true : false;
    }

}
