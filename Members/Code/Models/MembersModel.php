<?php

/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Social\Members\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\User\Registration;
use Social\Members\Code\Classes\Member;
use Social\Members\Code\Classes\SyncRegistered;
use Social\Posts\Code\Classes\FetchPost;
use Kazist\Service\Database\Query;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class MembersModel extends BaseModel {

    public function saveMember($form) {

        $user_valid = true;

        $factory = new KazistFactory();
        $registration = new Registration();

        $user_obj = json_decode(json_encode($form));

        if (isset($form['first_name']) || isset($form['second_name'])) {
            $user_obj->name = ($form['name'] == '') ? $form['first_name'] . ' ' . $form['second_name'] : $form['name'];
        }

        if (isset($form['city'])) {
            $user_obj->town = $form['city'];
        }

        $member_obj = clone $user_obj;

        if (isset($form['email']) && $user_valid) {
            $user_valid = $registration->isValidEmail($user_obj->email);
        }

        if (isset($form['email']) && $user_valid) {
            $user_valid = !$registration->emailExist($user_obj->email, false);
        }

        if (isset($form['username']) && $user_valid) {
            $user_valid = !$registration->userNameExist($user_obj->username, false);
        }

        $user_id = $this->getMemberUserId($form);


        if ($user_valid && $user_id) {

            $member_obj->id = $form['id'];
            $user_obj->id = $user_id;

            $factory->saveRecord('#__users_users', $user_obj);
            $id = $factory->saveRecord('#__social_members', $member_obj);

            $this->saveImages($id);

            return $id;
        }
    }

    public function saveImages($member_id) {

        $factory = new KazistFactory();

        $cover_ids = $factory->uploadMedia('form.cover', 'social.members', $member_id);
        $avatar_ids = $factory->uploadMedia('form.avatar', 'social.members', $member_id);

        if ($cover_ids[0]) {

            $data = new \stdClass();
            $data->id = $member_id;
            $data->cover = $cover_ids[0];

            $factory->saveRecord('#__social_members', $data);
        }

        if ($avatar_ids[0]) {

            $data = new \stdClass();
            $data->id = $member_id;
            $data->avatar = $avatar_ids[0];

            $factory->saveRecord('#__social_members', $data);
        }
    }

    public function registerMember($form) {

        $user_obj = new \stdClass();

        $factory = new KazistFactory();
        $registration = new Registration();

        $default_inviter_id = $factory->getSetting('default_inviter_id');

        $form['phone'] = preg_replace('/\D/', '', $form['phone']);
        $date_str = $form['date_arr'] . '-' . $form['month_arr'] . '-' . $form['year_arr'];

        $user_obj->name = (trim($form['name']) == '') ? $form['first_name'] . ' ' . $form['second_name'] : $form['name'];
        $user_obj->username = trim(preg_replace("/[^A-Za-z0-9 ]/", '', $form['username']));
        $user_obj->email = trim($form['email']);
        $user_obj->dbo = date('Y-m-d', strtotime($date_str));
        $user_obj->password = trim($form['password']);
        $user_obj->password_again = trim($form['password_again']);
        $user_obj->inviter_id = ($form['inviter_id']) ? $form['inviter_id'] : $default_inviter_id;
        $user_obj->country_id = $form['country_id'];
        $user_obj->location_id = $form['location_id'];
        $user_obj->town = $form['city'];
        $user_obj->city = $form['city'];
        $user_obj->phone = ($form['phone_code'] == '') ? $form['phone'] : $form['phone_code'] . ltrim($form['phone'], '0');
        $user_obj->phone_code = $form['phone_code'];
        $user_obj->phone_iso = $form['phone_iso'];
        $user_obj->address = trim($form['address']);
        $user_obj->gender = trim($form['gender']);

        $user_valid = $registration->validateRegistration($user_obj);
        $member_valid = $this->validateForm($form, true);

        $user_id = $this->getMemberUserId($form);

        if ($user_id) {
            $user_obj->id = $user_id;
        }

        if ($user_valid && $member_valid) {

            $user_id = $registration->registerUser($user_obj);

            if ($user_id) {

                $this->removeFieldsNotRequired($form);

                $factory->saveRecordByEntity('#__social_members', $form, array('user_id=:user_id'), array('user_id' => $user_id));

                return $user_id;
            }
        }
    }

    public function validateForm($form) {

        $is_valid = true;

        $factory = new KazistFactory();
        $session = $factory->getSession();
        $agreements = $this->getAgreements();

        $captcha = $session->get('captcha-digit');

        $captcha_enable = $factory->getSetting('social_member_captcha_enable');

        if ($form['phone'] == '' || strlen((int) $form['phone']) >= 9) {
            $factory->enqueueMessage('Phone is a required Field or is Invalid enter 7xx xxx xxx.');
            $is_valid = false;
        }

        if ($form['date_arr'] == '' || $form['month_arr'] == '' || $form['year_arr'] == '') {
            $factory->enqueueMessage('Date of Birth is a required Field.');
            $is_valid = false;
        }

        if ($form['gender'] == '') {
            $factory->enqueueMessage('Gender is a required Field.');
            $is_valid = false;
        }
        if ($captcha_enable && $form['captcha'] !== $captcha) {
            $factory->enqueueMessage('Captcha entered is wrong.');
            $is_valid = false;
        }

        if ($form['city'] == '') {
            $factory->enqueueMessage('City is a required Field.');
            $is_valid = false;
        }

        if ($form['country_id'] == '') {
            $factory->enqueueMessage('Country is a required Field.');
            $is_valid = false;
        }

        if ($form['email_again'] <> '') {
            if ($form['email_again'] <> $form['email']) {
                $factory->enqueueMessage('Email Did not match.');
                $is_valid = false;
            }
        }

        if (!empty($agreements)) {
            foreach ($agreements as $agreement) {
                if (!in_array($agreement->id, $form['agreements'])) {
                    $factory->enqueueMessage('Agreement [' . $agreement->agreement . '"] was not selected.', 'error');
                    return false;
                }
            }
        }

        return $is_valid;
    }

//put your code here
    public function getAdditionalDetail($items) {

        $tmp_array = array();

        if (!empty($items)) {
            foreach ($items as $item) {
                $tmp_array[] = $this->getItemAdditionDetails($item);
            }
        }

        return $tmp_array;
    }

    public function getItemAdditionDetails($item) {

        $factory = new KazistFactory();

        $item_obj = (is_object($item)) ? $item : new \stdClass();

        $item_obj->user = $this->getUserById($item->user_id);
        $item_obj->inviter = $this->getUserById($item->inviter_id);
        $item_obj->is_friend = $this->getIsFriend($item->user_id);
        //  $item_obj->inviter->media = $factory->getMedia($item->avatar);

        return $item_obj;
    }

    public function getAgreements() {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('ssa.*');
        $query->from('#__social_setup_agreements', 'ssa');

        $records = $query->loadObjectList();

        return $records;
    }

    public function getMemberUserId($form) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('*');
        $query->from('#__social_members', 'sm');
        $query->where('sm.id=:id');
        $query->setParameter('id', $form['id']);

        $record = $query->loadObject();

        if (is_object($record)) {
            return $record->user_id;
        } else {
            return false;
        }
    }

    public function removeFieldsNotRequired(&$form) {
        unset($form['name']);
        unset($form['username']);
        unset($form['email']);
        unset($form['password']);
        unset($form['password_again']);
    }

    public function getUser() {

        $factory = new KazistFactory();

        $user_id = $this->request->query->get('user_id');
        $id = $this->request->query->get('id');

        if ($user_id) {
            $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $user_id));
        } elseif ($id) {
            $member = $factory->getRecord('#__social_members', 'sm', array('sm.id=:id'), array('id' => $id));
            $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $member->user_id));
        } else {
            $document = $this->container->get('document');
            $user = $document->user;
        }

        $name = explode(' ', $user->name);
        $phone = $user->phone;

        if ($user->phone_code == substr($user->phone, 0, strlen($user->phone_code))) {
            $phone = substr($phone, strlen($user->phone_code));
        }

        $user->phone = $phone;
        $user->first_name = $name[0];
        unset($name[0]);
        $user->second_name = implode(' ', $name);


        return $user;
    }

    public function getInviterInput() {

        $keyword = $this->request->get('keyword');

        $query = new Query();
        $query->select('uu.*');
        $query->from('#__users_users', 'uu');
        $query->where('(uu.username = :keyword OR uu.email = :keyword)');
        $query->setParameter('keyword', $keyword);

        $record = $query->loadObject();

        if ($record->username == '') {
            $record = new \stdClass();
        }
        
        return json_encode($record);
    }

    public function getInviter() {

        $factory = new KazistFactory();

        $session = $this->container->get('session');

        $user = $factory->getUser();


        $user_inviter = $session->get('user_inviter');
        $inviter_name = $this->request->get('invite');
        $default = $this->request->get('default');
        $default_inviter_id = $factory->getSetting('default_inviter_id');
        $inviter_id = $user->inviter_id;

        if ($inviter_name <> '') {
            $inviter = $this->getQueryedRecord('#__users_users', 'uu', array('uu.username=:username'), array('username' => $inviter_name));
        }

        if ($inviter_name <> '' && !is_object($inviter)) {
            $factory->enqueueMessage('There is no user with that username:-' . $inviter_name, 'error');
        }

        if ($default == 'true') {
            $inviter = $this->getQueryedRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $default_inviter_id));
        }

        if ($inviter_id <> '' && !is_object($inviter)) {
            $inviter = $this->getQueryedRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $inviter_id));
        }

        if ($user_inviter <> '') {
            $inviter = $this->getQueryedRecord('#__users_users', 'uu', array('uu.username=:username'), array('username' => $user_inviter));
        }

        if (is_object($inviter)) {
            $inviter->is_default = ($default_inviter_id == $inviter->id) ? true : false;
        }

        return $inviter;
    }

    public function getIsFriend($user_id) {

        $factory = new KazistFactory();

        $document = $this->container->get('document');
        $user = $document->user;

        $data = new \stdClass();
        $data->user_id = $user->id;
        $data->friend_id = $user_id;

        $friend = $factory->getRecord('#__social_members_friends', 'smf', array('user_id=:user_id', 'friend_id=:friend_id'), $data);

        return (is_object($friend)) ? true : false;
    }

    public function getUserById($user_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('uu.*');
        $query->from('#__users_users', 'uu');

        if ($user_id) {
            $query->where('id=:id');
            $query->setParameter('id', $user_id);
        } else {
            $query->where('1=-1');
        }


        $record = $query->loadObject();

        if (is_object($record)) {
            return $record;
        } else {
            return new \stdClass;
        }
    }

    public function getMember($user_id) {

        $factory = new KazistFactory();

        if (!$user_id) {
            $user = $this->getUser();
            $user_id = $user->id;
        }

        $query = $factory->getQueryBuilder('#__social_members', 'sm');

        if ($user_id) {
            $query->where('sm.user_id=:user_id');
            $query->setParameter('user_id', $user_id);
        } else {
            $query->where('1=-1');
        }

        $record = $query->loadObject();

        return $record;
    }

    public function syncRegisteredUsers() {

        $syncregistered = new SyncRegistered();

        return $syncregistered->syncRegisteredUsers();
    }

    public function getMemberDetail() {

        $member = new Member();

        return $member->getMemberDetail();
    }

    public function getPosts($show_all = false) {

        $fetchpost = new FetchPost();

        return $fetchpost->fetchPostList($show_all);
    }

}
