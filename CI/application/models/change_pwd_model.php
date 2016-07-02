<?php
class  Change_pwd_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function change_pwd($number,$old_pwd,$new_pwd)
  {
    $query = $this->db->query("select password from users where number='".$number."';");
    $row = $query->first_row('array');
    if($row['password'] == sha1($old_pwd))
    {
      $this->db->set('password', sha1($new_pwd));

      $this->db->where('number', $number);
      return $this->db->update('users');
    }
    else
    {
      return 2;
    }
  }

  public function change_pwd_2($email,$pwd)
  {
    $query = $this->db->query("select uid from users where email='".$email."';");
    $row = $query->first_row('array');
    if($row['uid'])
    {
      $this->db->set('password', sha1($pwd));
      $this->db->where('uid', $row['uid']);

      $mem = new Memcache;
      $mem->connect("127.0.0.1", 11211);
      $mem->delete($email);

      return $this->db->update('users');
    }
    else{
      return 0;
    }
  }
}
?>
