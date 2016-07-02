<?php
class Admin_login_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function check($uid,$pwd)
  {
    $query = $this->db->query("select uid from admin where uid='".$uid."' and pwd = sha1('".$pwd."');");
    $row = $query->first_row('array');

    if($row['uid'])
    {
      return 1;
    }
    else
    {
      return 2;
    }

  }
}
 ?>
