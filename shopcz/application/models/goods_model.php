<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class goods_model extends CI_Model
{
	const TBL_GOODS = 'goods';

	//添加
	public function add_goods($data)
	{
		$query = $this->db->insert(self::TBL_GOODS,$data);
		return $query ? $this->db->insert_id() : false;
	}

	public function best_goods()
    {
        $condition['is_best'] = 1;
        $query = $this->db->where($condition)->get(TBL_GOODS);
        return $query->result_array();
    }
}