<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends CI_Controller {

	public function index($type = NULL, $typeID = NULL, $entityID = NULL) {

        // ensure user is logged in and is verified
        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('user_failed', 'Only a verified'
				. ' user may access the database.');
            redirect('login');
        } else if($this->session->userdata('user_type') == 'Unverified'){
            $this->session->set_flashdata('user_warning', 'Only a verified'
				. ' user may access the database. Please contact an admin to '
				. 'request to be verified.');
            redirect('welcome');
        }


        // get current form data
        $data = array(
            'title'                 => 'Choose Clinical or Gait Data',
            'selected_type'         => $this->session->userdata('chosen_datatype'),
            'subtypes'              => $this->session->userdata('subtypes'),
            'selected_subtype_ID'   => $this->session->userdata('selected_subtype_ID'),
            'selected_subtype'      => $this->session->userdata('selected_subtype'),
            'entities'              => $this->session->userdata('entities'),
            'selected_entity_ID'    => $this->session->userdata('selected_entity_ID'),
            'selected_entity'       => $this->session->userdata('selected_entity')
        );
        if ($data['selected_entity'] == '')
            $data['selected_entity'] = 'None';


        //
        if(isset($type) && $type != NULL){
            $this->session->set_userdata(array(
                'chosen_datatype'   => $type,
                'subtypes'          => $this->eav_model->get_data_types($type)
            ));

            if(isset($typeID) && $typeID != NULL){
                foreach ($data['subtypes'] as $subtype)
                    if ($subtype['idData_Type'] == $typeID)
                        $subtype_name = $subtype['Subtype'];
                $this->session->set_userdata(array(
                    'selected_subtype_ID'   => $typeID,
                    'selected_subtype'      => $subtype_name,
                    'entities'              => $this->eav_model->get_entity($typeID)
                ));

                if(isset($entityID) && $entityID != NULL){
                    foreach ($data['entities'] as $entity)
                        if ($entity['idEntity'] == $entityID)
                            $entity_name = $entity['Name'];
                    $this->session->set_userdata(array(
                        'selected_entity_ID'    => $entityID,
                        'selected_entity'       => $entity_name,
                    ));
                }
            }
            redirect('database');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('database/index.html', $data);
        $this->load->view('database/gap.html');

        if ($data['selected_type'] != '' && $data['subtypes'] != '')
            $this->load->view('database/select_subtype', $data);
        else
            $this->load->view('database/gap.html');

        $this->load->view('database/gap.html');

        if ($data['selected_subtype_ID'] != '' && $data['entities'] != '')
            $this->load->view('database/select_entity', $data);
        else
            $this->load->view('database/gap.html');

        $this->load->view('database/gap.html');
        $this->load->view('database/selected.html');
        $this->load->view('database/gap.html');
        $this->load->view('database/close.html');
        $this->load->view('templates/footer.html');
	}


    public function reset_form(){
        // unset form-related session data
        $this->session->set_userdata(array(
            'chosen_datatype'       => '',
            'subtypes'              => '',
            'selected_subtype_ID'   => '',
            'selected_subtype'      => '',
            'entities'              => '',
            'selected_entity_ID'    => '',
            'selected_entity'       => ''
        ));

        redirect("database");
    }


    public function result(){

        // ensure user is logged in and is verified
        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('user_failed', 'Only a verified user'
				. ' may access the database.');
            redirect('login');
        } else if($this->session->userdata('user_type') == 'Unverified'){
            $this->session->set_flashdata('user_warning', 'Only a verified user'
				. ' may access the database. Please contact an admin to request'
				. ' to be verified.');
            redirect('welcome');
        }


        //
        $selected_entity_ID = $this->session->userdata('selected_entity_ID');
        $selected_entity	= $this->session->userdata('selected_entity');
		$results			= $this->eav_model->get_results($selected_entity_ID);
        $patients			= $this->eav_model->get_patient();
        $data = array(
            'title'     => 'Search Result: ' . $selected_entity,
            'visits'    => $this->eav_model->get_visitation()
        );


        $preformatted_results = array();

		foreach($patients as $patient){
			$preformatted_results[$patient['Patient_ID']] = array();

			foreach($results as $result)
				if ($patient['idPatient'] == $result['Patient']['idPatient'])
					array_push($preformatted_results[$patient['Patient_ID']], $result);
		}

		$formatted_results = array();

		foreach($preformatted_results as $patient => $results){
			$formatted_results[$patient]['Values'] = array();

			foreach($results as $result){
				$formatted_results[$patient]['Attribute'] = $result['Attribute'];
				$visit1 = $result['Visitation']['idVisitation'];
				array_push($formatted_results[$patient]['Values'], array(
					$visit1 => $result['Value']
				));
			}
		}

		$data['all_results'] = $formatted_results;


        $this->load->view('templates/header', $data);
        $this->load->view('database/result', $data);
        $this->load->view('templates/footer.html');
    }

}
