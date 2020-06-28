<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//all static html pages for information
class Welcome extends CI_Controller {

	public function index() {
		$data['title'] = 'WELCOME';
		$this->static_page('welcome_message.html', $data);
	}


	public function about() {
		$data['title'] = 'ABOUT US';
		$this->static_page('about.html', $data);
	}


	public function contact() {
		$data['title'] = 'CONTACT THE CARDIGAN TEAM';

		if ($this->session->userdata('logged_in') === TRUE) {
			$data['name'] = $this->session->userdata('forename') . ' '
				. $this->session->userdata('surname');
			$data['email'] = $this->session->userdata('user_email');
		} else {
			$data['name'] = '';
			$data['email'] = '';
		}

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if($this->form_validation->run() === FALSE)
			$this->static_page('contact', $data);
		else {
			$data['email_data'] = array(
				$this->input->post('name'),
				$this->input->post('email'),
				$this->input->post('subject'),
				$this->input->post('message')
			);
		}
	}


	public function gallery() {
		$data['title'] = 'PHOTO GALLERY';

		// get every photo in photo directories
		$data['photos_dir1'] = array();
		$dir1 = scandir('assets/images/FOTOS');
		foreach ($dir1 as $photo)
			if (!in_array($photo, array('.', '..')))
				array_push($data['photos_dir1'], $photo);

		$data['photos_dir2'] = array();
		$dir2 = scandir('assets/images/Pictures + Video (Mireya)');
		foreach ($dir2 as $photo)
			if (!in_array($photo, array('.', '..', 'Logos', 'videos')))
				array_push($data['photos_dir2'], $photo);

		$this->static_page('gallery', $data);
	}


	public function static_page($pagename, $data){
		$this->load->view('templates/header', $data);
		$this->load->view('welcome/' . $pagename, $data);
		$this->load->view('templates/footer.html');
	}

}
