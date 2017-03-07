<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of MembersController
 *
 * @author sbc
 */

namespace Social\Members\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Social\Members\Code\Models\MembersModel;
use Kazist\KazistFactory;

class MembersController extends BaseController {

    public function indexAction() {

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/Members/Code/views/social';

        $this->model = new MembersModel();
        $items = $this->model->getRecords();
        $items = $this->model->getAdditionalDetail($items);
        $json_object = $this->model->getJson();

        $data_arr['items'] = $items;
        $data_arr['json_object'] = $json_object;

        $this->html = $this->render('Social:Members:Code:views:admin:table.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
