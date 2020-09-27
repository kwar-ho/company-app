<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';

class Employees extends RestController {

	/**
	 * Departments 
	 */
	 
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->model(array('api/employees_model'));
    }
	
	public function email_check($value)
	{
		$result = $this->employees_model->get_by_field('office_email', $value);
		if (!empty($result))
		{
				$this->form_validation->set_message('email_check', 'The {field} already exists');
				return FALSE;
		}
		else
		{
				return TRUE;
		}
	}
	
	public function email_check_edit($value)
	{
		$emp_id = $this->input->post('emp_id');
		$result = $this->employees_model->get_by_field('id', $emp_id);
		if (!empty($result))
		{
			$result1 = $this->employees_model->get_by_field('office_email', $value);			
			if($result[0]['office_email'] != $value && !empty($result1))
			{				
				$this->form_validation->set_message('email_check_edit', 'The {field} already exists');
				return FALSE;
			}
		}
		return TRUE;
	}
	
	public function mobile_check($value)
	{
		$result = $this->employees_model->get_by_field('primary_mobile', $value);
		if (!empty($result))
		{
				$this->form_validation->set_message('mobile_check', 'The {field} already exists');
				return FALSE;
		}
		else
		{
				return TRUE;
		}
	}
	
	public function mobile_check_edit($value)
	{
		$emp_id = $this->input->post('emp_id');
		$result = $this->employees_model->get_by_field('id', $emp_id);
		if (!empty($result))
		{
			$result1 = $this->employees_model->get_by_field('primary_mobile', $value);			
			if($result[0]['primary_mobile'] != $value && !empty($result1))
			{
				$this->form_validation->set_message('mobile_check_edit', 'The {field} already exists');
				return FALSE;
			}
		}
		return TRUE;
	}
	
	public function list_post()
	{		
		$this->form_validation->set_rules('sort_by', 'Sort by', 'trim|in_list[id,first_name,date_of_joining]');				
		$this->form_validation->set_rules('sort_order', 'Sort order', 'trim|in_list[asc,desc]');				
		$this->form_validation->set_rules('search_by', 'Search by', 'trim|in_list[first_name,office_email,primary_mobile]');				
		$this->form_validation->set_rules('search_value', 'Search value', 'trim|min_length[3]|max_length[30]');				
		if ($this->form_validation->run() == TRUE)
		{			
			$sort_by = $this->input->post('sort_by');
			$sort_order = $this->input->post('sort_order');
			$search_by = $this->input->post('search_by');
			$search_value = $this->input->post('search_value');
			$result = $this->employees_model->getAll($sort_by, $sort_order, $search_by, $search_value);
			if(!empty($result))
			{
				$this->response(['status' => true, 'data' => $result], 200);
			}
			else
			{
				$this->response(['status' => false, 'message' => 'No employees found'], 200);
			}
		}
		else
		{
			$msg = $this->form_validation->error_array();
			$this->response(['status' => false, 'message' => $msg], 200);			
		}		
	}
	
	public function add_post()
	{		
		$this->form_validation->set_rules('first_name', 'First name', 'trim|required|min_length[3]|max_length[20]');		
		$this->form_validation->set_rules('last_name', 'Lst name', 'trim|required|min_length[3]|max_length[20]');		
		$this->form_validation->set_rules('gender', 'Gender', 'required|in_list[M,F]');		
		$this->form_validation->set_rules('date_of_birth', 'Date of birth', 'required|min_length[2]|max_length[10]');		
		$this->form_validation->set_rules('date_of_joining', 'Date of joining', 'min_length[2]|max_length[10]');		
		$this->form_validation->set_rules('department', 'Department', 'required|numeric');	
		$this->form_validation->set_rules('office_email', 'Office email', 'required|valid_email|max_length[30]|callback_email_check');
		$this->form_validation->set_rules('primary_mobile', 'Primary mobile', 'required|numeric|min_length[10]|max_length[15]|callback_mobile_check');		
		
		$this->form_validation->set_rules('personal_email', 'Personal email', 'valid_email|max_length[30]');	
		$this->form_validation->set_rules('alternate_mobile', 'Alternate mobile', 'numeric|min_length[10]|max_length[15]');
		$this->form_validation->set_rules('office_mobile', 'Office mobile', 'numeric|min_length[10]|max_length[15]');		
		$this->form_validation->set_rules('emergency_person', 'Emergency person', 'min_length[3]|max_length[20]');	
		$this->form_validation->set_rules('emergency_person_contact_no', 'Emergency person contact no', 'numeric|min_length[10]|max_length[15]');		
		$this->form_validation->set_rules('current_address', 'Current address', 'required|min_length[10]|max_length[200]');	
		$this->form_validation->set_rules('current_address_state', 'Current address state', 'required|min_length[3]|max_length[15]');	
		$this->form_validation->set_rules('current_address_city', 'Current address city', 'required|min_length[3]|max_length[15]');	
		$this->form_validation->set_rules('current_address_zipcode', 'Current address zipcode', 'required|min_length[5]|max_length[15]');	
		$this->form_validation->set_rules('permanent_address', 'Permanent address', 'min_length[10]|max_length[200]');	
		$this->form_validation->set_rules('permanent_address_state', 'Permanent address state', 'min_length[3]|max_length[15]');	
		$this->form_validation->set_rules('permanent_address_city', 'Permanent address city', 'min_length[3]|max_length[15]');	
		$this->form_validation->set_rules('permanent_address_zipcode', 'Permanent address zipcode', 'min_length[5]|max_length[15]');	
		
		if ($this->form_validation->run() == TRUE)
		{
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			$gender = $this->input->post('gender');
			$date_of_birth = $this->input->post('date_of_birth');
			$date_of_joining = $this->input->post('date_of_joining');			
			$department = $this->input->post('department');
			$office_email = $this->input->post('office_email');
			$primary_mobile = $this->input->post('primary_mobile');
			
			$personal_email = $this->input->post('personal_email');
			$alternate_mobile = $this->input->post('alternate_mobile');
			$office_mobile = $this->input->post('office_mobile');			
			$emergency_person = $this->input->post('emergency_person');
			$emergency_person_contact_no = $this->input->post('emergency_person_contact_no');			
			$current_address = $this->input->post('current_address');
			$current_address_state = $this->input->post('current_address_state');
			$current_address_city = $this->input->post('current_address_city');
			$current_address_zipcode = $this->input->post('current_address_zipcode');
			$permanent_address = $this->input->post('permanent_address');
			$permanent_address_state = $this->input->post('permanent_address_state');
			$permanent_address_city = $this->input->post('permanent_address_city');
			$permanent_address_zipcode = $this->input->post('permanent_address_zipcode');
			
			$employee_data = array(
				'first_name' => $first_name,
				'last_name' => $last_name,
				'gender' => $gender,
				'date_of_birth' => $date_of_birth,
				'date_of_joining' => $date_of_joining,
				'department_id' => $department,
				'office_email' => $office_email,
				'primary_mobile' => $primary_mobile
			);
			
			$contact_details = array(
				'personal_email' => $personal_email,				
				'alternate_mobile' => $alternate_mobile,
				'office_mobile' => $office_mobile,
				'emergency_person' => $emergency_person,
				'emergency_person_contact_no' => $emergency_person_contact_no,				
				'current_address' => $current_address,
				'current_address_state' => $current_address_state,
				'current_address_city' => $current_address_city,
				'current_address_zipcode' => $current_address_zipcode,
				'permanent_address' => $permanent_address,
				'permanent_address_state' => $permanent_address_state,
				'permanent_address_city' => $permanent_address_city,
				'permanent_address_zipcode' => $permanent_address_zipcode
			);
			
			$result = $this->employees_model->add($employee_data);
			if($result)
			{
				$contact_details['emp_id'] = $result;
				$result = $this->employees_model->add_contact_details($contact_details);
				if ($result)
				{
					$this->response(['status' => true, 'message' => 'Added successfully'], 200);
				}
				else
				{
					$this->response(['status' => false, 'message' => 'Failed to add, try again'], 200);
				}
			}
			else
			{
				$this->response(['status' => false, 'message' => 'Failed to add, try again'], 200);
			}		
		}
		else
		{
			$msg = $this->form_validation->error_array();
			$this->response(['status' => false, 'message' => $msg], 200);			
		}
	}
	
	public function edit_post()
	{		
		$this->form_validation->set_rules('emp_id', 'Employee id', 'trim|required|numeric');				
		if ($this->form_validation->run() == TRUE)
		{
			$emp_id = $this->input->post('emp_id');
			$result = $this->employees_model->get($emp_id);
			if(!empty($result))
			{
				$this->response(['status' => true, 'data' => $result], 200);
			}
			else
			{
				$this->response(['status' => false, 'message' => 'Employee not found'], 200);
			}
		}
		else
		{
			$msg = $this->form_validation->error_array();
			$this->response(['status' => false, 'message' => $msg], 200);			
		}
	}
	
	public function update_post()
	{		
		$this->form_validation->set_rules('emp_id', 'Employee id', 'trim|required|numeric');		
		$this->form_validation->set_rules('first_name', 'First name', 'trim|required|min_length[3]|max_length[20]');		
		$this->form_validation->set_rules('last_name', 'Lst name', 'trim|required|min_length[3]|max_length[20]');		
		$this->form_validation->set_rules('gender', 'Gender', 'required|in_list[M,F]');		
		$this->form_validation->set_rules('date_of_birth', 'Date of birth', 'required|min_length[2]|max_length[10]');		
		$this->form_validation->set_rules('date_of_joining', 'Date of joining', 'min_length[2]|max_length[10]');		
		$this->form_validation->set_rules('department', 'Department', 'required|numeric');	
		$this->form_validation->set_rules('office_email', 'Office email', 'required|valid_email|max_length[30]|callback_email_check_edit');
		$this->form_validation->set_rules('primary_mobile', 'Primary mobile', 'required|numeric|min_length[10]|max_length[15]|callback_mobile_check_edit');		
		
		$this->form_validation->set_rules('personal_email', 'Personal email', 'valid_email|max_length[30]');	
		$this->form_validation->set_rules('alternate_mobile', 'Alternate mobile', 'numeric|min_length[10]|max_length[15]');
		$this->form_validation->set_rules('office_mobile', 'Office mobile', 'numeric|min_length[10]|max_length[15]');		
		$this->form_validation->set_rules('emergency_person', 'Emergency person', 'min_length[3]|max_length[20]');	
		$this->form_validation->set_rules('emergency_person_contact_no', 'Emergency person contact no', 'numeric|min_length[10]|max_length[15]');		
		$this->form_validation->set_rules('current_address', 'Current address', 'required|min_length[10]|max_length[200]');	
		$this->form_validation->set_rules('current_address_state', 'Current address state', 'required|min_length[3]|max_length[15]');	
		$this->form_validation->set_rules('current_address_city', 'Current address city', 'required|min_length[3]|max_length[15]');	
		$this->form_validation->set_rules('current_address_zipcode', 'Current address zipcode', 'required|min_length[5]|max_length[15]');	
		$this->form_validation->set_rules('permanent_address', 'Permanent address', 'min_length[10]|max_length[200]');	
		$this->form_validation->set_rules('permanent_address_state', 'Permanent address state', 'min_length[3]|max_length[15]');	
		$this->form_validation->set_rules('permanent_address_city', 'Permanent address city', 'min_length[3]|max_length[15]');	
		$this->form_validation->set_rules('permanent_address_zipcode', 'Permanent address zipcode', 'min_length[5]|max_length[15]');	
		
		if ($this->form_validation->run() == TRUE)
		{
			$emp_id = $this->input->post('emp_id');
			
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			$gender = $this->input->post('gender');
			$date_of_birth = $this->input->post('date_of_birth');
			$date_of_joining = $this->input->post('date_of_joining');			
			$department = $this->input->post('department');
			$office_email = $this->input->post('office_email');
			$primary_mobile = $this->input->post('primary_mobile');
			
			$personal_email = $this->input->post('personal_email');
			$alternate_mobile = $this->input->post('alternate_mobile');
			$office_mobile = $this->input->post('office_mobile');			
			$emergency_person = $this->input->post('emergency_person');
			$emergency_person_contact_no = $this->input->post('emergency_person_contact_no');			
			$current_address = $this->input->post('current_address');
			$current_address_state = $this->input->post('current_address_state');
			$current_address_city = $this->input->post('current_address_city');
			$current_address_zipcode = $this->input->post('current_address_zipcode');
			$permanent_address = $this->input->post('permanent_address');
			$permanent_address_state = $this->input->post('permanent_address_state');
			$permanent_address_city = $this->input->post('permanent_address_city');
			$permanent_address_zipcode = $this->input->post('permanent_address_zipcode');
			
			$employee_data = array(
				'first_name' => $first_name,
				'last_name' => $last_name,
				'gender' => $gender,
				'date_of_birth' => $date_of_birth,
				'date_of_joining' => $date_of_joining,
				'department_id' => $department,
				'office_email' => $office_email,
				'primary_mobile' => $primary_mobile,
				'updated_timestamp' => date('Y-m-d H:i:s')
			);
			
			$contact_details = array(
				'personal_email' => $personal_email,				
				'alternate_mobile' => $alternate_mobile,
				'office_mobile' => $office_mobile,
				'emergency_person' => $emergency_person,
				'emergency_person_contact_no' => $emergency_person_contact_no,				
				'current_address' => $current_address,
				'current_address_state' => $current_address_state,
				'current_address_city' => $current_address_city,
				'current_address_zipcode' => $current_address_zipcode,
				'permanent_address' => $permanent_address,
				'permanent_address_state' => $permanent_address_state,
				'permanent_address_city' => $permanent_address_city,
				'permanent_address_zipcode' => $permanent_address_zipcode,
				'updated_timestamp' => date('Y-m-d H:i:s')
			);
			
			$result = $this->employees_model->get($emp_id);
			if(!empty($result))
			{				
				$result = $this->employees_model->update($emp_id, $employee_data, $contact_details);				
				if ($result)
				{
					$this->response(['status' => true, 'message' => 'Updated successfully'], 200);
				}
				else
				{
					$this->response(['status' => false, 'message' => 'Failed to update, try again'], 200);
				}
			}
			else
			{
				$this->response(['status' => false, 'message' => 'Employee not found'], 200);
			}		
		}
		else
		{
			$msg = $this->form_validation->error_array();
			$this->response(['status' => false, 'message' => $msg], 200);			
		}
	}
	
	public function delete_post()
	{		
		$this->form_validation->set_rules('emp_id', 'Employee id', 'trim|required|numeric');				
		if ($this->form_validation->run() == TRUE)
		{
			$emp_id = $this->input->post('emp_id');
			$result = $this->employees_model->get($emp_id);
			if(!empty($result))
			{
				$result = $this->employees_model->delete_record($emp_id);
				if ($result)
				{
					$this->response(['status' => true, 'message' => 'Deleted successfully'], 200);
				}
				else
				{
					$this->response(['status' => false, 'message' => 'Failed to delete, try again'], 200);
				}
			}
			else
			{
				$this->response(['status' => false, 'message' => 'Employee not found'], 200);
			}
		}
		else
		{
			$msg = $this->form_validation->error_array();
			$this->response(['status' => false, 'message' => $msg], 200);			
		}
	}
}
