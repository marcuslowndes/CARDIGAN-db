<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Database extends CI_Controller {

	public function index($type = NULL, $subtypeID = NULL,
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
            'selected_type'			=> $this->session->userdata('selected_type'),
            'subtypes'				=> $this->session->userdata('subtypes'),
            'selected_subtype_ID'	=> $this->session->userdata('selected_subtype_ID'),
            'selected_subtype'		=> $this->session->userdata('selected_subtype'),
            'entities'				=> $this->session->userdata('entities'),
            'selected_entity_ID'	=> $this->session->userdata('selected_entity_ID'),
            'selected_entity'		=> $this->session->userdata('selected_entity'),
			'attributes'			=> $this->session->userdata('attributes'),
            'selected_attribute_ID'	=> $this->session->userdata('selected_attribute_ID'),
            'selected_attribute'	=> $this->session->userdata('selected_attribute'),
			'clinical_btn_style'	=> $this->session->userdata('clinical_btn_style'),
			'gait_btn_style'		=> $this->session->userdata('gait_btn_style'),
			'search_btn_style'		=> $this->session->userdata('search_btn_style'),
			'search_btn_enable'		=> $this->session->userdata('search_btn_enable')
        );
		if ($data['clinical_btn_style'] == '')
			$data['clinical_btn_style'] = 'secondary';
		if ($data['gait_btn_style'] == '')
			$data['gait_btn_style'] = 'secondary';
		if ($data['search_btn_style'] == '')
			$data['search_btn_style'] = 'secondary';


        // update form data
        $this->update_form($data, $type, $subtypeID, $entityID, $attributeID);


		//load views, depending on how the form is filled:
        $this->load->view('templates/header', $data);
        $this->load->view('database/index.html');

		// first, if the data type is selected, allow selection of category
        if ($data['selected_type'] != '' && $data['subtypes'] != '')
            $this->load->view('database/select_category', $data);
        else
            $this->load->view('database/gap.html');

		// then allow selection of subcategory
        if ($data['selected_subtype_ID'] != '' && $data['entities'] != '')
            $this->load->view('database/select_subcategory', $data);
        else
            $this->load->view('database/gap.html');

		$this->load->view('database/selected', $data);

		// then selection of data from subcategory
        if ($data['selected_entity_ID'] != '' && $data['attributes'] != ''
				&& sizeof($data['attributes']) != 1)
        	$this->load->view('database/select_subcategory_data', $data);
        else
            $this->load->view('database/gap.html');

        $this->load->view('database/close.html');
        $this->load->view('templates/footer.html');
	}


	/* Updates and refreshes the form */
	public function update_form($data, $type = NULL, $subtypeID = NULL,
								$entityID = NULL, $attributeID = NULL){

		// if a data type is selected, enable selection of subtype/"category"
		if(isset($type) && $type != NULL){
            $this->session->set_userdata(array(
                'selected_type'			=> $type,
                'subtypes'				=> $this->eav_model->get_data_types($type),
            ));
			if ($type == 'Clinical')
				$this->session->set_userdata(array(
					'clinical_btn_style' => 'primary',
					'gait_btn_style'	 => 'secondary'
				));
			else
				$this->session->set_userdata(array(
					'clinical_btn_style' => 'secondary',
					'gait_btn_style' 	 => 'primary'
				));


			// if subtype/"category" is selected,
			// enable selection of entity/"subcategory"
            if(isset($subtypeID) && $subtypeID != NULL){
                foreach ($data['subtypes'] as $subtype)
                    if ($subtype['idData_Type'] == $subtypeID)
                        $subtype_name = $subtype['Subtype'];
                $this->session->set_userdata(array(
                    'selected_subtype_ID'	=> $subtypeID,
                    'selected_subtype'		=> $subtype_name,
                    'entities'				=> $this->eav_model->get_entity($subtypeID)
                ));


				// if entity/"subcategory" is selected, if number of attributes
				// 		is more than 1: enable selection of attribute/"data item",
				// 		else: skip selection of attributes and enable search
                if(isset($entityID) && $entityID != NULL){
                    foreach ($data['entities'] as $entity)
                        if ($entity['idEntity'] == $entityID)
                            $entity_name = $entity['Name'];
                    $this->session->set_userdata(array(
                        'selected_entity_ID' 	=> $entityID,
                        'selected_entity'	 	=> $entity_name,
	                    'attributes'		 	=> $this->eav_model
												->get_attributes_from_entity($entityID),
                    ));

					if (sizeof($this->session->userdata('attributes')) > 1){
						if(isset($attributeID) && $attributeID != NULL){
		                    foreach ($this->session->userdata('attributes') as $attribute)
		                        if ($attribute['idAttribute'] == $attributeID)
		                            $attr_name = $attribute['Name'];
		                    $this->session->set_userdata(array(
		                        'selected_attribute_ID'	=> $attributeID,
		                        'selected_attribute'	=> $attr_name,
								'search_btn_style'		=> 'primary',
								'search_btn_enable'		=> 'href="database_result"'
		                    ));
		                }
					} else {
						$attr = current($this->session->userdata('attributes'));
						$this->session->set_userdata(array(
							'selected_attribute_ID'	=> $attr['idAttribute'],
							'selected_attribute'	=> $attr['Name'],
							'search_btn_style'		=> 'primary',
							'search_btn_enable'		=> 'href="database_result"'
						));
					}
                }
            }
            redirect('database');
        }
	}


	/* Unsets form-related session data */
	public function reset_form(){
        $this->session->set_userdata(array(
            'selected_type'			=> '',
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
			'search_btn_enable'		=> ''
        ));

        redirect("database");
    }


	/* Gives the search results in a table */
    public function result(){

        // ensure user is logged in and is verified, and a valid result has been selected
        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('user_failed', 'Only a verified user'
				. ' may access the database.');
            redirect('login');
        } else if($this->session->userdata('user_type') == 'Unverified'){
            $this->session->set_flashdata('user_warning', 'Only a verified user'
				. ' may access the database. Please contact an admin to request'
				. ' to be verified.');
            redirect('welcome');
        } else if ($this->session->userdata('selected_attribute') == ''
				|| $this->session->userdata('selected_attribute') == NULL){
            $this->session->set_flashdata('user_warning', 'A valid data item must'
				. ' be selected to return a result.');
            redirect('database');
		}


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
