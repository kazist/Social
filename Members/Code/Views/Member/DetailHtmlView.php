<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Member\Views\Member;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazicode\View\Detail\DetailHtmlView as GeneralDetailHtmlView;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class DetailHtmlView extends GeneralDetailHtmlView {

    public function prepare() {

        $extend_edit_dirs[] = JPATH_ROOT . '/applications/Social/Components/Templates';
        $extend_edit_dirs[] = JPATH_ROOT . '/applications/Social/Components/Member/Templates/social';

        $this->renderer->addPath($extend_edit_dirs, true);

        parent::prepare();

        $user = $this->model->getUser();
        $item = $this->model->getMember($user->id);
        $member = $this->model->getMemberDetail();
        $posts = $this->model->getPosts();


        $this->renderer->set('user', $user);
        $this->renderer->set('item', $item);
        $this->renderer->set('detail_index', $item);
        $this->renderer->set('member', $member);
        $this->renderer->set('posts', $posts);
    }

}
