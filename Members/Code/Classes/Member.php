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
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class Member {

    public $container;
    public $request;

    public function __construct() {
        global $sc;
        $this->container = $sc;
        $this->request = $sc->get('request');
    }

    public function getMemberDetail() {

        $member = new \stdClass;
        $factory = new KazistFactory();

        $user = $factory->getUser();

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

        $member->personal = $this->getMemberPersonalInfo($user->id);
        $member->image = $this->getMemberImage($user->id);
        $member->friends = $this->getMemberFriends($user->id);
        $member->groups = $this->getMemberGroups($user->id);
        $member->photos = $this->getMemberPhotos($user->id);
        $member->videos = $this->getMemberVideos($user->id);
        $member->audios = $this->getMemberAudios($user->id);
        $member->friends_count = $this->getMemberFriendsCounts($user->id);
        $member->invites_count = $this->getMemberInvitesCounts($user->id);
        $member->groups_count = $this->getGroupsCounts($user->id);
        $member->images_count = $this->getPostsImagesCounts($user->id);
        $member->videos_count = $this->getPostsVideosCounts($user->id);
        $member->audios_count = $this->getPostsAudiosCounts($user->id);
        $member->events_count = $this->getEventsCounts($user->id);

        return $member;
    }

    public function getMemberPersonalInfo($user_id) {

        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('#__social_members', 'sm');
        $query->addSelect('uu.name AS member_name, uu.username AS member_username, uu.name AS member_email');

        if ($user_id) {
            $query->where('sm.user_id=:user_id');
            $query->setParameter('user_id', $user_id);
        } else {
            $query->where('1=-1');
        }

        $record = $query->loadObject();

        if ($record->cover_file == '' || !file_exists(JPATH_ROOT . $record->cover_file)) {
            $record->cover_file = 'assets/images/users/users/cover.png';
        }

        if ($record->avatar_file == '' || !file_exists(JPATH_ROOT . $record->avatar_file)) {
            $record->avatar_file = 'assets/images/users/users/avatar.png';
        }

        return $record;
    }

    public function getMemberImage($user_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('mm.file as avatar, mm.title as avatar_name, mm2.file as cover, mm2.title as cover_name ');
        $query->from('#__social_members', 'sm');
        $query->leftJoin('sm', '#__media_media', 'mm', 'mm.id = sm.avatar');
        $query->leftJoin('sm', '#__media_media', 'mm2', 'mm2.id = sm.cover');

        if ($user_id) {
            $query->where('sm.created_by=:user_id');
            $query->setParameter('user_id', $user_id);
        } else {
            $query->where('1=-1');
        }




        $record = $query->loadObject();


        return $record;
    }

    public function getMemberFriends($user_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('sm.*, uu.name as member_name, uu.email as member_email, mm.file as avatar, mm.title as avatar_name, mm2.file as cover, mm2.title as cover_name ');
        $query->from('#__social_members', 'sm');
        $query->leftJoin('sm', '#__users_users', 'uu', 'uu.id = sm.user_id');
        $query->leftJoin('sm', '#__media_media', 'mm', 'mm.id = sm.avatar');
        $query->leftJoin('sm', '#__media_media', 'mm2', 'mm2.id = sm.cover');
        $query->leftJoin('sm', '#__social_members_friends', 'smf', 'smf.friend_id = sm.user_id');

        if ($user_id) {
            $query->where('smf.user_id=:user_id');
            $query->setParameter('user_id', $user_id);
        } else {
            $query->where('1=-1');
        }

        $query->setFirstResult(0);
        $query->setMaxResults(4);

        $record = $query->loadObjectList();

        return $record;
    }

    public function getMemberGroups($user_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('sg.*, mm.file AS image_file, mm.title as image_title');
        $query->from('#__social_groups_members', 'sgm');
        $query->leftJoin('sgm', '#__social_groups', 'sg', 'sg.id = sgm.group_id');
        $query->leftJoin('sgm', '#__media_media', 'mm', 'mm.id = sg.image');

        if ($user_id) {
            $query->where('sgm.user_id=:user_id');
            $query->setParameter('user_id', $user_id);
        } else {
            $query->where('1=-1');
        }

        $query->setFirstResult(0);
        $query->setMaxResults(4);

        $record = $query->loadObjectList();


        return $record;
    }

    public function getMemberPhotos($user_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('mm.file, mm.title');
        $query->from('#__social_posts_images', 'spp');
        $query->leftJoin('spp', '#__media_media', 'mm', 'mm.id = spp.media_id');

        if ($user_id) {
            $query->where('spp.created_by=:user_id');
            $query->setParameter('user_id', $user_id);
        } else {
            $query->where('1=-1');
        }

        $query->setFirstResult(0);
        $query->setMaxResults(4);

        $record = $query->loadObjectList();


        return $record;
    }

    public function getMemberVideos($user_id) {
        
    }

    public function getMemberAudios($user_id) {
        
    }

    public function getMemberFriendsCounts($user_id) {
        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('social_members_friends', 'smf');
        $query->select('COUNT(*) as total');
        $query->where('smf.user_id = ' . (int) $user_id);

        $record = $query->loadObject();

        return $record->total;
    }

    public function getMemberInvitesCounts($user_id) {
        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('social_members_invites', 'smi');
        $query->select('COUNT(*) as total');
        $query->where('smi.user_id = ' . (int) $user_id);

        $record = $query->loadObject();

        return $record->total;
    }

    public function getEventsCounts($user_id) {
        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('social_events', 'se');
        $query->select('COUNT(*) as total');
        $query->where('se.created_by = ' . (int) $user_id);

        $record = $query->loadObject();

        return $record->total;
    }

    public function getGroupsCounts($user_id) {
        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('social_groups_members', 'sgm');
        $query->select('COUNT(*) as total');
        $query->where('sgm.user_id = ' . (int) $user_id);

        $record = $query->loadObject();

        return $record->total;
    }

    public function getPostsImagesCounts($user_id) {
        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('social_posts_images', 'spi');
        $query->select('COUNT(*) as total');
        $query->where('sp.user_id = ' . (int) $user_id);

        $record = $query->loadObject();

        return $record->total;
    }

    public function getPostsVideosCounts($user_id) {
        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('social_posts_videos', 'spv');
        $query->select('COUNT(*) as total');
        $query->where('sp.user_id = ' . (int) $user_id);

        $record = $query->loadObject();

        return $record->total;
    }

    public function getPostsAudiosCounts($user_id) {
        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('social_posts_audios', 'spa');
        $query->select('COUNT(*) as total');
        $query->where('sp.user_id = ' . (int) $user_id);

        $record = $query->loadObject();

        return $record->total;
    }

}
