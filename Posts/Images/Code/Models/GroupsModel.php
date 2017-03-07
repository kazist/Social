<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GroupsModels
 *
 * @author dedan
 */

namespace Social\Groups\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class GroupsModel extends BaseModel {

    public function save($form_data = '') {

        if (!$form_data['id']) {
            $form_data['image'] = 0;
        }

        $id = parent::save($form_data);

        $this->saveImages($id);

        return $id;
    }

    public function saveImages($group_id) {

        $factory = new KazistFactory();

        $image_ids = $factory->uploadMedia('form.image', 'social.groups', $group_id);

        if ($image_ids[0]) {

            $data = new \stdClass();
            $data->id = $group_id;
            $data->image = $image_ids[0];

            $factory->saveRecord('#__social_groups', $data);
        }
    }

}
