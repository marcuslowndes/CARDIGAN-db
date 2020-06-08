<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//all static html pages for information 
class Welcome extends CI_Controller {

	public function index() {
		$data['title'] = 'WELCOME TO THE CARDIGAN PROJECT';
		$this->static_page('welcome_message', $data);
	}


	public function about() {
		$data['title'] = 'ABOUT THE CARDIGAN PROJECT';
		$this->static_page('about', $data);
	}


	public function contact() {
		$data['title'] = 'CONTACT THE CARDIGAN TEAM';
		$this->static_page('contact', $data);
	}


	public function static_page($pagename, $data){
		$this->load->view('templates/header', $data);
		$this->load->view('welcome/' . $pagename . '.html', $data);
		$this->load->view('templates/footer.html');	
	}

}
