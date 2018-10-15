<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class goods extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('goodstype_model');
		$this->load->model('attribute_model');
		$this->load->model('goods_model');
		$this->load->model('brand_model');
		$this->load->model('category_model');
	}


	public function index()
	{
		$this->load->view('goods_list.html');
	}

	public function add()
	{
		//获取所有的商品类型
		$data['goodstypes'] = $this->goodstype_model->get_all_types();
		$this->load->view('goods_add.html',$data);
	}

	public function edit()
	{
		$this->load->view('goods_edit.html');
	}

	public function create_attrs_html()
	{
		//获取类型id
		$type_id = $this->input->get('type_id');
		//echo $type_id;
		$attrs = $this->attribute_model->get_attrs($type_id);
		//根据获取到的属性构造html字符串
		$html = '';
		foreach ($attrs as $v) {
			$html .= "<tr>";
			$html .= "<td class='label'>".$v['attr_name']."</td>";
			$html .="<td>";
			$html .= "<input type='hidden' name='attr_id_list[]' value='".$v['attr_id']."'>";
			switch ($v['attr_input_type']) {
				case 0:
					# 文本框
					$html .= "<input name='attr_value_list[]'' type='text' size='40' value='".$v['default_value']."'>";
					break;
				case 1:
					# 下拉列表
					$arr = explode(PHP_EOL, $v['attr_value']);
					$html .= "<select name='attr_value_list[]'>";
					$html .= "<option value=''>请选择...</option>";
					foreach ($arr as $val) {
						$html .= "<option value='$val'";
						if ($val == $v['default_value']) {
							$html .= "selected";
						}
						$html .= ">$val</option>";
					}								  
					$html .= "</select>";
					break;
				case 2:
					# 文本域
					break;
				
				default:
					# code...
					break;
			}

			$html .="</td>";
			$html .="</tr>";
		}
		$data['attr_list'] = $html;

		$this->load->view('goods_edit.html',$data);
	}

	public function insert()
	{
		$data['goods_name'] = $this->input->post('goods_name');
		$data['goods_sn'] = $this->input->post('goods_sn');
		$data['cat_id'] = $this->input->post('cat_id');
		$data['brand_id'] = $this->input->post('brand_id');
		$data['shop_price'] = $this->input->post('shop_price');
		$data['promote_price'] = $this->input->post('promote_price');
		$data['promote_start_time'] = strtotime($this->input->post('promote_start_time'));
		$data['promote_end_time'] = strtotime($this->input->post('promote_end_time'));
		$data['goods_number'] = $this->input->post('goods_number');
		$data['goods_brief'] = $this->input->post('goods_brief');
		$data['is_best'] = $this->input->post('is_best');
		$data['is_new'] = $this->input->post('is_new');
		$data['is_hot'] = $this->input->post('is_hot');
		$data['is_onsale'] = $this->input->post('is_onsale');

		$config['upload_path'] =  './uploads/';
		$config['allowed_types']='gif|png|jpg|jpeg';
        $config['max_size'] = '100';
        $this->load->library('upload',$config);

        //图片上传
        if($this->upload->do_upload('goods_img'))
        {

        	$res = $this->upload->data();//获取图片信息
        	$data['goods_img'] = $res['file_name'];
        	$config_img['source_image'] = "./uploads/" . $res['file_name'];
        	$config_img['create_thumb'] = true;
        	$config_img['maintain_ratio'] = true;
        	$config_img['height'] = 160; 
        	$config_img['width'] = 160;

        	//载入初始化
        	$this->load->library('image_lib',$config_img);

        	if($this->image_lib->resize())
        	{
        		//缩略OK
        		$data['goods_thumb'] = $res['raw_name'] . $this->image_lib->thumb_marker .$res['file_ext'];
        		if($goods_id = $this->goods_model->add_goods($data))
        		{
        			$attr_ids = $this->input->post('attr_id_list');
        			$attr_values = $this->input->post('attr_value_list');
        			foreach ($attr_values as $k => $v) {
        				if(!empty($v)){
        				$data2['goods_id'] = $goods_id;
        				$data2['attr_id'] = $attr_ids[$k];
        				$data2['attr_value'] = $v;

        				$this->db->insert('goods_attr',$data2);
        			}
        		}
        		    $data['message'] = "添加成功";
                    $data['wait'] = 3;
                    $data['url'] = site_url('admin/goods/index');
                    $this->load->view('message.html',$data);

        		}else{
        			$data['message'] = "添加失败";
                    $data['wait'] = 3;
                    $data['url'] = site_url('admin/goods/add');
                    $this->load->view('message.html',$data);
        		}
        	}
        	else
        	{
        		$data['message'] = $this->image_lib->display_errors();
                $data['wait'] = 3;
                $data['url'] = site_url('admin/goods/add');
                $this->load->view('message.html',$data);
            }
        }else{
        	$data['message'] = $this->upload->display_errors();
            $data['wait'] = 3;
            $data['url'] = site_url('admin/goods/add');
            $this->load->view('message.html',$data);
        }
	}
}