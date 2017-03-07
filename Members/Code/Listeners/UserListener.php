<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

namespace Social\Members\Code\Listeners;

defined('KAZIST') or exit('Not Kazist Framework');

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Kazist\Event\UserEvent;
use Kazist\KazistFactory;
use Kazist\Event\CRUDEvent;

/**
 * Some of Events to be fired
 * - user.before.registration
 * - user.after.registration
 * - user.before.login
 * - user.after.login
 * - user.before.save
 * - user.after.save
 * - user.before.delete
 * - user.after.delete
 * 
 */
class UserListener implements EventSubscriberInterface {

    public $container = '';

    public function onUserBeforeRegistration(UserEvent $event) {
        global $sc;

        $this->container = $sc;
    }

    public function onUserAfterRegistration(UserEvent $event) {
        global $sc;

        $this->container = $sc;

        $factory = new KazistFactory();
        $user = $event->getUser();


        $data_obj = new \stdClass();
        $data_obj->user_id = $user->id;
        $data_obj->inviter_id = $user->inviter_id;
        $exist_obj = clone $data_obj;
        $data_obj->country_id = $user->country_id;
        $data_obj->location_id = $user->location_id;
        $data_obj->gender = $user->gender;
        $data_obj->phone = $user->phone;
        $data_obj->address = $user->address;
        $data_obj->points = $user->points;

        $id = $factory->saveRecordByEntity('#__social_members', $data_obj, array('user_id=:user_id', 'inviter_id=:inviter_id'), $exist_obj);
    }

    public function onUserBeforeLogin(UserEvent $event) {
        global $sc;

        $this->container = $sc;
    }

    public function onUserAferLogin(UserEvent $event) {
        global $sc;

        $this->container = $sc;
    }

    public function onUserBeforeSave(CRUDEvent $event) {
        global $sc;

        $this->container = $sc;
    }

    public function onUserAferSave(CRUDEvent $event) {
        global $sc;

        $this->container = $sc;
    }

    public function onUserBeforeDelete(CRUDEvent $event) {
        global $sc;

        $this->container = $sc;
    }

    public function onUserAferDelete(CRUDEvent $event) {
        global $sc;

        $this->container = $sc;

        $factory = new KazistFactory();
        $user = $event->getRecord();

        $data_obj = new \stdClass();
        $data_obj->user_id = $user->getId();

        $factory->deleteRecords('#__social_members', array('user_id=:user_id'), $data_obj);
    }

    public static function getSubscribedEvents() {
        return array(
            'user.before.registration' => 'onUserBeforeRegistration',
            'user.after.registration' => 'onUserAfterRegistration',
            'user.before.login' => 'onUserBeforeLogin',
            'user.after.login' => 'onUserAferLogin',
            'users.users.before.save' => 'onUserBeforeSave',
            'users.users.after.save' => 'onUserAferSave',
            'users.users.before.delete' => 'onUserBeforeDelete',
            'users.users.after.delete' => 'onUserAferDelete',
        );
    }

}
