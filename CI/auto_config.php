<?php
/**
 * Created by PhpStorm.
 * User: E_Bwill
 * Date: 16/3/30
 * Time: 下午8:47
 */
if( $_POST['old_username'] && $username = $_POST['username'])
{
  $old_username = $_POST['old_username'];
  $old_password = $_POST['old_password'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  $change_username="'username' => '".$username."'";
  $change_password="'password' => '".$password."'";

  $origin_str = file_get_contents('./application/config/database.php');

  $update_username = str_replace("'username' => '".$old_username."'", $change_username, $origin_str);
  $update_password = str_replace("'password' => '".$old_password."'", $change_password,$update_username);

  file_put_contents('./application/config/database.php', $update_password);

}
?>
<html>
<head>
  <title>Auto Modify Database Config</title>
  </head>
<body>
<h2>Auto Modify Database Config</h2>
<hr style="width:93%;" align="left" >
<h4 style="color: #c91032">Please Promptly Remove From The Server!</h4>
<div style="margin-left: 7%">
<form  action="auto_config.php" method="post">
Database old user name:<input type="text" name="old_username"/><br>
Database old user password:<input type="text" name="old_password"/><br>
Database new user name:<input type="text" name="username"/><br>
Database new user password:<input type="text" name="password"/><br>
<input type="submit" value="submit" />
  <hr>
  <h5 style="color: #00b5ad">If Modify Config Failed,Please Improve Related Documents Authority</h5>
</form>
</div>
</body>
</html>
