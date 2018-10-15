<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class login_model extends CI_Model{

	const TBL_ADMIN = 'admin';

	public function get_admin($admin_name,$password){
        $condition = array(
            'admin_name' => $admin_name,
            'password' => $password
        );

        $query = $this->db->where($condition)->get(self::TBL_ADMIN);

        return $query->num_rows() > 0 ? true : false;
    }
    public function reset_password($admin,$pw)
    {
        $sql = "UPDATE cz_admin SET password = '{$pw}' WHERE admin_name = '{$admin}'";

        $res = $this->db->query($sql);
        var_dump($res);
    }
}