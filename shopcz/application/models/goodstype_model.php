<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class goodstype_model extends CI_Model
{
	const TBL_GOODS_TYPE = 'goods_type';

	//添加商品类型
	public function add_goods_type($data)
	{
		return $this->db->insert(self::TBL_GOODS_TYPE,$data);
	}

	#获取所有商品类型
	public function get_all_types(){
		$query = $this->db->get(self::TBL_GOODS_TYPE);
		return $query->result_array();
	}

	//查询商品类型
	public function list_goods_type($limit,$offset)
	{
		$query = $this->db->limit($limit,$offset)->get(self::TBL_GOODS_TYPE);
		return $query->result_array();
	}

	//统计商品类型的总数
	public function count_goodstype()
	{
		return $this->db->count_all(self::TBL_GOODS_TYPE);
	}

	//修改商品类型
	public function edit_type($data)
	{
		$query = $this->db->update(self::TBL_GOODS_TYPE,$data);
	}
}