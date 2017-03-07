<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GroupsModels
 *
 * @author dedan
 */

namespace Social\Members\Friends\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Email\Email;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class FriendsModel extends BaseModel {

    public function getRecords($offset, $limit) {
        $factory = new KazistFactory();
        $records = parent::getRecords($offset, $limit);

        foreach ($records as $key => $record) {
            $user_id = $record->friend_id;
            $records[$key]->social = $factory->getRecord('#__social_members', 'sm', array('sm.user_id=:user_id'), array('user_id' => $user_id));
            $records[$key]->user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $user_id));
        }

        return $records;
    }

    public function save($form_data = '') {

        $email = new Email();
        $factory = new KazistFactory();

        $user_id = $this->request->get('user_id');

        $document = $this->container->get('document');
        $user = $document->user;
        $friend = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $user_id));

        $factory->enqueueMessage('Friend Request sent to Following:', 'success');

        $data = new \stdClass();
        $data->friend_id = $friend->id;
        $data->user_id = $user->id;

        $factory->saveRecord('#__social_members_friends', $data, array('friend_id=:friend_id', 'user_id=:user_id'), $data);

        $parameters = new \stdClass();
        $parameters->user = $user;
        $parameters->friend = $friend;

        $email->sendDefinedLayoutEmail('social.members.friends.user.request', $user->email, $parameters);
        $email->sendDefinedLayoutEmail('social.members.friends.friend.request', $friend->email, $parameters);
        $factory->enqueueMessage($friend->name, 'success');
    }

}
