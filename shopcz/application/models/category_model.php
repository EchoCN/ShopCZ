<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//商品类模型
class category_model extends CI_Model
{
    const TBL_CATE = "category";

    public function list_cate($pid=0){

        $query = $this->db->get(self::TBL_CATE);

        $cates = $query->result_array();

        //对类别进行重组，并返回
        return $this->_tree($cates,$pid);
    }

//arr要遍历的数组，Pid节点的pid
    private function _tree($arr , $pid = 0,$level = 0)
    {
        static $tree = array(

        );//保存排序的结果

        foreach ($arr as $v)
        {
            if($v['parent_id'] == $pid)
            {
                $v['level'] = $level;

                $tree[] = $v;

                $this->_tree($arr,$v['cat_id'],$level+1);
            }
        }
        return $tree;
    }

    public function add_cat($data)
    {
        $result = $this->db->insert('cz_category',$data);

        var_dump($result);
    }

    public function child($arr,$pid = 0){
        $child = array();
        foreach ($arr as $k => $v) {
            if ($v['parent_id'] == $pid){
                $child[] = $v;
            }
        }
        return $child;
    }


    public function cate_list($arr,$pid = 0){
       
        $child = $this->child($arr,$pid);
        
        if (empty($child)){
            return null;
        }
       
        foreach ($child as $k => $v) {
            $current_child = $this->cate_list($arr,$v['cat_id']);
            if ($current_child != null){
                
                $child[$k]['child'] = $current_child;
            }
        }
        return $child;
}

    public function front_cate(){
        $query = $this->db->get(self::TBL_CATE);
        $cates = $query->result_array();
        return $this->cate_list($cates);
    }
}
