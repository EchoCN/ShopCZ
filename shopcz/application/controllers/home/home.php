<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//前台控制器
class home extends Home_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('category_model');
		$this->load->model('goods_model');
	}

	public function index()
	{
		$data['cates'] = $this->category_model->front_cate();
		$data['best_goods'] = $this->goods_model->best_goods();
		$this->load->view('index.html',$data);
	}
}