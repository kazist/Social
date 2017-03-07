<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Posts\Code\Classes;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Joomla\Uri\Uri;
use Kazist\Service\Media\Upload;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class SavePost {

    public $request;

    public function __construct() {
        global $sc;
        $this->request = $sc->get('request');
    }

    public function savePostData($form) {


        $post_id = $this->savePost($form);

        if ($form['has_photos']) {
            $this->savePhotos($post_id);
        }

        if ($form['has_videos']) {
            $this->saveVideos($form, $post_id);
        }

        if ($form['has_audios']) {
            $this->saveAudios($form, $post_id);
        }

        if ($form['has_events']) {
            $this->saveEvents($form, $post_id);
        }
    }

    private function savePost($form) {

        $post_obj = new \stdClass();
        $factory = new KazistFactory();
        $user = $factory->getUser();

        $post_obj->post = $form['post'];
        $post_obj->privacy = (!isset($form['privacy']) && $form['privacy'] <> '') ? $form['privacy'] : 'public';
        $post_obj->user_id = $user->id;

        return $factory->saveRecord('#__social_posts', $post_obj);
    }

    private function savePhotos($post_id) {
        $factory = new KazistFactory();

        $media_ids = $factory->uploadMedia('form.photo', 'social.post', $post_id);

        if (!empty($media_ids)) {
            foreach ($media_ids as $media_id) {
                $std_class = new \stdClass();

                $std_class->post_id = $post_id;
                $std_class->media_id = $media_id;

                $factory->saveRecord('#__social_posts_images', $std_class);
            }
        }
    }

    private function saveVideos($form, $post_id) {

        $factory = new KazistFactory();
        $std_class = new \stdClass();

        $std_class->type = $form['video_type'];
        if ($form['video_type'] == 'youtube') {
            $std_class = $this->saveYoutubeVideo($std_class, $form);
        }
        if ($form['video_type'] == 'embed') {
            $std_class->embed_code = $form['embed_code'];
        }
        if ($form['video_type'] == 'file') {
            $video_files = $factory->uploadMedia('form.video_file', 'social.post', $post_id);
            $std_class->file = $video_files[0];
        }

        $std_class->post_id = $post_id;


        $factory->saveRecord('#__social_posts_videos', $std_class);
    }

    private function saveYoutubeVideo($std_class, $form) {
        $upload = new Upload();
        $uri = new Uri($form['youtube']);
        $code = $uri->getVar('v');

        $image_url = 'http://img.youtube.com/vi/' . $code . '/0.jpg';
        $image_name = $code . '.jpg';

        $media_id = $upload->downloadImageNSave($image_url, $image_name, null, 'youtube', 'social', 'post', 'videos');

        $std_class->youtube_code = $code;
        $std_class->image = $media_id;

        return $std_class;
    }

    private function saveAudios($form, $post_id) {
        $factory = new KazistFactory();
        $std_class = new \stdClass();

        $audio_files = $factory->uploadMedia('form.audio_file', 'social.post', $post_id);

        $std_class->embed_code = $form['embed_code'];
        $std_class->post_id = $post_id;
        $std_class->media_id = $audio_files[0];

        $factory->saveRecord('#__social_posts_audios', $std_class);
    }

    private function saveEvents($form, $post_id) {
        $factory = new KazistFactory();
        $std_class = new \stdClass();

        $std_class->start_date = $form['start_date'];
        $std_class->end_date = $form['end_date'];
        $std_class->start_time = $form['start_time'];
        $std_class->end_time = $form['end_time'];

        $factory->saveRecord('#__social_events', $std_class);
    }

}
