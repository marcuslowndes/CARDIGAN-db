<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Accesses user table and bookings table
class User_Model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}


	public function register($enc_password, $salt){
		// codeigniter automatically escapes any values passed in using the form methods and the $this->input->post() method
		$data = array(
			'Email'			=> $this->input->post('email'),
			'Forename'		=> $this->input->post('forename'),
			'Surname'		=> $this->input->post('surname'),
			'Password' 		=> $enc_password,
			'Salt'			=> $salt,
			'User_Type' 	=> "Unverified"
		);

		return $this->db->insert('user', $data);
	}


	//get single user / all users for admins
	public function get_user($email = FALSE){
		//if no variable is passed in, return whole table
		if($email === FALSE){
			$this->db->select('ID, Forename, Surname, Email, User_Type, Account_Created, Last_Updated, Last_Logged_In');
			$query = $this->db->get('user');
			return $query->result_array();
		}
		//else return row for specific email
		$query = $this->db->get_where('user', array('Email' => $email));
		return $query->row_array();
	}


	//check email exists
	public function check_email_not_exists($email){
		$query = $this->db->get_where('user', array('Email' => $email));

		if (is_null($query->row_array())) return true;
		else return false;
	}


	//set a user's type
	public function set_user_type($id, $user_type){
		$data = array('User_Type' => $user_type);

		$this->db->where('ID', $id);
		return $this->db->update('user', $data);
	}


	//set a user's password
	public function set_user_password($id, $enc_password, $salt){
		$data = array(
			'Password' 		=> $enc_password,
			'Salt'			=> $salt
		);

		$this->db->where('ID', $id);
		return $this->db->update('user', $data);
	}


	public function login($id){
		$data = array(
			'Last_Logged_In' => date('Y-m-d H:i:s', time())
		);

		$this->db->where('ID', $id);
		return $this->db->update('user', $data);
	}


	public function delete_user($id){
		$this->db->where('ID', $id);
		return $this->db->delete('user');
	}

}