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
			'selected_subtype'		=> $this->session->userdata('selected_subtype'),
            'selected_subtype_ID'	=> $this->session->userdata('selected_subtype_ID'),
            'entities'				=> $this->session->userdata('entities'),
			'selected_entity'		=> $this->session->userdata('selected_entity'),
            'selected_entity_ID'	=> $this->session->userdata('selected_entity_ID'),
			'attributes'			=> $this->session->userdata('attributes'),
			'selected_attribute'	=> $this->session->userdata('selected_attribute'),
            'selected_attribute_ID'	=> $this->session->userdata('selected_attribute_ID'),
			'all_selected'			=> $this->session->userdata('all_selected'),

			'clinical_btn_style'	=> $this->session->userdata('clinical_btn_style'),
			'gait_btn_style'		=> $this->session->userdata('gait_btn_style'),
			'search_btn_style'		=> $this->session->userdata('search_btn_style'),
			'search_btn_enable'		=> $this->session->userdata('search_btn_enable')
        );
		$data = $this->disable_btn_style($data, 'clinical');
		$data = $this->disable_btn_style($data, 'gait');
		$data = $this->disable_btn_style($data, 'search');


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
				&& count($data['attributes']) > 1)
        	$this->load->view('database/select_subcategory_data', $data);

        $this->load->view('database/close.html');
		$this->load->view('database/all_selected', $data);
        $this->load->view('templates/footer.html');
	}


	public function disable_btn_style($data, $btn){
		if ($data[$btn . '_btn_style'] == '')
			$data[$btn . '_btn_style'] = 'secondary';
		return $data;
	}


	/* Updates and refreshes the form */
	public function update_form($data, $type = NULL, $subtypeID = NULL,
								$entityID = NULL, $attributeID = NULL) {

		// if a data type is selected, enable selection of subtype/"category"
		if(isset($type) && $type != NULL){
            $this->session->set_userdata(array(
                'selected_type'			=> $type,
                'subtypes'				=> $this->eav_model->get_data_types($type),
				'selected_subtype_ID'	=> '',
	            'selected_subtype'		=> '',
	            'entities'				=> '',
	            'selected_entity_ID'	=> '',
	            'selected_entity'		=> '',
				'attributes'			=> '',
	            'selected_attribute_ID'	=> '',
	            'selected_attribute'	=> ''
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
                    'entities'				=> $this->eav_model->get_entity($subtypeID),
		            'selected_entity_ID'	=> '',
		            'selected_entity'		=> '',
					'attributes'			=> '',
		            'selected_attribute_ID'	=> '',
		            'selected_attribute'	=> ''
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
			            'selected_attribute_ID'	=> '',
			            'selected_attribute'	=> ''
                    ));

					if (count($this->session->userdata('attributes')) > 1){
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
							$this->add_to_selected();
		                }
					} elseif (count($this->session->userdata('attributes')) == 1) {
						$attr = current($this->session->userdata('attributes'));
						$this->session->set_userdata(array(
							'selected_attribute_ID'	=> $attr['idAttribute'],
							'selected_attribute'	=> $attr['Name'],
							'search_btn_style'		=> 'primary',
							'search_btn_enable'		=> 'href="database_result"'
						));
						$this->add_to_selected();
					} else {
						$this->session->set_userdata(array(
							'selected_attribute_ID'	=> '',
							'selected_attribute'	=> '',
							'search_btn_style'		=> '',
							'search_btn_enable'		=> ''
						));
					}
                }
            }
            redirect('database');
        }
	}


	public function add_to_selected(){
		if ($this->session->userdata('all_selected') === NULL)
			$this->session->set_userdata(array('all_selected' => array()));

		$all_selected = array();
		foreach ($this->session->userdata('all_selected') as $selected)
			array_push($all_selected, $selected);

		array_push($all_selected, array(
			'type'			=> $this->session->userdata('selected_type'),
            'subtype'		=> $this->session->userdata('selected_subtype'),
            'subtype_ID'	=> $this->session->userdata('selected_subtype_ID'),
            'entity'		=> $this->session->userdata('selected_entity'),
            'entity_ID'		=> $this->session->userdata('selected_entity_ID'),
			'attribute'		=> $this->session->userdata('selected_attribute'),
            'attribute_ID'	=> $this->session->userdata('selected_attribute_ID'),
		));

		$this->session->set_userdata(array('all_selected' => $all_selected));
	}


	public function remove_from_selected($id){
		if ($this->session->userdata('all_selected') === NULL)
			$this->session->set_userdata(array('all_selected' => array()));

		$all_selected = array();
		foreach ($this->session->userdata('all_selected') as $selected) {
			if ($selected['attribute_ID'] != $id)
				array_push($all_selected, $selected);
		}

		$this->session->set_userdata(array('all_selected' => $all_selected));

		redirect('database');
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
			'search_btn_enable'		=> '',
			'all_selected'			=> array()
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


		$patients = $this->eav_model->get_patient();
		$all_results = array();
		foreach ($this->session->userdata('all_selected') as $selected)
			array_push($all_results, $this->eav_model->get_results($selected['attribute_ID']));



		// reformat results for the view
		// preformatted_results gives a new results array arranged as:
			//  <idPatient> => array(
			//		<idAttribute> => <result array>
			//		... (each attribute that the patient has a result for)
			//  ) ... (for every patient)
        $preformatted_results = array();
		foreach($patients as $patient){
			$preformatted_results[$patient['Patient_ID']] = array();

			foreach ($all_results as $results)
				foreach($results as $result){
					if (!isset($preformatted_results[$patient['Patient_ID']]
							[$result['Attribute']['idAttribute']]))
						$preformatted_results[$patient['Patient_ID']]
							[$result['Attribute']['idAttribute']] = array();

					if ($patient['idPatient'] == $result['Patient']['idPatient'])
						array_push($preformatted_results[$patient['Patient_ID']]
							[$result['Attribute']['idAttribute']], $result);
				}
		}

		// formatted_results gives a new results array arranged as:
			//  <idPatient> => array(
			//		<idAttribute> => array(
			//			'Attribute' => array(...)
			//	 		'Values' => array(
			//				<idVisitation> => array(...results)
			//				... (for every visit 1 - 8)
			//	 		)
			//		) ... (for every selected attribute)
			// 	) ... (for every patient)
		$formatted_results = array();
		foreach($preformatted_results as $patient => $results_per_attr)
			foreach ($results_per_attr as $attribute => $results) {
				foreach ($results as $result) {

					$formatted_results[$patient][$attribute]['Attribute'] = $result['Attribute'];
					if (!isset($formatted_results[$patient][$attribute]['Values']
							[$result['Visitation']['idVisitation']]))
						$formatted_results[$patient][$attribute]['Values']
							[$result['Visitation']['idVisitation']] = array();

					array_push($formatted_results[$patient][$attribute]['Values']
						[$result['Visitation']['idVisitation']], $result['Value']['Value']);
				}
			}

		$data['all_results'] = $formatted_results;
		$data['visits'] = $this->eav_model->get_visitation();


		//load views
        $this->load->view('templates/header', $data);
        $this->load->view('database/result', $data);
        $this->load->view('templates/footer.html');
    }

}
