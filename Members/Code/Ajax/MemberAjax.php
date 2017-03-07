<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Member\Ajax;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Social\Member\Models\MemberModel;

/**
 * Description of MemberAjax
 *
 * @author sbc
 */
class MemberAjax {

    //put your code here

    function ajaxinviter() {

        $memberModel = new MemberModel();
        echo $memberModel->getInviterInput();
        exit;
    }

}
