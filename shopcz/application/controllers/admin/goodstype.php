<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class goodstype extends Admin_Controller
{
   public function __construct()
    {
        parent::__construct();
        $this->load->model('goodstype_model');
        $this->load->library('pagination');
    }

	public function index($offset = '')
	{

		//配置分页信息
		$config['base_url'] = site_url('admin/goodstype/index');
		$config['total_rows'] = $this->goodstype_model->count_goodstype();
		$config['per_page'] = 2;
		$config['uri_segment'] = 4;


		//自定义分页链接
		$config['first_link'] = '首页';
		$config['last_link'] = '尾页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';

		//初始化分页
		$this->pagination->initialize($config);

		//生成分页信息
		$data['pageinfo'] = $this->pagination->create_links();
		$limit = $config['per_page'];

		//展示数据
		$data['types']=  $this->goodstype_model->list_goods_type($limit,$offset);

		$this->load->view('goods_type_list.html',$data);
	}

	public function edit()
	{
		$this->load->view('goods_type_edit.html');
	}

	public function add()
	{
		$this->load->view('goods_type_add.html');
	}

	public function insert()
	{
	    $data['type_name'] = $this->input->post('type_name');

		if($this->goodstype_model->add_goods_type($data))
		{
			//添加成功
			$data['message'] = "添加成功";
            $data['wait'] = 3;
            $data['url'] = site_url('admin/goodstype/index');
            $this->load->view('message.html',$data);
		}
		else
		{
			//添加失败
			$data['message'] = "添加失败";
            $data['wait'] = 3;
            $data['url'] = site_url('admin/goodstype/add');
            $this->load->view('message.html',$data);
		}
	}

	public function edittype()
	{
		$data['type_name'] = $this->input->post('type_name');
		$data['type_id'] = $this->input->post('type_id');

		if($this->goodstype_model->edit_type($data))
		{
			//修改成功
			$data['message'] = "修改成功";
            $data['wait'] = 3;
            $data['url'] = site_url('admin/goodstype/index');
            $this->load->view('message.html',$data);
		}else
		{
		    //修改失败
			$data['message'] = "修改失败";
            $data['wait'] = 3;
            $data['url'] = site_url('admin/goodstype/edit');
            $this->load->view('message.html',$data);
		}
	}
}