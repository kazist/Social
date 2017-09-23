<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Members\Code\Classes;

/**
 * Description of Member
 *
 * @author sbc
 */
use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class SyncRegistered {

    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }

    public function syncRegisteredUsers() {

        // Update Subscription
        $this->syncSocialMemberList();
    }

    public function syncSocialMemberList() {

        $users = $this->getUnRegisteredUser();

        if (!empty($users)) {
            foreach ($users as $user) {
                $this->syncRegisteredUser($user);
            }
        }
    }

    public function syncRegisteredUser($user) {

        $factory = new KazistFactory();

        $data_obj = new \stdClass();

        $data_obj->user_id = $user->id;
        $exist_obj = clone $data_obj;
        $data_obj->inviter_id = $user->inviter_id;
        $data_obj->country_id = $user->country_id;
        $data_obj->location_id = $user->location_id;
        $data_obj->phone = $user->phone;
        $data_obj->address = $user->address;
        $data_obj->avatar = $user->avatar;
        $data_obj->created_by = $user->id;

        $factory->saveRecord('#__social_members', $data_obj, array('user_id=:user_id'), $exist_obj);
    }

    public function getUnRegisteredUser() {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('uu.*');
        $query->from('#__users_users', 'uu');
        $query->leftJoin('uu', '#__social_members', 'sm', 'sm.user_id = uu.id');
        $query->where('sm.id IS NULL');
        $query->orderBy('sm.id');
         $query->setMaxResults(50);

        $records = $query->loadObjectList();

        return $records;
    }

}
