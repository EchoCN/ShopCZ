<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//权限控制器
class privilege extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('captcha');
        $this->load->library('form_validation');
    }

    public function login()
    {
        $this->load->view('login.html');
    }

    //生成验证码
    public function code()
    {
        //自行配置
        $vals = array(
            'word_length'=>6,
        );

        $code = create_captcha($vals);
        //保存到session中
        $this->session->set_userdata('code',$code);
    }

    //处理登入
    public function signin()
    {
        //数据库
        $this->load->model('login_model','login');

        //验证规则
        $this->form_validation->set_rules('admin_name','用户名','required');
        $this->form_validation->set_rules('password','密码','required');

        //获取表单数据
        $captcha = strtolower($this->input->post('captcha'));

        //验证码获取
        $code = strtolower($this->session->userdata('code'));

        if ($captcha === $code) {

            if($this->form_validation->run() == false)

            {
                $message = validation_errors();
                $data['message'] = validation_errors();
                $data['url'] = site_url('admin/privilege/login');
                $data['wait'] = 3;
                $this->load->view('message.html',$data);

            }else {

                //数据库验证
                $admin_name = $this->input->post('admin_name');
                $password = $this->input->post('password');

                if ($this->login->get_admin($admin_name,$password)){
                    # OK，保存session信息,然后跳转到首页
                    $this->session->set_userdata('admin',$admin_name);
                    redirect('admin/main/index');
                } else {
                    //error
                    $data['url'] = site_url('admin/privilege/login');
                    $data['message'] = '用户名密码错误，重新输入';
                    $data['wait'] = 3;
                    $this->load->view('message.html', $data);
                }
            }
        }
        else
            {
            $data['url']=site_url('admin/privilege/login');
            $data['message']='验证码错误，重新输入';
            $data['wait']=3;
            $this->load->view('message.html',$data);

        }
    }
    public function logout()
    {
        $this->session->unset_userdata('admin');
        $this->session->sess_destroy();
        redirect('admin/privilege/login');
    }

    public function update_password()
    {
        $this->load->model('login_model','login');


        $admin=$this->input->post('admin_name');
        $pw=$this->input->post('password');


       if($this->login->reset_password($admin,$pw))
       {
           echo '修改成功';
       }else
       {
           echo'修改失败';
       }

       $this->load->view('get_password.html');

    }

}