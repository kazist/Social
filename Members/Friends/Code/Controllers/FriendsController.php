<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of FriendsController
 *
 * @author sbc
 */

namespace Social\Members\Friends\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;

class FriendsController extends BaseController {

    public function saveAction() {

        $this->model->save();

        return $this->redirectToRoute('social.members');
    }

}
