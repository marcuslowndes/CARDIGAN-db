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
		$this->form_validation->set_rules('user_captcha', 'Captcha',
			'required|callback_check_captcha');
		$data['user_captcha'] = $this->input->post('user_captcha');

		if($this->form_validation->run() === FALSE) {
			$data['captcha'] = create_captcha(array(
				'word'          => random_string('alnum', 8),
				'img_path'      => './captcha/',
				'img_url'       => 'captcha/',
				'font_path'     => './captcha/font/Roboto-Regular.tff'
			));
			$this->session->set_userdata('captcha_word', $data['captcha']['word']);
			$this->static_page('contact', $data);
		} else {
			$post_data = $this->input->post();

			$this->email->from($post_data['email'], $post_data['name']);
			$this->email->to('17090299@brookes.ac.uk');
			$this->email->subject($post_data['subject']);
			$this->email->message($post_data['message']);

            if($this->email->send() === TRUE) {
                // Unset form data
                $post_data = array();

				$this->session->set_flashdata('user_success', 'Your message has'
					. ' successfully been sent.');
            } else
				$this->session->set_flashdata('user_failed', 'Unfortunately, your'
                    . ' message did not send successfully.');

			redirect('contact');
		}
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
