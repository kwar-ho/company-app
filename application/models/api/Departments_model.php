<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departments_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAll($sort_by, $sort_order, $search_by, $search_value)
	{
		$sort_by = isset($sort_by) ? $sort_by : 'id';		
		$sort_order = isset($sort_order) ? $sort_order : 'asc';		
		
		$this->db->select('id, dept_name');
		$this->db->from('departments d');		
		
		if($search_by != '' && $search_value != '')		
		$this->db->like($search_by, $search_value);
	
		$this->db->where('is_deleted', '0');
	
		$this->db->order_by($sort_by, $sort_order);
	
		$query = $this->db->get();				
		return $query->result_array();
	}
	
	public function get_by_field($field_name, $field_value)
	{
		$this->db->select('*');
		$this->db->from('departments d');		
		$this->db->where($field_name, $field_value);
		$this->db->where('is_deleted', '0');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function add($data)
	{
		$this->db->insert('departments', $data);
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	}
	
	public function get($id)
	{
		$this->db->select('id, dept_name');
		$this->db->where('id', $id);
		$query = $this->db->get('departments');		
		return $query->result_array();
	}
	
	public function update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('departments', $data);		
		return $this->db->affected_rows();
	}
	
	public function delete_record($id)
	{		
		$this->db->where('id', $id);
		$this->db->update('departments', array('is_deleted' => 1));
		return $this->db->affected_rows();
	}
	
}