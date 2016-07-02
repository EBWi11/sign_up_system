<?php
session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign_up extends CI_Controller
{

	public function __construct()
     {
         parent::__construct();
         $this->load->model('admin_login_model');
         $this->load->helper('url_helper'); 
     }

	public function index()
	{
		$this->load->view('index');
	}

  public function admin()
  {
    $this->load->view('admin');
  }

  public function user()
  {
    $this->load->view('user');
  }

  public function admin_login()
  {

		//$this->load->helper('cookie');

    $uid = $this->input->post('uid');
		$pwd = $this->input->post('pwd');

		$data['admin'] = $this->admin_login_model->check($uid,$pwd);

		if($data['admin']==1)
		{
			$check="1";

			$_SESSION['ADMIN']=$uid;

			$req=array('check'=>$check);
			echo json_encode($req);
		}
		else
		{
			$check="0";
			$req=array('check'=>$check);
			echo json_encode($req);
		}
	}
}
