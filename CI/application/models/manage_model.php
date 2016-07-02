<?php
class  Manage_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function select_data()
  {
    $uploader=$_SESSION['ADMIN'];
    $query = $this->db->query("select * from activity where uploader='".$uploader."';");
    echo "<h2>管理活动</h2><br>";
    echo "<table border='2' class=\"table table-hover\" >
    <tr bgcolor=\"#E6E6FA\">
    <th>ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>活动名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>组织者&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>活动地点&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>起始时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>结束时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>发起学院&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>备注&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>报名状态&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    </tr>";

    foreach ($query->result_array() as $row)
    {
      date_default_timezone_set('prc');
      $now_time = date('Y-m-d h:i:s',time());
      $c=1;
       if($row['start_time']>$now_time)
       {
         if($row['status']==1)
         {
           $status="可以报名";
           $button="关闭报名";
           $b1 = "btn btn-primary";
           $b2 = "btn btn-danger";
         }
         else {
           $status="报名已关闭";
           $button="开启报名";
           $b1 = "btn btn-primary";
           $b2 = "btn btn-success";
         }
       }
       else {
         $status="该活动已过期";
         $button="该活动已过期";
         $b1 = "btn btn-primary";
         $b2 = "btn btn-warning";
         $c = 2;
       }
       echo"<tr>";
       echo"<td>" . $row['aid'] . "</td>";
       echo"<td>" . $row['name'] . "</td>";
       echo"<td>" . $row['organizer'] . "</td>";
       echo"<td>" . $row['location'] . "</td>";
       echo"<td>" . $row['start_time'] . "</td>";
       echo"<td>" . $row['end_time'] . "</td>";
       echo"<td>" . $row['college'] . "</td>";
       echo"<td>" . $row['remark'] . "</td>";
       echo"<td>" . $status . "</td>";
       echo "<td>".'<button name="'. $row['aid'] .'"  onclick="detail_activity(this.name)"  class = "'.$b1.'">查看详细信息</button>'." </td>";
       echo "<td>".'<button name="'. $row['aid'] .'"  onclick="change_status(this.name,'.$c.')" class = "'.$b2.'" >'.$button.'</button>'." </td>";
   	   echo "<td>".'<button id="'. $row['aid'] .'"  onclick="del_activity(this.id)" class = "btn btn-danger">删除信息</button>'." </td>";
       echo "<td>".'<button id="'. $row['aid'] .'"  onclick="comment(this.id)" class = "btn btn-info">查看评论</button>'." </td>";

       echo"</tr>";
    }
    echo "</table>";

  }

  public function delete_activity($aid)
  {
    $this->db->delete('activity', array('aid' => $aid));
  }

  public function change_status($aid)
  {
    $query = $this->db->query("select status from activity where aid='".$aid."';");
    foreach ($query->result_array(1) as $row)
    {
      if($row['status']==1)
      {
        $status=0;
        $this->db->set('status', $status);
        $this->db->where('aid', $aid);
        return $this->db->update('activity');

      }
      else {
        $status=1;
        $this->db->set('status', $status);
        $this->db->where('aid', $aid);
        return $this->db->update('activity');
      }
    }
  }

  public function change_static($number,$aid)
  {
    $c = 1;
    $this->db->set('static', $c);
    $this->db->where('aid', $aid);
    $this->db->where('snumber', $number);
    return $this->db->update('signup');
  }

  public function comment($number,$aid)
  {
    $query_comment = $this->db->query("select snumber,comment,static from signup where aid='".$aid."' and snumber = '".$number."';");
    foreach ($query_comment->result_array(1) as $row_comment)
    {
      if($row_comment['snumber'])
      {
        if($row_comment['comment'] == 0 && $row_comment['static'] == 1)
        {
          return 1;
        }
        else if($row_comment['comment'] == 1 && $row_comment['static'] == 1)
        {
          return 2;
        }
        else if($row_comment['static'] == 0)
        {
          return 4;
        }
      }
      else {
        return 3;
      }
    }
  }

  public function add_comment($number,$aid,$comment)
  {
    $data = array(
    'aid' => $aid,
    'comment' => $comment,
    );
    $this->db->insert('comment', $data);

    $c = 1;
    $this->db->set('comment',$c);
    $this->db->where('aid', $aid);
    $this->db->where('snumber', $number);
    return $this->db->update('signup');
  }

  public function manage_comment()
  {
    $query = $this->db->query("select * from comment order by id desc;");
    echo "<h3>查看评论</h3><hr>";
    echo "<table border='2' class=\"table table-hover\">
    <tr bgcolor=\"#E6E6FA\">
    <th>活动名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>评论&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th></th>
    </tr>";


    foreach ($query->result_array(1) as $row)
    {
      if($row['static'] == 0)
      {
        $s = "通过审核";
        $name = $this->db->query("select name from activity where aid = '".$row['aid']."';");
        $row_name = $name->first_row('array');
        echo"<tr>";
        echo"<td>" . $row_name['name'] . "</td>";
        echo"<td>" . $row['comment'] . "</td>";
        echo"<td><input type = 'button' value = '".$s."' onclick = 'change_static(".$row['id'].",this.value)' class = 'btn btn-success'/></td>";
        echo"</tr>";
      }
      if($row['static'] == 1)
      {
        $s = "取消通过";
        $name = $this->db->query("select name from activity where aid = '".$row['aid']."';");
        $row_name = $name->first_row('array');
        echo"<tr>";
        echo"<td>" . $row_name['name'] . "</td>";
        echo"<td>" . $row['comment'] . "</td>";
        echo"<td width=5%><input type = 'button' value = '".$s."' onclick = 'change_static(".$row['id'].",this.value)' class = 'btn btn-danger'/></td>";
        echo"</tr>";
      }
    }
    echo"</table>";
  }

  public function change_comment_static($id,$static)
  {
    if($id && $static)
    {
      if($static == "通过审核")
      {
        $this->db->set('static',1);
        $this->db->where('id', $id);
        $this->db->update('comment');
      }
      else if($static == "取消通过")
      {
        $this->db->set('static',0);
        $this->db->where('id', $id);
        $this->db->update('comment');
      }
    }
  }

  public function watch_comment($aid)
  {
    $query = $this->db->query("select * from comment where aid = '".$aid."'  and static = 1  order by id desc;");
    $query_name = $this->db->query("select name from activity where aid = '".$aid."';");
    $row_name = $query_name->first_row('array');
    echo "<h1>".$row_name['name']."</h1>";
    echo "<table border='2' class=\"table table-hover\" width=70%>
    <tr>
    <th width=20%>用户&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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

  public function activity_situation($class_no)
  {
    echo "<h1>".$class_no."</h1>";
    echo "<table border='2'  class=\"table table-hover\" width=70%>
    <tr bgcolor=\"#E6E6FA\">
    <th>活动名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>报名人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>实到人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>未报名人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    </tr>";

    $query_name = $this->db->query("select aid,name from activity;");
    foreach ($query_name->result_array() as $row)
    {
      echo"<td>".$row['name']."</td>";
      echo"<td>".self::search(1,$class_no,$row['aid'])."</td>";
      echo"<td>".self::search(2,$class_no,$row['aid'])."</td>";
      echo"<td>".self::search(3,$class_no,$row['aid'])."</td>";
      echo"</tr>";
    }
    echo "</table>";
  }

  public function search($s,$class_no,$aid)
  {
    if($s == 1)
    {
      $query = $this->db->query("select count(*) from signup where aid = '".$aid."' and sclass = '".$class_no."';");
      $count = $query->first_row('array');
      return $count['count(*)'];

    }
    else if($s == 2)
    {
      $query = $this->db->query("select count(*) from signup where aid = '".$aid."' and sclass = '".$class_no."' and static = 1;");
      $count = $query->first_row('array');
      return $count['count(*)'];
    }
    else if($s == 3)
    {
      $count = self::count_student($class_no) - self::search(1,$class_no,$aid);
      return $count;
    }
  }

  public function count_student($class_no)
  {
    $query = $this->db->query("select count(*) from users where class = '".$class_no."';");
    $count = $query->first_row('array');
    return $count['count(*)'];
  }

  public function check_grade($aid,$flag,$year)
  {
    date_default_timezone_set('prc');
    $mouth = date('m',time());
    if((int)$mouth<9) {
      $flag = (int)$year-$flag;
    }
    else {
      $flag = (int)$year - $flag + 1;
    }
    $query = $this->db->query("select count(*) from signup where aid = '".$aid."' and static = 1 and sign_up_year = $year and snumber like '".$flag."%';");
    $count = $query->first_row('array');
    return $count['count(*)'];
  }

  public function check_grade_situation($year)
  {
    echo "<h2>仅" . $year . "年</h2>";
    date_default_timezone_set('prc');
    $mouth = date('m', time());
    $nyear = date('Y', time());

    if((int)$nyear == (int)$year) {
      if ((int)$mouth <= 8) {
        echo "<table border='2'  class=\"table table-hover\" width=70%>
    <tr bgcolor=\"#E6E6FA\">
    <th>活动名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 1) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 2) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 3) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 4) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    </tr>";

        $query_name = $this->db->query("select aid,name from activity where YEAR(start_time) = '" . $year . "';");
        foreach ($query_name->result_array() as $row) {
          echo "<td>" . $row['name'] . "</td>";
          echo "<td>" . self::check_grade($row['aid'], 1, $year) . "</td>";
          echo "<td>" . self::check_grade($row['aid'], 2, $year) . "</td>";
          echo "<td>" . self::check_grade($row['aid'], 3, $year) . "</td>";
          echo "<td>" . self::check_grade($row['aid'], 4, $year) . "</td>";
          echo "</tr>";
        }
        echo "</table>";
      } else {
        echo "<table border='2'  class=\"table table-hover\" width=70%>
    <tr bgcolor=\"#E6E6FA\">
    <th>活动名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 0) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 1) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 2) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 3) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    </tr>";

        $query_name = $this->db->query("select aid,name from activity where YEAR(start_time) = '" . $year . "';");
        foreach ($query_name->result_array() as $row) {
          echo "<td>" . $row['name'] . "</td>";
          echo "<td>" . self::check_grade($row['aid'], 1, $year) . "</td>";
          echo "<td>" . self::check_grade($row['aid'], 2, $year) . "</td>";
          echo "<td>" . self::check_grade($row['aid'], 3, $year) . "</td>";
          echo "<td>" . self::check_grade($row['aid'], 4, $year) . "</td>";
          echo "</tr>";
        }
        echo "</table>";
      }
    }
    else if((int)$year<(int)$nyear)
    {
      echo "<table border='2'  class=\"table table-hover\" width=70%>
    <tr bgcolor=\"#E6E6FA\">
    <th>活动名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 0) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 1) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 2) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>" . ($year - 3) . "届参加人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    </tr>";

      $query_name = $this->db->query("select aid,name from activity where YEAR(start_time) = '" . $year . "';");
      foreach ($query_name->result_array() as $row) {
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . self::check_grade($row['aid'], 1, $year) . "</td>";
        echo "<td>" . self::check_grade($row['aid'], 2, $year) . "</td>";
        echo "<td>" . self::check_grade($row['aid'], 3, $year) . "</td>";
        echo "<td>" . self::check_grade($row['aid'], 4, $year) . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    }
  }
  public function check_major_situation($year)
  {
    if($_SESSION['ADMIN'])
    {
      echo "<h2>仅" . $year . "年</h2>";
      echo "<table border='2'  class=\"table table-hover\" width=70%>
        <tr bgcolor=\"#E6E6FA\">
        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th>计算机科学与技术&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th>信息安全&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th>软件工程&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th>网络工程&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        </tr>";
        echo "<td>总人数</td>";
        echo "<td>".self::major_count("cs",$year)."</td>";
        echo "<td>".self::major_count("is",$year)."</td>";
        echo "<td>".self::major_count("se",$year)."</td>";
        echo "<td>".self::major_count("ne",$year)."</td>";
        echo "</tr>";
        echo "<td>参加活动人次</td>";
        echo "<td>".self::major_count_2("cs",$year)."</td>";
        echo "<td>".self::major_count_2("is",$year)."</td>";
        echo "<td>".self::major_count_2("se",$year)."</td>";
        echo "<td>".self::major_count_2("ne",$year)."</td>";
        echo "</tr>";
      echo "</table>";
    }
  }

  public function major_count($major,$year)
  {
    date_default_timezone_set('prc');
    $mouth = date('m', time());
    $nyear = date('Y', time());
    if((int)$year<(int)$nyear)
    {
      $grade = (int)$year;
      $grade_max = ($grade+1)*10000;
      $grade_min = ($grade-3)*10000;
      $query = $this->db->query("select count(*) from users where number  between '".$grade_min."' and '".$grade_max."' and major = '".$major."';");
      $count = $query->first_row('array');
      return $count['count(*)'];
    }
    else if((int)$year==(int)$nyear){
      if((int)$mouth<9)
      {
        $grade = (int)$nyear-1;
        $grade_max = ($grade+1)*10000;
        $grade_min = ($grade-3)*10000;
        $query = $this->db->query("select count(*) from users where number  between '".$grade_min."' and '".$grade_max."' and major = '".$major."';");
        $count = $query->first_row('array');
        return $count['count(*)'];
      }
      else{
        $grade = (int)$nyear;
        $grade_max = $grade*10000;
        $grade_min = ($grade-3)*10000;
        $query = $this->db->query("select count(*) from users where number  between '".$grade_min."' and '".$grade_max."' and major = '".$major."';");
        $count = $query->first_row('array');
        return $count['count(*)'];
      }
    }
  }

  public function major_count_2($major,$year)
  {
    date_default_timezone_set('prc');
    $mouth = date('m', time());
    $nyear = date('Y', time());
    if((int)$year<(int)$nyear)
    {
      $grade = (int)$year;
      $grade_max = ($grade+1)*10000;
      $grade_min = ($grade-3)*10000;
      $query = $this->db->query("select count(*) from (signup left join users on snumber = number) where (number between '".$grade_min."' and '".$grade_max."') and major = '".$major."' and static = 1 and sign_up_year = '".$year."';");
      $count = $query->first_row('array');
      return $count['count(*)'];
    }
    else if((int)$year==(int)$nyear){
      if((int)$mouth<9)
      {
        $grade = (int)$nyear-1;
        $grade_max = ($grade+1)*10000;
        $grade_min = ($grade-3)*10000;
        $query = $this->db->query("select count(*) from (signup left join users on snumber = number) where (number between '".$grade_min."' and '".$grade_max."') and major = '".$major."' and static = 1 and sign_up_year = '".$year."';");
        $count = $query->first_row('array');
        return $count['count(*)'];
      }
      else{
        $grade = (int)$nyear;
        $grade_max = $grade*10000;
        $grade_min = ($grade-3)*10000;
        $query = $this->db->query("select count(*) from (signup left join users on snumber = number) where (number between '".$grade_min."' and '".$grade_max."') and major = '".$major."' and static = 1 and sign_up_year = '".$year."';");
        $count = $query->first_row('array');
        return $count['count(*)'];
      }
    }
  }

  public function manage_admin()
  {
    if($_SESSION['ADMIN'] == "root")
    {
      $query = $this->db->query("select * from admin where uid !='root';");
      echo '<div class="admin" ><h2>管理管理员</h2><br><button  class = "btn btn-info btn-lg button_1" data-toggle="modal" data-target="#myModal">添加管理员</button><br><br>';
      echo "<table border='2' class=\"table table-hover\" >
    <tr bgcolor=\"#E6E6FA\">
    <th>管理员账号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>管理员密码&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>删除该管理员&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th></tr></tr>";

      foreach ($query->result_array() as $row) {
        echo"<tr>";
        echo"<td>" . $row['uid'] . "</td>";
        echo"<td>" . $row['pwd'] . "</td>";
        echo "<td>".'<button name="'. $row['uid'] .'"  onclick="delete_admin(this.name)"  class = "btn btn-danger">删除该账号</button>'." </td>";

      }
    echo "</table></div>";
    }
    else
    {
      echo "权限不足,请返回!";
    }
  }

  public function delete_admin($uid)
  {
    if($_SESSION["ADMIN"] == "root")
    {
      $this->db->delete('admin', array('uid' => $uid));
    }
    else
    {
      echo "权限不足,请返回!";
    }
  }

  public function add_admin($uid,$pwd)
  {
    if($_SESSION["ADMIN"] == "root")
    {
      $query = $this->db->query("select uid from admin where uid = '".$uid."'");
      $check = $query->first_row('array');
      if($check)
      {
        return "3";
      }
      else {
        return $this->db->query("insert into admin values('" . $uid . "','" . sha1($pwd) . "');");
      }
    }
    else
    {
      echo "权限不足,请返回!";
    }
  }
}
?>
