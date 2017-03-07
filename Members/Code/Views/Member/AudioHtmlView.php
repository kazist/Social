<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Member\Views\Member;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazicode\View\Detail\DetailHtmlView as GeneralDetailHtmlView;
use Social\Members\Code\Classes\Audios;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class AudioHtmlView extends GeneralDetailHtmlView {

    public function prepare() {

        $audios = new Audios();

        $extend_edit_dirs[] = JPATH_ROOT . '/applications/Social/Components/Templates';
        $extend_edit_dirs[] = JPATH_ROOT . '/applications/Social/Components/Member/Templates/social';

        $this->renderer->addPath($extend_edit_dirs, true);

        parent::prepare();

        $member = $this->model->getMemberDetail();
        $audiolist = $audios->getAudioList();


        $this->renderer->set('member', $member);
        $this->renderer->set('audiolist', $audiolist);
    }

}
