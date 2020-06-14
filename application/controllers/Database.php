<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends CI_Controller {

	public function index($type = NULL, $typeID = NULL,
	 					  $entityID = NULL, $attributeID = NULL) {

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
            'title'					=> 'Choose Clinical or Gait Data',
            'selected_type'			=> $this->session->userdata('chosen_datatype'),
            'subtypes'				=> $this->session->userdata('subtypes'),
            'selected_subtype_ID'	=> $this->session->userdata('selected_subtype_ID'),
            'selected_subtype'		=> $this->session->userdata('selected_subtype'),
            'entities'				=> $this->session->userdata('entities'),
            'selected_entity_ID'	=> $this->session->userdata('selected_entity_ID'),
            'selected_entity'		=> $this->session->userdata('selected_entity'),
			'attributes'			=> $this->session->userdata('attributes'),
            'selected_attribute_ID'	=> $this->session->userdata('selected_attribute_ID'),
            'selected_attribute'	=> $this->session->userdata('selected_attribute')
        );
        if ($data['selected_entity'] == '')
            $data['selected_entity'] = 'None';


        // update form data
        if(isset($type) && $type != NULL){
            $this->session->set_userdata(array(
                'chosen_datatype'	=> $type,
                'subtypes'			=> $this->eav_model->get_data_types($type)
            ));

            if(isset($typeID) && $typeID != NULL){
                foreach ($data['subtypes'] as $subtype)
                    if ($subtype['idData_Type'] == $typeID)
                        $subtype_name = $subtype['Subtype'];
                $this->session->set_userdata(array(
                    'selected_subtype_ID'	=> $typeID,
                    'selected_subtype'		=> $subtype_name,
                    'entities'				=> $this->eav_model->get_entity($typeID)
                ));

                if(isset($entityID) && $entityID != NULL){
                    foreach ($data['entities'] as $entity)
                        if ($entity['idEntity'] == $entityID)
                            $entity_name = $entity['Name'];
                    $this->session->set_userdata(array(
                        'selected_entity_ID' => $entityID,
                        'selected_entity'	 => $entity_name,
	                    'attributes'		 => $this->eav_model
							->get_attributes_from_entity($entityID)
                    ));

					// // TEST get_attributes_from_entity
					// foreach ($this->session->userdata('attributes') as $value) {
					// 	foreach ($value as $key => $val)
					// 	 	echo $key . ' ' . $val . ' ';
					// 	echo '<br>';
					// }
					// die();

	                if(isset($attributeID) && $attributeID != NULL){
	                    foreach ($data['attributes'] as $attribute)
	                        if ($attribute['idAttribute'] == $attributeID)
	                            $attr_name = $attribute['Name'];
	                    $this->session->set_userdata(array(
	                        'selected_attribute_ID'	=> $attributeID,
	                        'selected_attribute'	=> $attr_name,
	                    ));
	                }
                }
            }
            redirect('database');
        }


		//load views, depending on how the form is filled
        $this->load->view('templates/header', $data);
        $this->load->view('database/index.html', $data);
        $this->load->view('database/gap.html');

		// first, if the data type is selected, allow selection of category
        if ($data['selected_type'] != '' && $data['subtypes'] != '')
            $this->load->view('database/select_category', $data);
        else
            $this->load->view('database/gap.html');

        $this->load->view('database/gap.html');

		// then allow selection of subcategory
        if ($data['selected_subtype_ID'] != '' && $data['entities'] != '')
            $this->load->view('database/select_subcategory', $data);
        else
            $this->load->view('database/gap.html');

        $this->load->view('database/gap.html');

		// then selection of data from subcategory
        if ($data['selected_entity_ID'] != '' && $data['attributes'] != '')
            $this->load->view('database/select_subcategory_data', $data);
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

		// TODO: IF ENTITY HAS ONLY ONE (1) ATTRIBUTE, SKIP ATTRIBUTE SELECTION
		// TODO: ENABLE SELECTION OF MULTIPLE DATA TO ADD TO RESULTS? (table)

        // get search result data
        $selected_attribute_ID = $this->session->userdata('selected_attribute_ID');
        $selected_attribute	= $this->session->userdata('selected_attribute');
		$results			= $this->eav_model->get_results($selected_attribute_ID);
        $patients			= $this->eav_model->get_patient();
        $data = array(
            'title'		=> 'Search Result: ' . $selected_attribute,
            'visits'	=> $this->eav_model->get_visitation()
        );


		// reformat results for the view
		// preformatted_results gives a new results array arranged as:
			//  <idPatient> => array(
			//		<idValue> => array(...)
			//		<idValue>...
			//		... (each result for that patient)
			//  )
			//  <idPatient> => array(...)
			//  ... (every patient)
        $preformatted_results = array();
		foreach($patients as $patient){
			$preformatted_results[$patient['Patient_ID']] = array();

			foreach($results as $result)
				if ($patient['idPatient'] == $result['Patient']['idPatient'])
					array_push($preformatted_results[$patient['Patient_ID']], $result);
		}

		// formatted_results gives a new results array arranged as:
			//  <idPatient> => array(
			// 		'Values' => array(
			//			<idVisitation 1...8> => array(
			//				... (all result values for that visit)
			//			)
			// 		)
			//		'Attribute' => array(...)
			// 	)
			//  <idPatient> => array(...)
			//  ... (every patient)
		$formatted_results = array();
		foreach($preformatted_results as $patient => $results){
			$formatted_results[$patient]['Values'] = array();

			foreach($results as $result){
				$formatted_results[$patient]['Attribute'] = $result['Attribute'];
				$visit = $result['Visitation']['idVisitation'];
				array_push($formatted_results[$patient]['Values'], array(
					$visit => $result['Value']
				));
			}
		}

		$data['all_results'] = $formatted_results;


		//load views
        $this->load->view('templates/header', $data);
        $this->load->view('database/result', $data);
        $this->load->view('templates/footer.html');
    }

}
