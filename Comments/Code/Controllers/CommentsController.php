<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of CommentsController
 *
 * @author sbc
 */

namespace Social\Comments\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Social\Comments\Code\Models\CommentsModel;
use Kazist\KazistFactory;

class CommentsController extends BaseController {

    public function savecommentajaxAction() {

        $data_obj = new \stdClass;
        $factory = new KazistFactory();

        $commentModel = new CommentsModel();
        $msg = $commentModel->saveComment();

        $data_obj->sucessful = true;
        $data_obj->data = $msg;

        echo json_encode($data_obj);
        exit;
    }

    public function deletecommentajaxAction() {

        $data_obj = new \stdClass;
        $factory = new KazistFactory();



        $commentModel = new CommentsModel();
        $msg = $commentModel->deleteComment();

        $data_obj->sucessful = true;
        $data_obj->data = $msg;

        echo json_encode($data_obj);
        exit;
    }

}
