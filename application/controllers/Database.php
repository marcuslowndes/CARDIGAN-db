<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Database extends CI_Controller {

	public function index($type = NULL, $subtypeID = NULL,
	 					  $entityID = NULL, $attributeID = NULL) {

        // ensure user is logged in and is verified
        $this->check_verified_user();


        // get current form data
        $data = array(
            'title'					=> 'DATABASE',
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
		foreach (array('clinical', 'gait', 'search') as $btn)
			if ($data[$btn . '_btn_style'] == '')
				$data[$btn . '_btn_style'] = 'secondary';


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


	public function check_verified_user(){
		if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('user_failed', 'Only a verified'
				. ' user may access the database.');
            redirect('login');
        } else if($this->session->userdata('user_type') == 'Unverified'){
            $this->session->set_flashdata('user_warning', 'Only a verified'
				. ' user may access the database. Please <a href="contact">'
				. 'contact an admin</a> to request to be verified.');
            redirect('welcome');
        }
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
					'clinical_btn_style' => 'danger',
					'gait_btn_style'	 => 'secondary'
				));
			else
				$this->session->set_userdata(array(
					'clinical_btn_style' => 'secondary',
					'gait_btn_style' 	 => 'danger'
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
								'search_btn_style'		=> 'success',
								'search_btn_enable'		=> 'href="database_result"'
		                    ));
							$this->add_to_selected();
		                }
					} elseif (count($this->session->userdata('attributes')) == 1) {
						$attr = current($this->session->userdata('attributes'));
						$this->session->set_userdata(array(
							'selected_attribute_ID'	=> $attr['idAttribute'],
							'selected_attribute'	=> $attr['Name'],
							'search_btn_style'		=> 'success',
							'search_btn_enable'		=> 'href="database_result"'
						));
						$this->add_to_selected();
					} else {
						$this->session->set_userdata(array(
							'selected_attribute_ID'	=> '',
							'selected_attribute'	=> '',
							'search_btn_style'		=> '',
							'search_btn_enable'		=> 'disabled'
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


	public function remove_from_selected($attributeID, $subtypeID){
        $this->check_verified_user();

		if ($this->session->userdata('all_selected') === NULL)
			$this->session->set_userdata(array('all_selected' => array()));

		$all_selected = array();
		foreach ($this->session->userdata('all_selected') as $selected)
			if ($selected['attribute_ID'] != $attributeID
					|| $selected['subtype_ID'] != $subtypeID)
				array_push($all_selected, $selected);


		$this->session->set_userdata(array('all_selected' => $all_selected));

		redirect('database');
	}


	/* Unsets form-related session data */
	public function reset_form(){
        $this->check_verified_user();

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
			'search_btn_enable'		=> 'disabled',
			'all_selected'			=> array()
        ));

        redirect("database");
    }


	/* Gives all the search results in a table */
    public function result(){

        // ensure user is logged in and is verified, and a valid result has been selected
		$this->check_verified_user();
		if (count($this->session->userdata('all_selected')) == 0
				|| $this->session->userdata('all_selected') == NULL){
            $this->session->set_flashdata('user_warning', 'A valid data item must'
				. ' be selected to return a result.');
            redirect('database');
		}

		$data['all_results'] = $this->get_results($this->session->userdata('all_selected'));
		$data['visits'] = $this->eav_model->get_visitation();
		$data['title'] = 'RESULTS';

		//load views
        $this->load->view('templates/header', $data);
        $this->load->view('database/result', $data);
        $this->load->view('templates/footer.html');
    }


	public function get_results($db_results){
		$all_results = array();
		foreach ($db_results as $selected)
			array_push($all_results, $this->eav_model->get_results(
				$selected['attribute_ID'], $selected['subtype_ID']
			));
		$patients = $this->eav_model->get_patient();


		// reformat results for the view
		// preformatted_results gives a new results array arranged as:
			//  <idPatient> => array(
			//		<idData_Type> => array(
			//			<idAttribute> => array(
			// 					'Patient'		=> array(...<database row>),
			// 					'Attribute' 	=> array(...<database row>),
			// 					'Visitation' 	=> array(...<database row>),
			// 					'Value' 		=> array(...<database row>),
			// 					'Data_Type'		=> array(...<database row>)
			// 				)
			//			... (each attribute that the patient has a result for)
			//		) ... (for each data type)
			//  ) ... (for every patient)
		$preformatted_results = array();
		foreach($patients as $patient) {
			$preformatted_results[$patient['Patient_ID']] = array();

			foreach ($all_results as $results)
				foreach($results as $result) {
					if (!isset($preformatted_results[$patient['Patient_ID']]
							[$result['Data_Type']['idData_Type']]))
						$preformatted_results[$patient['Patient_ID']]
							[$result['Data_Type']['idData_Type']] = array();

					if (!isset($preformatted_results[$patient['Patient_ID']]
							[$result['Data_Type']['idData_Type']]
							[$result['Attribute']['idAttribute']]))
						$preformatted_results[$patient['Patient_ID']]
							[$result['Data_Type']['idData_Type']]
							[$result['Attribute']['idAttribute']] = array();

					if ($patient['idPatient'] == $result['Patient']['idPatient'])
						array_push($preformatted_results[$patient['Patient_ID']]
							[$result['Data_Type']['idData_Type']]
							[$result['Attribute']['idAttribute']], $result);
				}
		}

		// formatted_results gives a new results array arranged as:
			//  <idPatient> => array(
			// 		<idData_Type> => array(
			//			<idAttribute> => array(
			//				'Attribute' => array(...<database row>)
			//				'Data_Type' => array(...<database row>)
			//		 		'Values' => array(
			//					<idVisitation> => array(...<all values>)
			//					... (for every visit 1 - 8)
			//		 		)
			//			) ... (for every selected attribute)
			//		)
			// 	) ... (for every patient)
		// this ensures only relevant data is passed to the view, in the required order
		$formatted_results = array();
		foreach($preformatted_results as $patient => $attr_per_data_type)
			foreach($attr_per_data_type as $data_type => $results_per_attr) {
				foreach ($results_per_attr as $attribute => $results) {
					foreach ($results as $result) {
						$formatted_results[$patient][$data_type][$attribute]
							['Data_Type']['Type'] = $result['Data_Type']['Type'];
						$formatted_results[$patient][$data_type][$attribute]['Data_Type']['Walk_Type']
							= $result['Data_Type']['Walk_Type'];
						$formatted_results[$patient][$data_type][$attribute]['Data_Type']['Subtype']
							= $result['Data_Type']['Subtype'];

						$formatted_results[$patient][$data_type][$attribute]['Attribute']
							= $result['Attribute'];

						if (!isset($formatted_results[$patient][$data_type][$attribute]['Values']
								[$result['Visitation']['idVisitation']]))
							$formatted_results[$patient][$data_type][$attribute]['Values']
								[$result['Visitation']['idVisitation']] = array();

						array_push($formatted_results[$patient][$data_type][$attribute]['Values']
							[$result['Visitation']['idVisitation']], $result['Value']['Value']);
					}
				}
			}

		return $formatted_results;
	}


	public function export_from_selected($attributeID, $subtypeID){
        $this->check_verified_user();

		if ($this->session->userdata('all_selected') === NULL)
			$this->session->set_userdata(array('all_selected' => array()));
		if (empty($this->session->userdata('all_selected')))
			redirect('database');

		foreach ($this->session->userdata('all_selected') as $selected)
			if ($selected['attribute_ID'] == $attributeID
					&& $selected['subtype_ID'] == $subtypeID) {
				$attr_name = str_replace(
					array(' ', '(', ')', '\\', '/'),
					array('_', '',  '',  '_',  '_'),
					$selected['attribute']
				);
				$results = $this->get_results(array($selected));
			}

		// set file name and type
		$filename = 'results_' . $attr_name . '_' . date('dmY') . '.csv';
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename=' . $filename);
		header('Content-Type: application/csv; ');

		// file creation
		$file = fopen('php://output','w');

		// write header to file
		$header = array('Patient ID');
		$visits = $this->eav_model->get_visitation();
		foreach ($visits as $value)
			array_push($header, "Visit " . $value['idVisitation']);
		fputcsv($file, $header);

		// write data values to file
		foreach ($results as $patient => $attr_per_data_type) {
			$line = array($patient);

			foreach ($attr_per_data_type as $attributes)
			foreach ($attributes as $results_per_attr)
			if (isset($results_per_attr['Attribute']['Name']))
			foreach($visits as $visitID => $visit) {
				$line[$visitID + 1] = '';
				foreach($results_per_attr['Values'] as $visit_id => $results)
				foreach ($results as $value) {
					if ($visit['idVisitation'] == $visit_id)
						$line[$visitID + 1] = $line[$visitID + 1] . ' ' . $value;
				}
			}

			fputcsv($file, $line);
		}

		// close file and send
		fclose($file);
	}

}
