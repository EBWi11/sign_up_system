<?php
class  Check_activity_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function detail_activity($aid)
  {
    $query = $this->db->query("select * from signup where aid='".$aid."';");

      $query_name = $this->db->query("select name from activity where aid = '".$aid."';");
      $row_name = $query_name->first_row('array');
      echo "<h1>".$row_name['name']."</h1>";

    echo "<div class='table'><table border='2' class=\"table table-hover\" >
    <tr bgcolor=\"#E6E6FA\">
    <th>学号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>班级&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>姓名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>手机号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>状态</th>
    <th>签到</th>

    </tr>";

    foreach ($query->result_array() as $row)
    {
       if($row['static']==1)
       {
         $static = '已确认到场';
           $c = "btn btn-info";
       }
       else {
         $static = '确认到场';
         $row['static'] = "2";
           $c = "btn btn-success";
       }
       $name = "students";
       echo"<tr>";
       echo"<td>" . $row['snumber'] . "</td>";
       echo"<td>" . $row['sclass'] . "</td>";
       echo"<td>" . $row['sname'] . "</td>";
       echo"<td>" . $row['phone'] . "</td>";
       echo "<td>".'<button name="'. $row['snumber'] .'" class = "'.$c.'" onclick="static('.$row['static'].',this.name,'.$row['aid'].')">'.$static.'</button>'." </td>";
       echo"<td  width='10px'><input type='checkbox' id = '".$row['snumber']."' name ='".$name."' value = '".$aid."'/></td>";
       echo"</tr>";
    }
    echo "</table></div>";
  }

  public function check_activity($snumber)
  {
    $query = $this->db->query("select * from activity where to_days(start_time)>to_days(now());");
    echo"<h2>查看活动</h2><hr>";
    echo "<table border='2' class=\"table table-hover\">
    <tr bgcolor=\"#E6E6FA\">
    <th>活动名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>主办方&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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

       $query_check = $this->db->query("select snumber,static from signup where snumber = '".$snumber."' and aid = '".$row['aid']."' ;");
       //$query_time = $this->db->query("select aid from activity where start_time > now() and aid = '".$row['aid']."';");

       $row_check = $query_check->first_row('array');
       //$row_time = $query_time->first_row('array');

       if($row_check['snumber'])
       {
         $static  = 1;
         $check = '已报名';
         $check_cancel_signup = '取消报名';
           $button_class1 = 'btn btn-info';
           $button_class2 = 'btn btn-danger';
       }
       else {
         $static  = 2;
         $check = '点击报名';
         $check_cancel_signup = '无法取消';
           $button_class1 = 'btn btn-success';
           $button_class2 = 'btn btn-default';
       }

       echo"<tr>";
       echo"<td>" . $row['name'] . "</td>";
       echo"<td>" . $row['organizer'] . "</td>";
       echo"<td>" . $row['location'] . "</td>";
       echo"<td>" . $row['start_time'] . "</td>";
       echo"<td>" . $row['end_time'] . "</td>";
       echo"<td>" . $row['college'] . "</td>";
       echo"<td>" . $row['remark'] . "</td>";
       echo "<td>".'<button name="'. $row['aid'] .'"  onclick="check('.$static.',this.name)"  class = "'.$button_class1.'"  >'.$check.'</button>'." </td>";
       echo "<td>".'<button name="'. $row_check['snumber'] .'"  onclick="cancel_signup('.$static.',this.name,'.$row['aid'].')" class = "'.$button_class2.'" >'.$check_cancel_signup.'</button>'." </td>";
       echo"</tr>";
    }
    echo "</table>";
  }

  public function watch_comment($aid)
  {
    $query = $this->db->query("select * from comment where aid = '".$aid."';");
    $query_name = $this->db->query("select name from activity where aid = '".$aid."';");
    $row_name = $query_name->first_row('array');
    echo "<h1>".$row_name['name']."</h1>";
    echo "<table border='2' class=\"table table-hover\">
    <tr bgcolor=\"#E6E6FA\">
    <th width=5%>用户&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>评论&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    </tr>";
    foreach ($query->result_array() as $row)
    {
      echo"<tr>";
      echo"<td>匿名</td>";
      echo"<td>" . $row['comment'] . "</td>";
      echo"</tr>";
    }
    echo "</table>";

  }

  public function cancel_signup($number,$aid)
  {
    if($number && $aid)
    {
      $check_time = $this->db->query("select aid from activity where (date_sub(start_time, interval '1' day_second))>now() and aid = '".$aid."';");
      $check = $check_time->first_row('array');
      if($check)
      {
        $this->db->delete('signup', array('snumber' => $number,'aid' => $aid));
        return 1;
      }
      return 0;
    }
  }

  public function check_student($number)
  {
    if($number)
    {
      $query = $this->db->query("select aid,static from signup where snumber = '".$number."';");

      echo "<table border='2' class=\"table table-hover\">
              <tr bgcolor=\"#E6E6FA\">
              <th>学号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th>班级&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th>姓名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th>活动名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th>组织者&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th>学院&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th>到场情况&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              </tr>";

      foreach ($query->result_array() as $row)
      {
        $query_activity = $this->db->query("select name,organizer,college from activity where aid = '".$row['aid']."'");
        $query_student = $this->db->query("select * from users where number = '".$number."';");

        $check_student = $query_student->first_row('array');
        $check_activity = $query_activity->first_row('array');

        if($row['static'] == 1)
        {
          $static = "确认到场";
        }
        else {
          $static = "未到场";
        }
        echo"<tr>";
        echo"<td>" . $check_student['number'] . "</td>";
        echo"<td>" . $check_student['class'] . "</td>";
        echo"<td>" . $check_student['name'] . "</td>";
        echo"<td>" . $check_activity['name'] . "</td>";
        echo"<td>" . $check_activity['organizer'] . "</td>";
        echo"<td>" . $check_activity['college'] . "</td>";
        echo"<td>" . $static . "</td>";
      }
          echo "</table>";

    }
    else {
      return 0;
    }
  }

  public function check_class($class_no)
  {
    $check_students = $this->db->query("select number,name,phone from users where class = '".$class_no."';");
    $check_activity = $this->db->query("select aid,name from activity;");
    echo "<h1>".$class_no."</h1>";
    echo "<table border='2' class=\"table table-hover\">
    <tr bgcolor=\"#E6E6FA\">
    <th>学号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>姓名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>手机号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

    foreach ($check_activity->result_array() as $aname)
    {
      echo "<th>".$aname['name']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
    }

    echo "</tr>";

    foreach ($check_students->result_array() as $student)
    {
      echo"<tr>";
      echo"<td>" . $student['number'] . "</td>";
      echo"<td>" . $student['name'] . "</td>";
      echo"<td>" . $student['phone'] . "</td>";
      foreach ($check_activity->result_array() as $aid)
      {
          echo"<td>" . self::check_student_static($aid['aid'],$student['number']) . "</td>";
      }
      echo"</tr>";
    }

  }

  public function check_student_static($aid,$number)
  {
    $query = $this->db->query("select snumber,static from signup where aid = '".$aid."' and snumber = '".$number."';");
    $static = $query->first_row('array');
    if($static['snumber'])
    {
      if($static['static'] ==1)
      {
        return "确认到场";
      }
      else {
        return "未到场";
      }
    }
    else {
      return "未报名";
    }


  }

  public function check_students($aid)
  {
      if($aid)
      {
        $students_data = array();
        $query = $this->db->query("select snumber from signup where aid = '".$aid."';");
        foreach ($query->result_array() as $student)
        {
          array_push($students_data,$student['snumber']);
        }
        return $students_data;
      }
      else {
        return 0;
      }
  }

}
?>
