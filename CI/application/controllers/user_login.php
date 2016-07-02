<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_login extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->helper('cookie');
      $this->load->helper('url');
   }

   public function index()
   {
     $sha_number = $this->input->cookie('number');
     if($sha_number)
     {
        $this->load->view('user_home.php');
     }
     else {
      $this->load->view('user.php');
     }
   }

    public function user_login()
    {

      $this->load->model('user_login_model');

      $number = $this->input->post("number");
      $password = $this->input->post("password");
      $sha_number = sha1($number);

      $data = $this->user_login_model->check_login($number,$password);
      $req=array('check'=>$data);
      if($data == '1')
      {
        $cookie = array(
          'name'   => 'number',
          'value'  => $sha_number,
          'expire' => '3600',
          //'domain' => '.localhost',
          //'path'   => '/',
          //'prefix' => 'myprefix_',
          //'secure' => TRUE
        );
        $this->input->set_cookie($cookie);
      }
      echo json_encode($req);
  }
}
