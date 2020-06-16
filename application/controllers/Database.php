<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Database extends CI_Controller {

	public function index($type = NULL, $typeID = NULL, $entityID = NULL, $attributeID = NULL) {

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

		if ($this->session->userdata('selected_subtype') === NULL
			|| $this->session->userdata('selected_entity') === NULL
			|| $this->session->userdata('selected_attribute') === NULL
			|| $this->session->userdata('btn_style') === NULL ){
			$this->session->set_userdata(array(
				'selected_subtype'	 => array('name'=>'', 'id'=>''),
				'selected_entity'	 => array('name'=>'', 'id'=>''),
				'selected_attribute' => array('name'=>'', 'id'=>''),
				'btn_style'			 => array('clinical'=>'', 'gait'=>'', 'search'=>'')
			));
		}

        // get current form data
        $data = array(
            'title'					=> 'Choose Clinical or Gait Data',
            'selected_type'			=> $this->session->userdata('selected_type'),
            'subtypes'				=> $this->session->userdata('subtypes'),
			'selected_subtype' 		=> array(
										'name'	=> $this->session->userdata('selected_subtype')['name'],
										'id'	=> $this->session->userdata('selected_subtype')['id']
									),
            'entities'				=> $this->session->userdata('entities'),
			'selected_entity' 		=> array(
										'name'	=> $this->session->userdata('selected_entity')['name'],
										'id'	=> $this->session->userdata('selected_entity')['id']
									),
			'attributes'			=> $this->session->userdata('attributes'),
			'selected_attribute' 	=> array(
										'name'	=> $this->session->userdata('selected_attribute')['name'],
										'id'	=> $this->session->userdata('selected_attribute')['id']
									),
			'btn_style' 			=> array(
										'clinical'	=> $this->session->userdata('btn_style')['clinical'],
										'gait'		=> $this->session->userdata('btn_style')['gait'],
										'search'	=> $this->session->userdata('btn_style')['search']
									),
			'search_btn_enable'		=> $this->session->userdata('search_btn_enable')
        );
        if ($data['selection_descriptor'] == '')
            $data['selection_descriptor'] = 'None';
		foreach ($data['btn_style'] as $style)
			if ($style == '')
				$style = 'secondary';

        // update form data
        $this->update_form($data, $type, $typeID, $entityID, $attributeID);


		//load views, depending on how the form is filled:
        $this->load->view('templates/header', $data);
        $this->load->view('database/index.html');

		// first, if the data type is selected, allow selection of category
        if ($data['selected_type'] != '' && $data['subtypes'] != '')
            $this->load->view('database/select_category', $data);
        else
            $this->load->view('database/gap.html');

		// then allow selection of subcategory
        if ($data['selected_subtype']['name'] != '' && $data['entities'] != '')
            $this->load->view('database/select_subcategory', $data);
        else
            $this->load->view('database/gap.html');

		$this->load->view('database/selected.html');

		// then selection of data from subcategory
        if ($data['selected_entity']['id'] != '' && $data['attributes'] != ''
				&& sizeof($data['attributes']) != 1)
        	$this->load->view('database/select_subcategory_data', $data);
        else
            $this->load->view('database/gap.html');

        $this->load->view('database/close.html');
        $this->load->view('templates/footer.html');
	}


	/* Updates and refreshes the form */
	public function update_form($data, $type = NULL, $typeID = NULL, $entityID = NULL, $attributeID = NULL){

		// if a data type is selected, enable selection of subtype/"category"
		if(isset($type) && $type != NULL){
            $this->session->set_userdata(array(
                'selected_type'			=> $type,
                'subtypes'				=> $this->eav_model->get_data_types($type),
            ));
			if ($type == 'Clinical') {
				$this->session->userdata('btn_style')['clinical'] = 'primary';
				$this->session->userdata('btn_style')['gait']	  = 'secondary';
			} else {
				$this->session->userdata('btn_style')['clinical'] = 'secondary';
				$this->session->userdata('btn_style')['gait']	  = 'primary';
			}


			// if subtype/"category" is selected,
			// enable selection of entity/"subcategory"
            if(isset($typeID) && $typeID != NULL){
                foreach ($data['subtypes'] as $subtype)
                    if ($subtype['idData_Type'] == $typeID)
                        $subtype_name = $subtype['Subtype'];
                $this->session->set_userdata(array(
					'selected_subtype'	=> array('name' => $subtype_name,
												 'id'	=> $typeID),
                    'entities'			=> $this->eav_model->get_entity($typeID)
                ));


				// if entity/"subcategory" is selected, if number of attributes
				// 		is more than 1: enable selection of attribute/"data item",
				// 		else: skip selection of attributes and enable search
                if(isset($entityID) && $entityID != NULL){
                    foreach ($data['entities'] as $entity)
                        if ($entity['idEntity'] == $entityID)
                            $entity_name = $entity['Name'];
                    $this->session->set_userdata(array(
                        'selected_entity'	 	=> array('name' => $entity_name,
														 'id'	=> $entityID),
						'selection_descriptor'	=> $entity_name . ' Data',
	                    'attributes'		 	=> $this->eav_model->get_attributes_from_entity($entityID),
                    ));

					if (sizeof($this->session->userdata('attributes')) > 1){
						if(isset($attributeID) && $attributeID != NULL){
		                    foreach ($this->session->userdata('attributes') as $attribute)
		                        if ($attribute['idAttribute'] == $attributeID)
		                            $attr_name = $attribute['Name'];
		                    $this->session->set_userdata(array(
		                        'selected_attribute'	=> array('name' => $attr_name,
																 'id'	=> $attributeID),
								'search_btn_enable'		=> 'href="database_result"',
								'selection_descriptor'	=> $entity_name . ' Data – ' . $attr_name
		                    ));
							$this->session->userdata('btn_style')['search'] = 'primary';
		                }
					} else {
						$attr = current($this->session->userdata('attributes'));
						$this->session->set_userdata(array(
							'selected_attribute'	=> array('name' => $attr['Name'],
															 'id'	=> $attr['idAttribute']),
							'search_btn_enable'		=> 'href="database_result"',
							'selection_descriptor'	=> $attr['Name']
						));
						$this->session->userdata('btn_style')['search'] = 'primary';
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
            'subtypes'				=> '',
            'selected_subtype'		=> array('name' => '', 'id' => ''),
			'entities'				=> '',
			'selected_entity'		=> array('name' => '', 'id' => ''),
			'attributes'			=> '',
			'selected_attribute'	=> array('name' => '', 'id' => ''),
            'selected_attribute'	=> '',
			'selection_descriptor'	=> '',
			'btn_style'				=> array(
				'clinical' => '', 'gait' => '', 'search' => ''
			),
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
        } else if ($this->session->userdata('selected_attribute')['name'] == ''
					|| $this->session->userdata('selected_attribute') == NULL){
            $this->session->set_flashdata('user_warning', 'A valid data item must'
				. ' be selected to return a result.');
            redirect('database');
		}


        // get search result data
        $selected_attribute_ID	= $this->session->userdata('selected_attribute')['id'];
        $selected_attribute		= $this->session->userdata('selected_attribute')['name'];
		$results				= $this->eav_model->get_results($selected_attribute_ID);
        $patients				= $this->eav_model->get_patient();
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
