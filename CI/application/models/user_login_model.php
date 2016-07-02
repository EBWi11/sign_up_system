<?php
session_start();
class  User_login_model extends CI_Model
{
  public function __construct()
  {
    $this->load->database();
  }

  public function check_login($number,$password)
  {
    $query = $this->db->query("select password from users where number='".$number."';");
    $row = $query->first_row('array');

    if(sha1($password)==$row['password'])
    {
      return '1';
    }
    else
    {
      return '0';
    }

  }

}
