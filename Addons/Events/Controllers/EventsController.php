<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Addons\Events\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Social\Addons\Events\Models\EventsModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class EventsController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new EventsModel;

        $events = $model->getLatestEvents();

        $data_arr['events'] = $events;

        $this->html = $this->render('Social:Addons:Events:views:events.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
