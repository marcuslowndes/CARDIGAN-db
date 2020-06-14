<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Accesses tables related tot he EAV model
class EAV_Model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}


    //get single attribute
	public function get_attribute($id){
		//else return row for specific email
		$query = $this->db->get_where(
			'attribute', array('idAttribute' => $id)
		);
		return $query->row_array();
	}


    //get each distinct attribute for specified entity
	public function get_attributes_from_entity($entityID){
		$this->db->distinct();
		$this->db->select('Name, attribute.idAttribute');
		$this->db->from('attribute');
		$this->db->join('value', 'attribute.idAttribute = value.idAttribute');
		$this->db->where(array('value.idEntity' => $entityID));
		$query = $this->db->get();
		return $query->result_array();
	}


    //get single entity / all entities
	public function get_entity($data_type_id = FALSE){
		//if no variable is passed in, return whole table
		if($data_type_id === FALSE){
			$query = $this->db->get('entity');
			return $query->result_array();
		}
		//else return row for specific data type ID
		$this->db->select('*');
		$this->db->where(array('idDataType' => $data_type_id));
		$query = $this->db->get('entity');
		return $query->result_array();
	}


    //get single attribute / all attributes
	public function get_data_types($type = FALSE){
		//if no variable is passed in, return whole table
		if($type === FALSE){
			$query = $this->db->get('data_type');
			return $query->result_array();
		}
		//else return row for specific email
		$this->db->select('*');
		$this->db->where(array('Type' => $type));
		$query = $this->db->get('data_Type');
		return $query->result_array();
	}


    //get single patient
	public function get_patient($id = FALSE){
		//if no argument passed return all patients
		if($id === FALSE){
			$query = $this->db->get('patient');
			return $query->result_array();
		}
		//else return row for specific patient
		$query = $this->db->get_where(
			'patient', array('idPatient' => $id)
		);
		return $query->row_array();
	}


    //get single visit
	public function get_visitation($id = FALSE){
		if($id === FALSE){
			$query = $this->db->get('visitation');
			return $query->result_array();
		}
		//else return row for specific email
		$query = $this->db->get_where(
			'visitation', array('idVisitation' => $id)
		);
		return $query->row_array();
	}


	public function get_value($id, $data_type){
		$query = $this->db->get_where(
			'value_' . $data_type, array('idValue' => $id)
		);
		return $query->row_array();
	}


	// returns all the results in an array arranged as:
		//  <idValue> => array(
		// 		'Patient' => array(
		// 			'idPatient' => <idPatient>
		//			'Name'		=> ...
		//			...
		// 		)
		//		'Attribute'  => array(...)
		// 		'Visitation' => array(...)
		// 		'Value' 	 => array(...)
		//  <idValue> => array(...)
		//  ... (every returned value)
	public function get_results($attribute_id){
		$this->db->select('*');
		$this->db->from('value');
		$this->db->join('attribute', 'attribute.idAttribute = value.idAttribute');
		$this->db->where(array('attribute.idAttribute' => $attribute_id));
		$query  = $this->db->get();
		$values = $query->result_array();
		$result = array();

		foreach ($values as $val) {
			$patient 	= $this->get_patient($val['idPatient']);
			$visit		= $this->get_visitation($val['idVisitation']);
			$attribute	= $this->get_attribute($val['idAttribute']);

			switch($attribute['Value_Type']){
				case 'BOOL' :
					$value = $this->get_value($val['idValue'], 'boolean');
					break;
				case 'VARCHAR' :
					$value = $this->get_value($val['idValue'], 'varchar');
					break;
				case 'INT' :
					$value = $this->get_value($val['idValue'], 'integer');
					break;
				case 'DECIMAL' :
					$value = $this->get_value($val['idValue'], 'decimal');
					break;
				case 'TEXT' :
					$value = $this->get_value($val['idValue'], 'text');
					break;
				case 'DATE' :
					$value = $this->get_value($val['idValue'], 'date');
					break;
				case 'TIMESTAMP' :
					$value = $this->get_value($val['idValue'], 'timestamp');
					break;
			}

			$result[$val['idValue']] = array(
				'Patient'		=> $patient,
				'Attribute' 	=> $attribute,
				'Visitation' 	=> $visit,
				'Value' 		=> $value
			);
		}

		return $result;
	}


}
