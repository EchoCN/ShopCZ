<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class brand_model extends CI_Model
{

    const TBL_BRAND = 'brand';//不需要前缀

    //添加商品品牌
    public function add_brand($data)
    {
    	
        return $this->db->insert(self::TBL_BRAND,$data);
    }

    //查询商品品牌
    public function list_brand()
    {
    	$query=$this->db->get(self::TBL_BRAND);
    	return $query->result_array();
    }
}
