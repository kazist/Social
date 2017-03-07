<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Addons\Members\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Social\Addons\Members\Models\MembersModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class MembersController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new MembersModel;

        $members = $model->getLatestMembers();

        $data_arr['members'] = $members;

        $this->html = $this->render('Social:Addons:Members:views:members.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
