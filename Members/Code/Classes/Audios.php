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
class Audios {

    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }

    public function getAudioList() {

        $factory = new KazistFactory();

        $user_id = $this->request->request->get('user_id');
        $limit = $this->request->request->get('limit', 10);
        $offset = $this->request->request->get('offset', 0);

        $query = new Query();
        $query->select('spa.*, mm.file as media_file');
        $query->from('#__social_posts_audios', 'spa');
        $query->leftJoin('spa', '#__media_media', 'mm', 'spa.file = mm.id');

        if ($user_id) {
            $query->where('spa.created_by=:user_id');
            $query->setParameter('user_id', $user_id);
        } else {
            $query->where('1=-1');
        }

        $query->orderBy('spa.id ', 'DESC');

        $query->setFirstResult($offset);
        $query->setMaxResults($limit);

        $records = $query->loadObjectList();


        return $records;
    }

}
