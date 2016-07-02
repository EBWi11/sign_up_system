<?php
if(isset($_SESSION['ADMIN']))
{
  //echo $_SESSION['ADMIN'];
  header("Location:../home");
  exit;
}
?>
<html>
<head>
  <script src = "../../jquery-2.1.1.js"></script>
  <link href="../../resource/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../resource/dist/css/flat-ui.css" rel="stylesheet">
  <link href="../../resource/docs/assets/css/demo.css" rel="stylesheet">
  <style>
  .log-in{margin-left: 35%;margin-right: 35%;}
  .form-control{background-color:#948888;color:#000000}
  .footer{position: fixed;  bottom:0;  left:0;  width:100%;  background-color:#0a0a0b ;  border-style:solid;  border-top-color: #1f1f1f;  color: #fef6ff;}
  </style>
</head>
<h3 align="center">管理员登陆</h3>
<hr  style="height:1px;border:none;border-top:1px solid #555555;">
<body>
<div class="log-in">
    <div class="login-form">
      <div class="form-group">
        <input type="text" class="form-control login-field" value="" placeholder="Enter your ID" id="uid" />
        <label class="login-field-icon fui-user" for="login-name"></label>
      </div>

      <div class="form-group">
        <input type="password" class="form-control login-field" value="" placeholder="Password" id="pwd" />
        <label class="login-field-icon fui-lock" for="login-pass"></label>
      </div>
      <div style="color:red">
        <p id="error"></p>
      </div>
      <button onclick="check()" class="btn btn-primary btn-lg btn-block">登陆</button>
</div>
</body>
  <script>
  function check()
  {
    var uid = $("#uid").val();
    var pwd = $("#pwd").val();
    $.ajax({
      type:"post",
      url:"../home/admin_login",
      dataType:"json",
      data:'uid='+uid+'&pwd='+pwd,
      success:function(req)
      {
        if(req.check=="1")
        {
          location.href = "../home/admin_home";
        }
        else
        {
          document.getElementById('error').innerHTML = "用户名或密码错误！";
        }
      }
    });

  }
  </script>
<footer class="footer">
  <div>
    <br><br>
    <p align="center">上海电力学院 计算机与技术学院 2013055创新创业班</p>
  </div>
</footer>
</html>
