<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Posts\Code\Classes;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Social\Likes\Code\Classes\FetchLikes;
use Social\Comments\Code\Classes\FetchComments;
use Kazist\Service\Database\Query;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class FetchPost {

    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }

    public function fetchPostList($show_all = false) {

        $posts = $this->fetchPosts($show_all);

        foreach ($posts as $key => $post) {
            $posts[$key] = $this->addAdditionalInfo($post);
        }

        // print_r($posts);
        //exit;

        return $posts;
    }

    public function fetchPostById($post_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('*');
        $query->from('#__social_posts', 'sp');

        if ($post_id) {
            $query->where('sp.id=:post_id');
            $query->setParameter('post_id', $post_id);
        } else {
            $query->where('1=-1');
        }
        $query->orderBy('id ', 'DESC');

        $record = $query->loadObject();

        return $this->addAdditionalInfo($record);
    }

    public function addAdditionalInfo($post) {
        $fetchlikes = new FetchLikes();
        $fetchcomments = new FetchComments();

        $post->user = $this->fetchUser($post->created_by);
        $post->photos = $this->fetchPhotos($post->id);
        $post->videos = $this->fetchVideos($post->id);
        $post->audios = $this->fetchAudios($post->id);
        $post->events = $this->fetchEvents($post->id);
        $post->likes = $fetchlikes->fetchLikes($post->id, 'post');
        $post->share = $this->fetchShare($post->id);
        $post->comments = $fetchcomments->fetchComments($post->id);

        return $post;
    }

    private function fetchUser($user_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('uu.*,mm.file AS media_file');
        $query->from('#__users_users', 'uu');
        $query->leftJoin('uu', '#__media_media', 'mm', 'uu.avatar = mm.id');

        if ($user_id) {
            $query->where('uu.id=:user_id');
            $query->setParameter('user_id', $user_id);
        } else {
            $query->where('1=-1');
        }
        $query->orderBy('id ', 'DESC');

        $record = $query->loadObject();

        return $record;
    }

    private function fetchPosts($show_all = false) {

        $factory = new KazistFactory();

        $user_id = $this->request->get('user_id');

        $query = new Query();
        $query->select('*');
        $query->from('#__social_posts', 'sp');

        if ($show_all) {
            //$query->where('1=-1');
        } elseif ($user_id) {
            $query->where('sp.created_by=:user_id');
            $query->setParameter('user_id', $user_id);
        } else {
            $query->where('1=-1');
        }

        $query->orderBy('id ', 'DESC');

        $query->setFirstResult(0);
        $query->setMaxResults(10);

        $records = $query->loadObjectList();
        
        return $records;
    }

    private function fetchPhotos($post_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('spp.*, mm.file as media_file');
        $query->from('#__social_posts_images', 'spp');
        $query->leftJoin('spp', '#__media_media', 'mm', 'spp.media_id = mm.id');

        if ($post_id) {
            $query->where('spp.post_id=:post_id');
            $query->setParameter('post_id', $post_id);
        } else {
            $query->where('1=-1');
        }

        $records = $query->loadObjectList();

        return $records;
    }

    private function fetchVideos($post_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('spv.*, mm.file as media_file');
        $query->from('#__social_posts_videos', 'spv');
        $query->leftJoin('spv', '#__media_media', 'mm', 'spv.file = mm.id');

        if ($post_id) {
            $query->where('spv.post_id=:post_id');
            $query->setParameter('post_id', $post_id);
        } else {
            $query->where('1=-1');
        }

        $records = $query->loadObjectList();

        return $records;
    }

    private function fetchAudios($post_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('spa.*, mm.file as media_file');
        $query->from('#__social_posts_audios', 'spa');
        $query->leftJoin('spa', '#__media_media', 'mm', 'spa.media_id = mm.id');

        if ($post_id) {
            $query->where('spa.post_id=:post_id');
            $query->setParameter('post_id', $post_id);
        } else {
            $query->where('1=-1');
        }

        $records = $query->loadObjectList();


        return $records;
    }

    private function fetchEvents($post_id) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('se.*');
        $query->from('#__social_events', 'se');

        if ($post_id) {
            $query->where('se.post_id=post_id');
            $query->setParameter('post_id', $post_id);
        } else {
            $query->where('1=-1');
        }

        $records = $query->loadObjectList();



        return $records;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Sharing

    public function fetchShare($post_id) {

        $data_obj = new \stdClass();

        $query = new Query();
        $query->select('COUNT(*) AS total');
        $query->from('#__social_posts', 'sp');

        if ($post_id) {
            $query->where('sp.post_id=:post_id');
            $query->setParameter('post_id', $post_id);
        } else {
            $query->where('1=-1');
        }

        $count = $query->loadObject();

        $data_obj->count = $count->total;

        return $data_obj;
    }

}
