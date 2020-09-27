<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{	
	function __construct()
	{
		$this->ci =& get_instance();		
		$this->ci->load->database();
        $this->ci->load->model(array('api/Apiuser_model'));		
	}

	public function api_auth($user, $pass)
	{		
		$flag = false;

		if($user != '' && $pass != '')
		{
			$user = $this->ci->Apiuser_model->fetchUser($user);
			if(!empty($user))
			{
				$pwd = sha1($pass);
				if($user[0]['password'] == $pwd)
				{
					if($user[0]['status'] == 1)
					{
						$flag = true;	
					}
					else
					{
						$flag = false;
					}
					
				}	
			}
		}
		return $flag;
	}
	
}