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

namespace Social\Members\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Social\Members\Code\Models\MembersModel;
use Kazist\KazistFactory;
use Social\Members\Code\Classes\Audios;
use Social\Members\Code\Classes\Events;
use Social\Members\Code\Classes\Friends;
use Social\Members\Code\Classes\Groups;
use Social\Members\Code\Classes\Photos;
use Social\Members\Code\Classes\Videos;

class MembersController extends BaseController {

    public function indexAction() {

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/summary';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/header';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/post';

        $this->model = new MembersModel();
        $items = $this->model->getRecords();
        $items = $this->model->getAdditionalDetail($items);
        $json_object = $this->model->getJson();

        $data_arr['json_object'] = $json_object;
        $data_arr['items'] = $items;

        $this->html = $this->render('Social:Members:Code:views:table.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function detailAction() {

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/summary';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/header';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/post';

        $this->model = new MembersModel();

        $user = $this->model->getUser();
        $item = $this->model->getMember($user->id);
        $member = $this->model->getMemberDetail();
        $posts = $this->model->getPosts();
        $json_object = $this->model->getJson();

        if (!$user->id) {
            return $this->redirectToRoute('social.posts');
        }

        $data_arr['json_object'] = $json_object;
        $data_arr['user'] = $user;
        $data_arr['item'] = $item;
        $data_arr['member'] = $member;
        $data_arr['posts'] = $posts;

        $this->html = $this->render('Social:Members:Code:views:detail.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function editAction() {

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/Members/Code/views/edit/profile/';

        $factory = new KazistFactory();

        $action = $this->request->query->get('action');

        $confirm_email = $factory->getSetting('social_member_confirm_email');
        $separate_names = $factory->getSetting('social_member_separate_names');
        $recaptcha_site_key = $factory->getSetting('recaptcha_site_key');
        $is_forced_inviter = $factory->getSetting('is_forced_inviter');
        $edit_name = $factory->getSetting('social_member_edit_name_readonly');
        $edit_username = $factory->getSetting('social_member_edit_username_readonly');
        $edit_email = $factory->getSetting('social_member_edit_email_readonly');
        $edit_gender = $factory->getSetting('social_member_edit_gender_readonly');
        $edit_country_id = $factory->getSetting('social_member_edit_country_id_readonly');
        $edit_location_id = $factory->getSetting('social_member_edit_location_id_readonly');
        $edit_city = $factory->getSetting('social_member_edit_city_readonly');
        $edit_phone = $factory->getSetting('social_member_edit_phone_readonly');
        $edit_dbo = $factory->getSetting('social_member_edit_dbo_readonly');

        $date_arr = array();
        $month_arr = array();
        $year_arr = array();

        $start_year = date('Y') - 100;
        $end_year = date('Y') - 18;

        for ($x = 1; $x <= 12; $x++) {
            $data = date('F', mktime(0, 0, 0, $x, 1));
            $month_arr[] = array('value' => $data, 'text' => $data);
        }

        for ($x = 1; $x <= 31; $x++) {
            $date_arr[] = array('value' => $x, 'text' => $x);
        }

        for ($x = $start_year; $x <= $end_year; $x++) {
            $year_arr[] = array('value' => $x, 'text' => $x);
        }

        $year_arr = array_reverse($year_arr);

        $agreements = $this->model->getAgreements();
        $inviter = $this->model->getInviter();
        $user = $this->model->getUser();
        $item = $this->model->getMember($user->id);

        if ($user->id) {
            $dob_date = date('d', strtotime($item->dob));
            $dob_month = date('F', strtotime($item->dob));
            $dob_year = date('Y', strtotime($item->dob));
        }

        $json_object = $this->model->getJson();

        $data_arr['action'] = $action;

        $data_arr['json_object'] = $json_object;
        $data_arr['confirm_email'] = $confirm_email;
        $data_arr['separate_names'] = $separate_names;

        $data_arr['agreements'] = $agreements;
        $data_arr['inviter'] = $inviter;
        $data_arr['user'] = $user;
        $data_arr['item'] = $item;

        $data_arr['date_arr'] = $date_arr;
        $data_arr['month_arr'] = $month_arr;
        $data_arr['year_arr'] = $year_arr;

        $data_arr['dob_date'] = $dob_date;
        $data_arr['dob_month'] = $dob_month;
        $data_arr['dob_year'] = $dob_year;

        $data_arr['recaptcha_site_key'] = $recaptcha_site_key;
        $data_arr['is_forced_inviter'] = $is_forced_inviter;

        $data_arr['edit_name'] = $edit_name;
        $data_arr['edit_username'] = $edit_username;
        $data_arr['edit_email'] = $edit_email;
        $data_arr['edit_gender'] = $edit_gender;
        $data_arr['edit_country_id'] = $edit_country_id;
        $data_arr['edit_location_id'] = $edit_location_id;
        $data_arr['edit_city'] = $edit_city;
        $data_arr['edit_phone'] = $edit_phone;
        $data_arr['edit_dbo'] = $edit_dbo;

        $this->html = $this->render('Social:Members:Code:views:edit:edit.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

    function ajaxinviterAction() {

        $this->model = new MembersModel();
        echo $this->model->getInviterInput();

        exit;
    }

    public function registerAction() {

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/Members/Code/views/';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/Members/Code/views/edit/profile/';

        $session = $this->container->get('session');
        $session_form = $session->get('session_form');

        $this->model = new MembersModel();

        $factory = new KazistFactory();

        $default_step = ($factory->getSetting('is_forced_inviter')) ? 1 : 2;
        $step = $this->request->get('step', $default_step);
        $default = $this->request->get('default');
        $inviter_name = $this->request->get('invite');
        $id = $this->request->get('id');
        $form = $this->request->get('form');

        $form = (!empty($form)) ? $form : array();
        $new_form = (!empty($session_form)) ? array_merge($session_form, $form) : $form;

        $session->set('session_form', $new_form);

        $captcha_enable = $factory->getSetting('social_member_captcha_enable');
        $confirm_email = $factory->getSetting('social_member_confirm_email');
        $separate_names = $factory->getSetting('social_member_separate_names');
        $recaptcha_site_key = $factory->getSetting('recaptcha_site_key');
        $is_forced_inviter = $factory->getSetting('is_forced_inviter');

        $date_arr = array();
        $month_arr = array();
        $year_arr = array();

        $start_year = date('Y') - 100;
        $end_year = date('Y') - 18;

        for ($x = 1; $x <= 12; $x++) {
            $data = date('F', mktime(0, 0, 0, $x, 1));
            $month_arr[] = array('value' => $data, 'text' => $data);
        }

        for ($x = 1; $x <= 31; $x++) {
            $date_arr[] = array('value' => $x, 'text' => $x);
        }

        for ($x = $start_year; $x <= $end_year; $x++) {
            $year_arr[] = array('value' => $x, 'text' => $x);
        }

        $year_arr = array_reverse($year_arr);

        $agreements = $this->model->getAgreements();
        $inviter = $this->model->getInviter();
        $user = $this->model->getUser();
        $item = $this->model->getMember($user->id);

        if ($user->id) {
            $dob_date = date('d', strtotime($item->dob));
            $dob_month = date('F', strtotime($item->dob));
            $dob_year = date('Y', strtotime($item->dob));
        } else {
            $user->name = null;
            $user->username = null;
            $user->first_name = null;
            $user->second_name = null;
        }

        $json_object = $this->model->getJson();

        $data_arr['step'] = ($step) ? $step : 1;
        $data_arr['json_object'] = $json_object;
        $data_arr['confirm_email'] = $confirm_email;
        $data_arr['separate_names'] = $separate_names;
        $data_arr['inviter_name'] = $inviter_name;

        $data_arr['captcha_enable'] = $captcha_enable;
        $data_arr['agreements'] = $agreements;
        $data_arr['inviter'] = $inviter;
        $data_arr['user'] = $user;
        $data_arr['item'] = $item;

        $data_arr['date_arr'] = $date_arr;
        $data_arr['month_arr'] = $month_arr;
        $data_arr['year_arr'] = $year_arr;

        $data_arr['dob_date'] = $dob_date;
        $data_arr['dob_month'] = $dob_month;
        $data_arr['dob_year'] = $dob_year;

        $data_arr['recaptcha_site_key'] = $recaptcha_site_key;
        $data_arr['is_forced_inviter'] = $is_forced_inviter;

        $this->html = $this->render('Social:Members:Code:views:register.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

    public function saveregistrationAction() {

        $redirect = '';

        $factory = new KazistFactory();

        $session = $this->container->get('session');
        $session_form = $session->get('session_form');

        $form = $this->request->request->get('form');
        $form = (!empty($form)) ? $form : array();
        $new_form = (!empty($session_form)) ? array_merge($session_form, $form) : $form;

        $session->set('session_form', $new_form);
        // print_r($session_form); exit;
        $default_arr = ((int) $form['is_default']) ? array('default' => 'true') : '';

        if ($form['first_name'] == '' && $form['second_name'] == '' && $form['name'] == '') {
            $msg = 'Account Not added due to some errors;';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('social.members.register' . $default_arr);
        }

        $this->model = new MembersModel();
        $user_id = $this->model->registerMember($new_form);

        if ($user_id) {
            $email_verification = $factory->getSetting('users_users_email_verification');

            if ($email_verification) {
                $msg = 'You need to verify your email address before logging in. We sent a verification code to: ' . $form['email'];
                $location = 'registration_thankyou';
            } else {
                $msg = ' You Can now login';
                $location = 'login';
            }

            $factory->enqueueMessage($msg, 'info');
            $redirect = $this->generateUrl($location);
            $session->clear('session_form');
        } else {

            $msg = 'Account Not added due to some errors;';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('social.members.register' . $default_arr);
        }

        return $this->redirect($redirect);
    }

    public function saveAction() {

        $redirect = '';

        $factory = new KazistFactory();

        $session = $factory->getSession();

        $form = $this->request->get('form');

        $session->set('session_form', $form);

        $user_id = $this->model->saveMember($form);

        if ($user_id) {
            $msg = ' Record Saved Successfully';
            $factory->enqueueMessage($msg, 'info');
            $redirect = $this->generateUrl('social.members.detail');
            $session->remove('session_form');
        } else {
            $msg = 'Your Account was Not updated due to some errors;';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('social.members.edit');
        }

        return $this->redirect($redirect);
    }

    public function audioAction() {

        $audios = new Audios();

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/summary';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/header';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/post';

        $this->model = new MembersModel();

        $member = $this->model->getMemberDetail();
        $audiolist = $audios->getAudioList();

        $data_arr['member'] = $member;
        $data_arr['audiolist'] = $audiolist;

        $this->html = $this->render('Social:Members:Code:views:audio.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function eventAction() {

        $events = new Events();

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/summary';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/header';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/post';

        $this->model = new MembersModel();

        $member = $this->model->getMemberDetail();
        $eventlist = $events->getEventList();


        $data_arr['member'] = $member;
        $data_arr['eventlist'] = $eventlist;

        $this->html = $this->render('Social:Members:Code:views:event.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function friendAction() {

        $friend = new Friends();

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/summary';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/header';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/post';

        $this->model = new MembersModel();

        $member = $this->model->getMemberDetail();
        $friendlist = $friend->getFriendList();


        $data_arr['member'] = $member;
        $data_arr['friendlist'] = $friendlist;

        $this->html = $this->render('Social:Members:Code:views:friend.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function groupAction() {

        $groups = new Groups();

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/summary';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/header';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/post';

        $this->model = new MembersModel();

        $member = $this->model->getMemberDetail();
        $grouplist = $groups->getGroupList();


        $data_arr['member'] = $member;
        $data_arr['grouplist'] = $grouplist;


        $this->html = $this->render('Social:Members:Code:views:group.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function photoAction() {

        $photos = new Photos();

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/summary';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/header';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/post';

        $this->model = new MembersModel();

        $member = $this->model->getMemberDetail();
        $photolist = $photos->getPhotoList();

        $data_arr['member'] = $member;
        $data_arr['photolist'] = $photolist;

        $this->html = $this->render('Social:Members:Code:views:photo.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function videoAction() {

        $videos = new Videos();

        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/summary';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/header';
        $this->twig_paths[] = JPATH_ROOT . '/applications/Social/generalviews/post';

        $this->model = new MembersModel();

        $member = $this->model->getMemberDetail();
        $videolist = $videos->getVideoList();


        $data_arr['member'] = $member;
        $data_arr['videolist'] = $videolist;

        $this->html = $this->render('Social:Members:Code:views:video.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

    function cronregisteruserAction() {
        $this->model = new MembersModel();
        $this->model->syncRegisteredUsers();
    }

}
