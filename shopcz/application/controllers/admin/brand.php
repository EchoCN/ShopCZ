<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class brand extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('brand_model');
    }

    //显示品牌信息

    public function index(){
        $data['brands'] = $this->brand_model->list_brand();
        $this->load->view('brand_list.html',$data);
    }

    //显示添加品牌页面
    public function add(){
        $this->load->view('brand_add.html');
    }

    //显示编辑品牌页面
    public function edit(){
        $this->load->view('brand_edit.html');
    }

    //添加品牌
    public function insert(){
        //设置验证规则
        $this->form_validation->set_rules('brand_name','品牌名称','required');

        if($this->form_validation->run() == false)
        {
            //未通过
            $data['message'] = validation_errors();
            $data['wait'] = 3;
            $data['url'] = site_url('admin/brand/add');
            $this->load->view('message.html',$data);
        }else
        {
            //通过,处理图片上传

            $config['upload_path'] = './uploads/';
            $config['allowed_types']='gif|png|jpg|jpeg';
            $config['max_size'] = '1000';
            $config['file_name'] = uniqid();

            $this->load->library('upload',$config);
            $this->upload->do_upload('logo');

            $file = $this->upload->data();//获得图片数据
            $data['logo'] =  $file['file_name'];
            $data['brand_name'] = $this->input->post('brand_name');
            $data['url'] = $this->input->post('url');
            $data['brand_desc'] = $this->input->post('brand_desc');
            $data['sort_order'] = $this->input->post('sort_order');
            $data['is_show'] = $this->input->post('is_show');

            //调用模型完成insert动作
            if($this->brand_model->add_brand($data))
            {
                $data['message'] = "添加成功";
                $data['wait'] = 3;
                $data['url'] = site_url('admin/brand/index');
                $this->load->view('message.html',$data);
            }else
            {
                $data['message'] = "添加失败";
                $data['wait'] = 3;
                $data['url'] = site_url('admin/brand/add');
                $this->load->view('message.html',$data);
            }
        }
    }
}