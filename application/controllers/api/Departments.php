<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';

class Departments extends RestController {

	/**
	 * Departments 
	 */
	 
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->model(array('api/departments_model'));
    }
	
	public function department_check($value)
	{
		$result = $this->departments_model->get_by_field('dept_name', $value);
		if (!empty($result))
		{
				$this->form_validation->set_message('department_check', 'The {field} already exists');
				return FALSE;
		}
		else
		{
				return TRUE;
		}
	}
	
	public function department_check_edit($value)
	{
		$dept_id = $this->input->post('dept_id');
		$result = $this->departments_model->get_by_field('id', $dept_id);
		if (!empty($result))
		{
			$result1 = $this->departments_model->get_by_field('dept_name', $value);			
			if($result[0]['dept_name'] != $value && !empty($result1))
			{				
				$this->form_validation->set_message('department_check_edit', 'The {field} already exists');
				return FALSE;
			}
		}
		return TRUE;
	}
	
	public function list_post()
	{		
		$this->form_validation->set_rules('sort_by', 'Sort by', 'trim|in_list[dept_name]');				
		$this->form_validation->set_rules('sort_order', 'Sort order', 'trim|in_list[asc,desc]');				
		$this->form_validation->set_rules('search_by', 'Search by', 'trim|in_list[dept_name]');				
		$this->form_validation->set_rules('search_value', 'Search value', 'trim|min_length[3]|max_length[30]');				
		if ($this->form_validation->run() == TRUE)
		{			
			$sort_by = $this->input->post('sort_by');
			$sort_order = $this->input->post('sort_order');
			$search_by = $this->input->post('search_by');
			$search_value = $this->input->post('search_value');
			$result = $this->departments_model->getAll($sort_by, $sort_order, $search_by, $search_value);
			if(!empty($result))
			{
				$this->response(['status' => true, 'data' => $result], 200);
			}
			else
			{
				$this->response(['status' => false, 'message' => 'No deparments found'], 200);
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
		$this->form_validation->set_rules('dept_name', 'Department name', 'required|min_length[2]|max_length[10]|callback_department_check');		
		if ($this->form_validation->run() == TRUE)
		{
			$dept_name = $this->input->post('dept_name');
			$data = array(
				'dept_name' => $dept_name			
			);
			
			$result = $this->departments_model->add($data);
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
			$msg = $this->form_validation->error_array();
			$this->response(['status' => false, 'message' => $msg], 200);			
		}
	}
	
	public function edit_post()
	{		
		$this->form_validation->set_rules('dept_id', 'Department id', 'trim|required|numeric');				
		if ($this->form_validation->run() == TRUE)
		{
			$dept_id = $this->input->post('dept_id');
			$result = $this->departments_model->get($dept_id);
			if(!empty($result))
			{
				$this->response(['status' => true, 'data' => $result], 200);
			}
			else
			{
				$this->response(['status' => false, 'message' => 'Department not found'], 200);
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
		$this->form_validation->set_rules('dept_id', 'Department id', 'required');		
		$this->form_validation->set_rules('dept_name', 'Department name', 'required|min_length[2]|max_length[10]|callback_department_check_edit');		
		if ($this->form_validation->run() == TRUE)
		{
			$dept_id = $this->input->post('dept_id');
			$dept_name = $this->input->post('dept_name');
			$data = array(
				'dept_name' => $dept_name,
				'updated_timestamp' => date('Y-m-d H:i:s')
			);
			
			$result = $this->departments_model->get($dept_id);
			if ($result)
			{
				$result = $this->departments_model->update($dept_id, $data);
				if ($result)
				{
					$this->response(['status' => true, 'message' => 'Updated successfully'], 200);
				}
				else
				{
					$this->response(['status' => false, 'message' => 'Failed to add, try again'], 200);
				}
			}
			else
			{
				$this->response(['status' => false, 'message' => 'Department not found'], 200);
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
		$this->form_validation->set_rules('dept_id', 'Department id', 'required');				
		if ($this->form_validation->run() == TRUE)
		{
			$dept_id = $this->input->post('dept_id');
			$result = $this->departments_model->get($dept_id);
			if ($result)
			{
				$result = $this->departments_model->delete_record($dept_id);
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
				$this->response(['status' => false, 'message' => 'Department not found'], 200);
			}
		}
		else
		{
			$msg = $this->form_validation->error_array();
			$this->response(['status' => false, 'message' => $msg], 200);			
		}
	}
}
