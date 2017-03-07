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
class Photos {

    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }

    public function getPhotoList() {

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

        $query = new Query();
        $query->select('mm.file, mm.title');
        $query->from('#__social_posts_images', 'spp');
        $query->leftJoin('spp', '#__media_media', 'mm', 'mm.id = spp.media_id');

        if ($user->id) {
            $query->where('spp.created_by=:user_id');
            $query->setParameter('user_id', $user->id);
        } else {
            $query->where('1=-1');
        }

        $query->orderBy('spp.id ', 'DESC');

        $query->setFirstResult($offset);
        $query->setMaxResults($limit);

        $record = $query->loadObjectList();

        return $record;
    }

}
