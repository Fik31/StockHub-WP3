<?php

class Model_auth extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}


	public function check_email($email)
	{
		if ($email) {
			$sql = 'SELECT * FROM users WHERE email = ?';
			$query = $this->db->query($sql, array($email));
			$result = $query->num_rows();
			return ($result == 1) ? true : false;
		}

		return false;
	}


	public function login($email, $password)
	{
		if ($email && $password) {
			$sql = "SELECT * FROM users WHERE email = ?";
			$query = $this->db->query($sql, array($email));

			if ($query->num_rows() == 1) {
				$result = $query->row_array();

				$hash_password = password_verify($password, $result['password']);
				if ($hash_password === true) {
					return $result;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}
}
