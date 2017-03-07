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

namespace Social\Members\Invites\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Email\Email;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class InvitesModel extends BaseModel {

    public function save($form_data = '') {

        $email = new Email();
        $factory = new KazistFactory();

        $form_data = $this->request->get('form');

        $document = $this->container->get('document');
        $user = $document->user;
        $emails = explode(',', $form_data['emails']);

        $factory->enqueueMessage('Email sent to Following:', 'success');
        foreach ($emails as $key => $tmp_email) {

            $parameters = new \stdClass();
            $data = new \stdClass();
            $data->email = trim($tmp_email);
            $data->user_id = $user->id;

            $parameters->user = $user;

            $email->sendDefinedLayoutEmail('social.members.invites.sendinvite', $email, $parameters);
            $factory->saveRecord('#__social_members_invites', $data, array('email=:email', 'user_id=:user_id'), $data);
            $factory->enqueueMessage($tmp_email, 'success');
        }
    }

}
