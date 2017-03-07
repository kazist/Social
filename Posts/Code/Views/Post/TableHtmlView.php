<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Postss\Views\Post;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazicode\View\Table\TableHtmlView as GeneralTableHtmlView;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class TableHtmlView extends GeneralTableHtmlView {

    public function prepare() {

        $extend_edit_dirs[] = JPATH_ROOT . '/applications/Social/Components/Templates/';
        $this->renderer->addPath($extend_edit_dirs, true);

        parent::prepare();
    }

}
