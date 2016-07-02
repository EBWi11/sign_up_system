<?php
class Add_info_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function add_info($number,$name,$class_no,$phone,$major,$email)
  {
    $this->db->set('name', $name);
    $this->db->set('class', $class_no);
    $this->db->set('phone', $phone);
    $this->db->set('major', $major);
    $this->db->set('email', $email);
    $this->db->where('number', $number);
    return $this->db->update('users');
  }
}
?>
