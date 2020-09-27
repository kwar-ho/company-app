<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAll($sort_by, $sort_order, $search_by, $search_value)
	{
		$sort_by = isset($sort_by) ? $sort_by : 'id';		
		$sort_order = isset($sort_order) ? $sort_order : 'asc';		
		
		$this->db->select('e.id as employee_id, first_name, last_name, gender, date_of_birth, date_of_joining, department_id, d.dept_name');
		$this->db->from('employees e');
		$this->db->join('departments d', 'e.department_id = d.id');
		
		if($search_by != '' && $search_value != '')		
		$this->db->like($search_by, $search_value);
	
		$this->db->where('d.is_deleted', '0');
		$this->db->where('e.is_deleted', '0');
	
		$this->db->order_by($sort_by, $sort_order);
	
		$query = $this->db->get();				
		return $query->result_array();
	}
	
	public function get_by_field($field_name, $field_value)
	{
		$this->db->select('*');
		$this->db->from('employees e');		
		$this->db->where($field_name, $field_value);
		$this->db->where('is_deleted', '0');
		$query = $this->db->get();		
		return $query->result_array();
	}	
	
	public function get($id)
	{
		$this->db->select('e.id as employee_id, first_name, last_name, gender, date_of_birth, date_of_joining, department_id, d.dept_name, ecd.*');
		$this->db->from('employees e');
		$this->db->join('departments d', 'e.department_id = d.id', 'inner');
		$this->db->join('emp_contact_details ecd', 'e.id = ecd.emp_id', 'left');
		$this->db->where('e.id', $id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function add($data)
	{
		$this->db->insert('employees', $data);
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	}
	
	public function add_contact_details($data)
	{
		$this->db->insert('emp_contact_details', $data);
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	}
	
	public function update($emp_id, $employee_data, $contact_details)
	{
		$this->db->trans_begin();		
		
		$this->db->where('id', $emp_id);
		$this->db->update('employees', $employee_data);

		$this->db->where('emp_id', $emp_id);
		$this->db->update('emp_contact_details', $contact_details);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
	
	public function delete_record($id)
	{
		$this->db->where('id', $id);
		$this->db->update('employees', array('is_deleted' => 1));
		return $this->db->affected_rows();
	}
	
}