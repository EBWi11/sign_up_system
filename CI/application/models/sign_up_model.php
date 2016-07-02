<?php
class  Sign_up_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function sign_up($aid,$number,$name,$class,$phone)
  {
    $check_activity_static = self::check_activity_static($aid);
    if($check_activity_static)
    {
      if($aid && $number && $name && $class && $phone)
      {
        date_default_timezone_set('prc');
        $year = data('Y',time());
        $month = date('m',time());

        $data = array(
          'aid' => $aid,
          'snumber' => $number,
          'sname' => $name,
          'sclass' => $class,
          'phone' => $phone,
            'sign_up_year' => $year,
            'sign_up_month' => $month
        );
        return $this->db->insert('signup', $data);
      }
      else {
        return "error";
        }
      }
    else {
      return 3;
    }
  }
    public function check_activity_static($aid)
    {
      $query = $this->db->query("select status from activity where aid = '".$aid."';");
      $row = $query->first_row('array');
      return $row['status'];
    }
}
?>
