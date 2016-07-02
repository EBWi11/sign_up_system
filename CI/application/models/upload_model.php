<?php
class Upload_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function insert_data($name,$organizer,$location,$start_time,$end_time,$college,$remark)
  {
    $uploader=$_SESSION['ADMIN'];
    $data = array(
    'name' => $name,
    'organizer' => $organizer,
    'location' => $location,
    'start_time' => $start_time,
    'end_time' => $end_time,
    'college' => $college,
    'remark' => $remark,
    'uploader'=>$uploader,
    'status' => 1
    );

    if($name&&$organizer&&$location&&$start_time&&$end_time&&$college&&$remark)
    {
      $this->db->insert('activity', $data);
      return 1;
    }
    else
    {
      return 0;
    }
  }
}
 ?>
