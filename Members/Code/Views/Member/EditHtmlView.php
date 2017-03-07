<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Social\Member\Views\Member;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazicode\View\Edit\EditHtmlView as GeneralEditHtmlView;
use Kazicode\Service\KazistFactory;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class EditHtmlView extends GeneralEditHtmlView {

    public function prepare() {

        $extend_edit_dirs[] = JPATH_ROOT . '/applications/Social/Components/Member/Templates/Member/Edit/Profile/';
        $this->renderer->addPath($extend_edit_dirs, true);

        $factory = new KazistFactory();
        $input = $factory ->getInput();

        $inviter_name = $input->get('invite');
        $id = $input->get('id');
        $user = $factory ->getUser();

        if (!$user->id) {
            
            $user->first_name = '';
            $user->second_name = '';
            $user->username = '';
            $user->name = '';
            $user->username = '';
            $user->country_id = '';

            $factory ->redirect('app=social&com=member&subset=member');
        }

        $confirm_email = $factory ->getSetting('social_member_confirm_email');
        $separate_names = $factory ->getSetting('social_member_separate_names');
        $recaptcha_site_key = $factory ->getSetting('recaptcha_site_key');
        $is_forced_inviter = $factory ->getSetting('is_forced_inviter');
        $edit_name = $factory ->getSetting('social_member_edit_name_readonly');
        $edit_username = $factory ->getSetting('social_member_edit_username_readonly');
        $edit_email = $factory ->getSetting('social_member_edit_email_readonly');
        $edit_gender = $factory ->getSetting('social_member_edit_gender_readonly');
        $edit_country_id = $factory ->getSetting('social_member_edit_country_id_readonly');
        $edit_location_id = $factory ->getSetting('social_member_edit_location_id_readonly');
        $edit_city = $factory ->getSetting('social_member_edit_city_readonly');
        $edit_phone = $factory ->getSetting('social_member_edit_phone_readonly');
        $edit_dbo = $factory ->getSetting('social_member_edit_dbo_readonly');

        parent::prepare();

        $date_arr = array();
        $month_arr = array();
        $year_arr = array();

        $start_year = date('Y') - 100;
        $end_year = date('Y') - 13;

        for ($x = 1; $x <= 12; $x++) {
            $data = date('F', mktime(0, 0, 0, $x, 1));
            $month_arr[] = array('value' => $data, 'text' => $data);
        }

        for ($x = 1; $x <= 30; $x++) {
            $date_arr[] = array('value' => $x, 'text' => $x);
        }

        for ($x = $start_year; $x <= $end_year; $x++) {
            $year_arr[] = array('value' => $x, 'text' => $x);
        }

        $agreements = $this->model->getAgreements();
        $inviter = $this->model->getInviter();
        $user = $this->model->getUser();
        $item = $this->model->getMember($user->id);

        if ($user->id) {
            $dob_date = date('d', strtotime($item->dob));
            $dob_month = date('F', strtotime($item->dob));
            $dob_year = date('Y', strtotime($item->dob));
        }

        $this->renderer->set('confirm_email', $confirm_email);
        $this->renderer->set('separate_names', $separate_names);
        $this->renderer->set('inviter_name', $inviter_name);

        $this->renderer->set('agreements', $agreements);
        $this->renderer->set('inviter', $inviter);
        $this->renderer->set('user', $user);
        $this->renderer->set('item', $item);
        $this->renderer->set('edit_index', $item);

        $this->renderer->set('date_arr', $date_arr);
        $this->renderer->set('month_arr', $month_arr);
        $this->renderer->set('year_arr', $year_arr);

        $this->renderer->set('dob_date', $dob_date);
        $this->renderer->set('dob_month', $dob_month);
        $this->renderer->set('dob_year', $dob_year);

        $this->renderer->set('recaptcha_site_key', $recaptcha_site_key);
        $this->renderer->set('is_forced_inviter', $is_forced_inviter);

        $this->renderer->set('edit_name', $edit_name);
        $this->renderer->set('edit_username', $edit_username);
        $this->renderer->set('edit_email', $edit_email);
        $this->renderer->set('edit_gender', $edit_gender);
        $this->renderer->set('edit_country_id', $edit_country_id);
        $this->renderer->set('edit_location_id', $edit_location_id);
        $this->renderer->set('edit_city', $edit_city);
        $this->renderer->set('edit_phone', $edit_phone);
        $this->renderer->set('edit_dbo', $edit_dbo);
    }

}
