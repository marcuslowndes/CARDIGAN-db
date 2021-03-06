<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Users extends CI_Controller{

    public function register(){
        // check user is logged in
        if($this->session->userdata('logged_in')){
            redirect('welcome');
        }

        $data['title'] = 'REGISTER A NEW ACCOUNT';
        $data['user_captcha'] = $this->input->post('user_captcha');

        $this->form_validation->set_rules('forename', 'Forename', 'required');
        $this->form_validation->set_rules('surname', 'Surname', 'required');
    	$this->form_validation->set_rules('email', 'Email',
            'required|valid_email|callback_check_email_exists');
    	$this->form_validation->set_rules('password', 'Password', 'required');
    	$this->form_validation->set_rules('password2', 'Confirm Password',
            'required|matches[password]');
        $this->form_validation->set_rules('user_captcha', 'Captcha',
            'required|callback_check_captcha');

    	if($this->form_validation->run() === FALSE){
            $data['captcha'] = create_captcha(array(
                'word'          => random_string('alnum', 8),
    			'img_path'      => './captcha/',
    			'img_url'       => 'captcha/',
	            'font_path'     => './captcha/font/Roboto-Regular.tff'
            ));
            $this->session->set_userdata('captcha_word', $data['captcha']['word']);

    		$this->load->view('templates/header', $data);
            $this->load->view('users/register', $data);
            $this->load->view('templates/footer.html');
    	} else {
            // Salt and hash the password before adding to database
            try {
                // $salt = random_string('alnum', 16);
                $salt = base64_encode(random_bytes(16)); // more secure

                $enc_password = hash('sha512', $salt
                    . $this->input->post('password'));

    			$this->user_model->register($enc_password, $salt);

    			$this->session->set_flashdata('user_success', 'Success! You are'
                    . ' now registered as an <b>Unverified User</b> and can log'
                    . ' in, but you <b>must be verified</b> to gain acces to the'
                    . ' database. <br> To do this, email an admin or a member'
                    . ' of the CARDIGAN Team. You can do this using the <a href='
                    . '"contact">Contact Us</a> page.');

    			redirect('login');

            } catch (Exception $e) {
                $this->session->set_flashdata('user_failed', 'ERROR: ' . $e
                    . '<br> Please try again.');
                redirect('register');
            }
    	}
    }


    //check if email exists
    public function check_email_exists($email){
        $this->form_validation->set_message('check_email_exists',
            '<p style="color: #254151;">The email entered already has an account'
            . ' associated with it.</p>');

        if($this->user_model->check_email_not_exists($email))
            return true;
        else
            return false;
    }


    public function login(){
        // check user is logged in
        if($this->session->userdata('logged_in')) {
            $this->session->set_flashdata('user_warning', 'User already logged in.');
            redirect('welcome');
        }

        $data['title'] = 'LOG IN';

        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if($this->form_validation->run() === FALSE){
            $this->load->view('templates/header', $data);
            $this->load->view('users/login', $data);
            $this->load->view('templates/footer.html');
        } else {
            // log in user
            $email = $this->input->post('email');

            if(!$this->user_model->check_email_not_exists($email)){
                $user = $this->user_model->get_user($email);

                $enc_password = substr(hash('sha512', $user['Salt']
                    . $this->input->post('password')), 0, 45);

                if($enc_password == $user['Password']){

                    $this->user_model->login($user['ID']);

                    //create user session
                    $this->session->set_userdata(array(
                        'user_id'       => $user['ID'],
                        'forename'      => $user['Forename'],
                        'surname'       => $user['Surname'],
                        'user_type'     => $user['User_Type'],
                        'user_email'    => $user['Email'],
                        'logged_in'     => TRUE,
            			'all_selected'	=> array()
                    ));

                    //message
                    $this->session->set_flashdata('user_success', 'Welcome, '
                        . $user['Forename'] . ' ' . $user['Surname'] . '.');

                    redirect('database');

                } else
                    $this->failed_login();
            } else
                $this->failed_login();
        }
    }


    public function failed_login(){
        $this->session->set_flashdata('user_failed', 'Invalid log in, username'
            . ' or password not found.');

        redirect('login');
    }


    public function unset_user_session(){
        $this->session->set_userdata(array(
            'user_id'               => '',
            'forename'              => '',
            'surname'               => '',
            'user_type'             => '',
            'user_email'            => '',
            'logged_in'             => FALSE,
            'selected_type'         => '',
            'subtypes'              => '',
            'selected_subtype_ID'   => '',
            'selected_subtype'      => '',
            'entities'              => '',
            'selected_entity_ID'    => '',
            'selected_entity'       => '',
			'attributes'			=> '',
            'selected_attribute_ID'	=> '',
            'selected_attribute'	=> '',
			'clinical_btn_style'	=> '',
			'gait_btn_style'		=> '',
			'search_btn_style'		=> '',
            'search_btn_enable'		=> '',
			'all_selected'			=> array()
        ));
    }


    //user log out
    public function logout(){
        $this->unset_user_session();

        $this->session->set_flashdata('user_warning', 'You are now logged out.');

        redirect("login");
    }


    //allow admins to view all users and verify new ones/give admin priviliges
    public function users_list($user_id = NULL, $user_type = NULL){
        // ensure user is logged in and is an admin
        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('user_failed', 'You must be logged in'
                . ' as an admin to access this page.');
            redirect('login');
        } else if($this->session->userdata('user_type') != 'Admin'){
            $this->session->set_flashdata('user_failed', 'Only an admin may access this page.');
            redirect('welcome');
        }

        $data = array(
            'title' => 'ALL USERS',
            'users' => $this->user_model->get_user()
        );

        // if a user and user type have been specified, set that user's type
        if (isset($user_id) && isset($user_type))
            foreach ($data['users'] as $user)
                if($user_id == $user['ID']) {
                    $this->user_model->set_user_type($user_id, $user_type);
                    $this->session->set_flashdata('user_success', $user['Forename']
                        . ' ' . $user['Surname'] . ' has been set as ' . $user_type . '.');
                    redirect('view_all_users', 'refresh');
                }

        $this->load->view('templates/header', $data);
        $this->load->view('users/view_all', $data);
        $this->load->view('templates/footer.html');
    }


    public function change_password(){
        // ensure user is logged in and is an admin
        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('user_failed', 'You must be logged in '
                . 'to access this page.');
            redirect('login');
        }

        $data = array(
            'title'                 => 'MANAGE YOUR ACCOUNT',
            'is_current_pw_valid'   => FALSE,
            'id'                    => $this->session->userdata('user_id'),
            'forename'              => $this->session->userdata('forename'),
            'surname'               => $this->session->userdata('surname'),
            'email'                 => $this->session->userdata('user_email'),
        );


        $this->form_validation->set_rules('password', 'Current Password', 'required');
        $this->form_validation->set_rules('password2', 'New Password', 'required');
        $this->form_validation->set_rules('password3', 'Confirm New Password',
            'required|matches[password2]');
        $this->form_validation->set_rules('user_captcha', 'Captcha',
            'required|callback_check_captcha');
        $data['user_captcha'] = $this->input->post('user_captcha');


        if($this->form_validation->run() === FALSE){
            $data['captcha'] = create_captcha(array(
                'word'          => random_string('alnum', 8),
                'img_path'      => './captcha/',
                'img_url'       => 'captcha/',
                'font_path'     => './captcha/font/Roboto-Regular.tff'
            ));
            $this->session->set_userdata('captcha_word', $data['captcha']['word']);

            $this->load->view('templates/header', $data);
            $this->load->view('users/change_password', $data);
            $this->load->view('templates/footer.html');
        } else {
            $user = $this->user_model->get_user(
                $this->session->userdata('user_email')
            );

            $currentPW = $this->input->post('password');
            $newPW = $this->input->post('password2');
            $enc_current_password = substr(hash('sha512', $user['Salt'] . $currentPW), 0, 45);

            if($enc_current_password == $user['Password'] && $currentPW != $newPW){
                $new_salt = base64_encode(random_bytes(16));
                $enc_new_password = hash('sha512', $new_salt . $newPW);

                $this->user_model->set_user_password($user['ID'], $enc_new_password, $new_salt);

                $this->session->set_flashdata('user_success', 'Success! You have'
                    . ' changed your password.');

                redirect('change_password');
            } else {
                $this->session->set_flashdata('user_failed', 'Please submit your'
                    . ' correct current password, and ensure that your new password'
                    . ' is not the saame as your old one.');

                redirect('change_password');
            }
        }
    }


    public function delete_account($id){
        // ensure user is logged in and is the logged-in user
        if(!$this->session->userdata('logged_in')
                 || $id != $this->session->userdata('user_id')){
            $this->session->set_flashdata('user_failed', 'You must be logged in'
                . ' as the user to delete their account.');
            redirect('welcome');
        }

        $this->unset_user_session();
        $this->user_model->delete_user($id);
        $this->session->set_flashdata('user_warning', 'Account successfully deleted.');

        redirect("login");
    }


    public function check_captcha($str){
        $word = $this->session->userdata('captcha_word');
        if(strcmp(strtoupper($str),strtoupper($word)) == 0)
            return true;
        else {
            $this->form_validation->set_message('check_captcha',
                '<p style="color: #254151;">Incorrect CAPTCHA.</p>');
            return false;
        }
    }

}
