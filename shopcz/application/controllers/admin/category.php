<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class category extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
        $this->load->library('form_validation');

    }

    //首页
    public function index()
    {
        $data['cates'] = $this->category_model->list_cate();

        $this->load->view('cat_list.html',$data);
    }
    //添加
    public function add()
    {
        $data['cates'] = $this->category_model->list_cate();
        $this->load->view('cat_add.html',$data);
    }

    //编辑
    public function edit()
    {

        $this->load->view('cat_edit.html');
    }

    //完成添加分类动作
    public function insert()
    {
        //验证规则
        $this->form_validation->set_rules('cat_name','分类名称','trim|required');

        if($this->form_validation->run() == false)
        {
            //未通过
            $data['message'] = validation_errors();
            $data['wait'] = 3;
            $data['url'] = site_url('admin/category/add');
        }else
        {
            $config['upload_path'] = './uploads/';
            $config['allowed_types']='gif|png|jpg|jpeg';
            $config['max_size'] = '1000';
            $config['file_name'] = uniqid();

            $this->load->library('upload',$config);
            $this->upload->do_upload('pic');

            $data['cat_name'] = $this->input->post('cat_name',true);
            $data['parent_id'] = $this->input->post('parent_id');
            $data['unit'] = $this->input->post('unit',true);
            $data['sort_order'] = $this->input->post('sort_order',true);
            $data['is_show'] = $this->input->post('is_show');
            $data['cat_desc'] = $this->input->post('cat_desc',true);

            //调用model
            if($this->category_model->add_cat($data))
            {
                //添加成功
                echo '添加成功';
            }
            else
            {
                $data['message'] = validation_errors();
                $data['wait'] = 3;
                $data['url'] = site_url('admin/category/add');
            }
        }
    }
}