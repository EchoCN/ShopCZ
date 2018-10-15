<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute_model extends CI_Model
{
	const TBL_ATTR = 'attribute';

	public function add_attrs($data)
	{
		return $this->db->insert(self::TBL_ATTR,$data);
	}

	public function list_attrs()
	{
		$query = $this->db->get(self::TBL_ATTR);
		return $query->result_array();
	}

	//获取指定类型下面所有的属性
	public function get_attrs($type_id)
	{
		$condition['type_id'] = $type_id;
		$query = $this->db->where($condition)->get(self::TBL_ATTR);
		return $query->result_array();
	}
}