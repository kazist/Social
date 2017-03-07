<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of PostsController
 *
 * @author sbc
 */

namespace Social\Posts\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Social\Posts\Code\Models\PostModel;
use Kazist\KazistFactory;

class PostsController extends BaseController {

    public function indexAction() {

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/';


        $items = $this->model->getRecords();
        $data_arr['items'] = $items;

        $this->html = $this->render('Social:Posts:Code:views:table.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

    public function saveAction() {

        $redirect = '';

        $factory = new KazistFactory();

        $document = $this->container->get('document');
        $user = $document->user;

        $sharing = $this->request->get('sharing');
        $post = $this->request->get('post');
        $action = $this->request->get('action');
        $item_id = $this->request->get('item_id');
        $return_url = $this->request->get('return_url');

        if ($action == 'sharing') {

            $data = $factory->getRecord('#__social_posts', 'sp', array('id=:id'), array('id' => $item_id));

            unset($data->id);
            unset($data->created_by);
            unset($data->date_created);
            unset($data->modified_by);
            unset($data->date_modified);
            unset($data->system_tracking_id);
            unset($data->is_smodified);
            unset($data->slug);

            $data->post = $post;
            $data->sharing = $sharing;
            $data->post_id = $item_id;
            $data->user_id = $user->id;
            
            $factory->saveRecord('#__social_posts', $data);
            exit;
        } else {

            $form = $this->request->get('form');

            if ($form['post'] != '' || $form['has_photos'] || $form['has_videos'] || $form['has_audios'] || $form['has_events']) {
                $this->model->savePost($form);
            }
        }

        $redirect = $return_url;

        return $this->redirect($redirect);
    }

    public function postajaxAction() {

        $data_obj = new \stdClass;
        $factory = new KazistFactory();


        $post_id = $this->request->request->get('post_id');

        $postModel = new PostModel();
        $post = $postModel->getPost($post_id);

        $data_obj->sucessful = true;

        echo json_encode($data_obj);
        exit;
    }

    public function postlistajaxAction() {

        $data_obj = new \stdClass;
        $factory = new KazistFactory();



        $postModel = new PostModel();
        $posts = $postModel->getPostList();

        $data_obj->sucessful = true;

        echo json_encode($data_obj);
        exit;
    }

}
