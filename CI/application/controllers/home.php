<?php
session_start();
/**
 * Created by PhpStorm.
 * User: E_Bwill
 * Date: 16/3/18
 * Time: 下午0:31
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller
{

    public function __construct()
     {
       parent::__construct();
       $this->load->model('upload_model');
       $this->load->model('manage_model');
       $this->load->model('check_activity_model');
       $this->load->model('check_uid_model');
       $this->load->model('sign_up_model');
       $this->load->model('add_info_model');
       $this->load->model('change_pwd_model');
       $this->load->model('admin_login_model');
       $this->load->model('Retrieve_model');

       $this->load->helper('url_helper');
       $this->load->helper('cookie');
       $this->load->helper('url');

       //$this->load->library('session');
     }

     public function index()
     {
       $this->load->view('index.php');
     }

     public function admin_home()
     {
       if(isset($_SESSION['ADMIN']))
       {
         $this->load->view('admin_home');
       }
       else
       {
         $this->load->view('admin.php');
       }
     }

     public function admin_login()
     {
       $uid = $this->input->post('uid');
       $pwd = $this->input->post('pwd');
       $check = $this->admin_login_model->check($uid,$pwd);
       $req=array('check'=>$check);
       $_SESSION['ADMIN'] = $uid;
       echo json_encode($req);
     }

     public function user_home()
     {
         $sha_number = $this->input->cookie('number');
         if($sha_number)
         {
           $check = $this->check_uid_model->check_user($sha_number);
           if($check)
           {
             $number = $this->check_uid_model->check_number($sha_number);
             $static = $this->check_uid_model->check_user_static($number);
             if($static == 3)
             {
               $this->load->view('change_password.html');
             }
             else if($static == 2)
             {
               $this->load->view('add_info.html');
             }
             else if($static == 1)
             {
               $this->load->view('user_home');
             }

           }
           else {
             $this->load->view('user');
           }
         }
         else {
           $this->load->view('user');
         }
      }

     public function upload_activity()
     {
       if(isset($_SESSION['ADMIN']))
       {
         $this->load->view('upload_activity');
       }
       else
       {
         redirect('home');
       }
     }

  /**
   *
   */
  public function manage_activity()
     {
       if(isset($_SESSION['ADMIN']))
       {
         $this->load->view('manage_activity');
         $data=$this->manage_model->select_data();
        // $this->load->view('manage_activity',$data);
         $title=array('title'=>'管理活动');
         $this->load->view('mould.html',$title);
       }
       else
       {
         redirect('home');
       }
     }

     public function delete_activity()
     {
       $aid = $this->input->post('aid');
       if(isset($_SESSION['ADMIN']))
       {
         $data = $this->manage_model->delete_activity($aid);
         echo 1;
       }
       else
       {
         redirect('home');
       }
     }

     public  function modify_activity()
     {
       $aid = $this->input->post('aid');
       if(isset($_SESSION['ADMIN']))
       {
          $this->load->view('modify_activity');
       }
       else
       {
         redirect('home');
       }
     }

     public function detail_activity($aid)
     {
       //$aid = $this->input->post('aid');
       if(isset($_SESSION['ADMIN']))
       {
         $this->check_activity_model->detail_activity($aid);
         $students_data = $this->check_activity_model->check_students($aid);
         //print_r($students_data);
         $data['list'] = $students_data;
         $this->load->view('detail_activity.html',$data);
       }
       else
       {
         redirect('home');
       }
     }

     public function check_class($class_no)
     {
       if(isset($_SESSION['ADMIN']))
       {
         $this->check_activity_model->check_class($class_no);
         $title=array('title'=>$class_no);
         $this->load->view('mould.html',$title);
       }
       else
       {
         redirect('home');
       }
     }

     public function check_student($number)
     {
       if(isset($_SESSION['ADMIN']))
       {
         $this->check_activity_model->check_student($number);
         $title=array('title'=>$number);
         $this->load->view('mould.html',$title);
       }
       else
       {
         redirect('home');
       }
     }

     public function change_status()
     {
       $aid = $this->input->post('aid');
       if(isset($_SESSION['ADMIN']))
       {
         $data = $this->manage_model->change_status($aid);
         $req=array('check'=>$data);
         echo json_encode($req);
       }
       else
       {
         redirect('home');
       }
     }

     public function upload()
     {
       if(isset($_SESSION['ADMIN']))
       {
         $name = $this->input->post('name');
         $organizer = $this->input->post('organizer');
         $location = $this->input->post('location');
         $start_time = $this->input->post('start_time');
         $end_time = $this->input->post('end_time');
         $college = $this->input->post('college');
         $remark = $this->input->post('remark');

         $data['upload'] = $this->upload_model->insert_data($name,$organizer,$location,$start_time,$end_time,$college,$remark);
         if($data['upload']=="1")
         {
           $check="1";
           $req=array('check'=>$check);
           echo json_encode($req);
         }
         else if($data['upload']=="0")
         {
           $check="0";
           $req=array('check'=>$check);
           echo json_encode($req);
         }
       }
       else
       {
         redirect('home');
       }
     }

     public function check_activity()
     {
       $sha_number = $this->input->cookie('number');
       $snumber = $this->check_uid_model->check_number($sha_number);
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number);
         if($check)
         {
           $this->load->view('check_activity.php');
           $this->check_activity_model->check_activity($snumber);
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function sign_up_activity()
     {
       $sha_number = $this->input->cookie('number');
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number);
         if($check)
         {
           $aid = $this->input->post('aid');
           $number = $this->check_uid_model->check_number($sha_number);

           $data = $this->check_uid_model->check_userinfo($number);

           $result = $this->sign_up_model->sign_up($aid,$number,$data['name'],$data['class'],$data['phone']);
           $req=array('check'=>$result);
           echo json_encode($req);
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function cancel_login()
     {
       $sha_number = $this->input->cookie('number');
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number);
         if($check)
         {
           delete_cookie('number');
           $this->load->view('user.php');
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function add_info()
     {
       $sha_number = $this->input->cookie('number');
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number);
         if($check)
         {
           $number = $this->check_uid_model->check_number($sha_number);
           $name = $this->input->post('name');
           $class_no = $this->input->post('class_no');
           $phone = $this->input->post('phone');
           $major = $this->input->post('major');
           $email = $this->input->post('email');


           $this->add_info_model->add_info($number,$name,$class_no,$phone,$major,$email);
           $check=1;
           $req=array('check'=>$check);
           echo json_encode($req);
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function change_pwd_view()
     {
       $sha_number = $this->input->cookie('number');
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number);
         if($check)
         {
           $this->load->view('change_password.html');
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function change_pwd()
     {
       $sha_number = $this->input->cookie('number');
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number);
         if($check)
         {
           $number = $this->check_uid_model->check_number($sha_number);
           $old_pwd = $this->input->post('old_pwd');
           $new_pwd = $this->input->post('new_pwd');

           $check = $this->change_pwd_model->change_pwd($number,$old_pwd,$new_pwd);
           $req=array('check'=>$check);
           echo json_encode($req);
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function check_signup_activity()
     {
       $sha_number = $this->input->cookie('number');
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number);
         if($check)
         {
           $number = $this->check_uid_model->check_number($sha_number);
           $this->load->view('check_signup_activity.html');
           $this->check_uid_model->check_signup_activity($number);
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function change_static()
     {

       if(isset($_SESSION['ADMIN']))
       {
         $number = $this->input->post('number');
         $aid = $this->input->post('aid');
         $this->manage_model->change_static($number,$aid);
       }
       else
       {
         redirect('home');
       }
     }

     public function comment($number,$aid)
     {
       $sha_number = $this->input->cookie('number');
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number,1);
         if($check == $number)
         {
           $c = $this->manage_model->comment($number,$aid);
           if($c == 1)
           {
             $data = array('number'=>$number,'aid'=>$aid);
             $this->load->view('comment.html',$data);
           }
           else if($c == 0 || $c == 4)
           {
             echo "<h3>评论之后才有权查看评论!请返回!</h3>";
           }
           else if($c == 2)
           {
             $this->manage_model->watch_comment($aid);
             $title = array('title'=>"查看评论");
             $this->load->view('mould.html',$title);
           }
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function admin_watch_comment($aid)
     {
       if(isset($_SESSION['ADMIN']))
       {
         $this->check_activity_model->watch_comment($aid);
         $this->load->view('watch_comment.html');
       }
       else
       {
         redirect('home');
       }
     }

     public function add_comment()
     {
       $sha_number = $this->input->cookie('number');
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number);
         if($check)
         {
           $aid = $this->input->post('aid');
           $number = $this->input->post('number');
           $comment = $this->input->post('comment');

           $this->manage_model->add_comment($number,$aid,$comment);
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function cancel_signup()
     {
       $sha_number = $this->input->cookie('number');
       if($sha_number)
       {
         $check = $this->check_uid_model->check_user($sha_number);
         if($check)
         {
           $aid = $this->input->post('aid');
           $number = $this->input->post('number');
           if($number && $aid)
           {
             $check = $this->check_activity_model->cancel_signup($number,$aid);
             $req = array('check'=>$check);
             echo json_encode($req);
           }
         }
         else {
           redirect('user_login');
         }
       }
       else {
         redirect('user_login');
       }
     }

     public function manage_comment()
     {
       if(isset($_SESSION['ADMIN']))
       {
         $this->load->view('manage_comment.html');
         $data=$this->manage_model->manage_comment();
         $title = array('title'=>"查看评论");
         $this->load->view('mould.html',$title);
       }
       else
       {
         redirect('home');
       }
     }

     public function change_comment_static()
     {
       if(isset($_SESSION['ADMIN']))
       {
         $id = $this->input->post('id');
         $static = $this->input->post('static');

         if($id && $static)
         {
           $this->manage_model->change_comment_static($id,$static);
         }
       }
       else
       {
         redirect('home');
       }
     }

     public function activity_situation()
     {
       if(isset($_SESSION['ADMIN']))
       {
         $this->load->view('search_activity.html');
           //$this->manage_model->activity_situation();
       }
       else
       {
         redirect('home');
       }
     }

     public function check_class_situation($class_no)
     {
       if(isset($_SESSION['ADMIN']))
       {
          $this->manage_model->activity_situation($class_no);
         $title=array('title'=>$class_no);
         $this->load->view('mould.html',$title);
       }
       else
       {
         redirect('home');
       }
     }

  public function check_major_situation($major)
  {
    if(isset($_SESSION['ADMIN']))
    {
      $this->manage_model->check_major_situation($major);
      $title = array('title'=>$major);
      $this->load->view('mould.html',$title);
    }
    else
    {
      redirect('home');
    }
  }

  public function check_grade_situation($year)
  {
    if(isset($_SESSION['ADMIN']))
    {
      $this->manage_model->check_grade_situation($year);
      $title=array('title'=>$year);
      $this->load->view('mould.html',$title);
    }
    else
    {
      redirect('home');
    }
  }


  public function input_email()
  {
    $email = $this->input->post('email');
    if($email)
    {
      if(preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$email))
      {
        $check =  $this->Retrieve_model->make_code($email);
        $_SESSION['EMAIL'] = $email;
        $req = array('check'=>$check);
        echo json_encode($req);
      }
      else
      {
        $req = array('check'=>"2");
        echo json_encode($req);
      }
    }
  }

  public function validate()
  {
    $code = $this->input->post('code');
    $check =  $this->Retrieve_model->validate($_SESSION['EMAIL'],$code);
    $req = array('check'=>$check);
    echo json_encode($req);

  }

  public function change_pwd_2()
  {
    $pwd = $this->input->post('pwd');
    if(strlen($pwd)<8)
    {
      $req = array('check'=>"2");
      echo json_encode($req);
    }
    else
    {
      $check = $this->change_pwd_model->change_pwd_2($_SESSION['EMAIL'],$pwd);
      $req = array('check'=>$check);
      echo json_encode($req);
    }
  }

  public function manage_admin()
  {
    if(isset($_SESSION['ADMIN']) == "root")
    {
      $this->manage_model->manage_admin();
      $this->load->view('manage_admin.html');
    }
    else
    {
      echo "权限不足,前联系计算机学院";
    }
  }

  public function delete_admin()
  {
    if(isset($_SESSION['ADMIN']) == "root")
    {
      $uid = $this->input->post("uid");
      $check = $this->manage_model->delete_admin($uid);
      $req = array('check'=>$check);
      echo json_encode($req);
    }
    else
    {
      echo "权限不足,前联系计算机学院";
    }
  }

  public function add_admin()
  {
    if(isset($_SESSION['ADMIN']) == "root")
    {
      $uid = $this->input->post("uid");
      $pwd = $this->input->post("pwd");
      $check = $this->manage_model->add_admin($uid,$pwd);
      $req = array('check'=>$check);
      echo json_encode($req);
    }
    else
    {
      echo "权限不足,前联系计算机学院";
    }
  }
}
