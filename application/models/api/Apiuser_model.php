<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Apiuser_model extends CI_Model {

	public function fetchUser($email)
	{
		$this->db->select('*');
		$this->db->from('api_user');
		$this->db->where('username',$email);
		$result = $this->db->get();
		
		$result = $result->result_array();
		return $result;
	}
}
