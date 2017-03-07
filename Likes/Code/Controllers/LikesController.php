<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of LikesController
 *
 * @author sbc
 */

namespace Social\Likes\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Social\Likes\Code\Models\LikesModel;
use Kazist\KazistFactory;

class LikesController extends BaseController {

    public function savelikeajaxAction() {
        $redirect = '';

        $data_obj = new \stdClass;
        $factory = new KazistFactory();



        $likesModel = new LikesModel();
        $msg = $likesModel->saveLikes();

        $data_obj->sucessful = true;
        $data_obj->data = $msg;

        echo json_encode($data_obj);
        exit;
    }

}
