<?php
class  Check_uid_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function check_user($sha_number,$s=0)
  {
    $query = $this->db->query("select uid,number from users where sha1(number)='".$sha_number."';");
    $row = $query->first_row('array');
    if($s!=1)
    {
      return $row['uid'];
    }
    else {
      return $row['number'];
    }
  }

  public function check_number($sha_number)
  {
    $query = $this->db->query("select number from users where sha1(number)='".$sha_number."';");
    $row = $query->first_row('array');
    return $row['number'];
  }

  public function check_userinfo($number)
  {
    $query = $this->db->query("select name,class,phone from users where number = '".$number."';");
    $row = $query->first_row('array');
    $data = array('name' => $row['name'],'class' => $row['class'],'phone' => $row['phone']);
    return $data;
  }

  public function check_user_static($number)
  {
    $query_pwd = $this->db->query("select uid from users where password = sha1('".$number."') and number = '".$number."';");
    $query_info = $this->db->query("select name from users where number = '".$number."';");

    $row_pwd = $query_pwd->first_row('array');
    $row_info = $query_info->first_row('array');

    if($row_pwd['uid'])
    {
      return 3;
    }
    else if(empty($row_info['name']))
    {
      return 2;
    }
    else
    {
      return 1;
    }
  }

  public function check_signup_activity($number)
  {
    $query = $this->db->query("select signup.aid,signup.comment,snumber,sname,organizer,location,start_time,end_time,college,remark,static,name from signup,activity where signup.aid = activity.aid and signup.snumber='".$number."';");
      echo "<h3>我的记录</h3><hr>";
      echo "<table border='2' class=\"table table-hover\">
    <tr bgcolor=\"#E6E6FA\">
    <th>学号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>活动名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>组织&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>地点&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>开始时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>结束时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>学院&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>备注&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th></th>
    <th></th>
    </tr>";

    foreach ($query->result_array() as $row)
    {

       if($row['static']==1)
       {
         $static = "已确认到场";
         if($row['comment']!=1)
         {
           $comment = "评论";
             $button_class = "btn btn-primary";
         }
         else {
           $comment = "查看评论";
             $button_class = "btn btn-info";

         }
       }
       else {
         $static = "未确认到场";
         $comment = "查看评论";
           $button_class = "btn btn-default";

       }

       echo"<tr>";
       echo"<td>" . $row['snumber'] . "</td>";
       echo"<td>" . $row['name'] . "</td>";
       echo"<td>" . $row['organizer'] . "</td>";
       echo"<td>" . $row['location'] . "</td>";
       echo"<td>" . $row['start_time'] . "</td>";
       echo"<td>" . $row['end_time'] . "</td>";
       echo"<td>" . $row['college'] . "</td>";
       echo"<td>" . $row['remark'] . "</td>";
       echo"<td>" . $static . "</td>";
       echo "<td>".'<button  class = "'.$button_class.'" name="'. $row['aid'] .'"  onclick="comment('.$row['snumber'].',this.name)">'.$comment.'</button>'." </td>";
       echo"</tr>";
    }
    echo "</table>";
  }
}
?>
