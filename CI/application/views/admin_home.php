<?php
//session_start();
if($_SESSION['ADMIN']=="root")
{
    $authority = "      <div class=\"col-xs-3 last\">
          <div class=\"tile\">
              <img src=\"../../resource/img/icons/svg/book.svg\" alt=\"Infinity-Loop\" class=\"tile-image\">
              <a class=\"btn btn-primary btn-large btn-block\" href=\"../home/manage_admin\">管理管理员</a>
          </div>
      </div>";
}
else
{
    $authority = "";
}
?>
<html>
<head>
  <title>管理员主页</title>
  <script src = "../../jquery-2.1.1.js"></script>
  <link href="../../resource/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../resource/dist/css/flat-ui.css" rel="stylesheet">
  <link href="../../resource/docs/assets/css/demo.css" rel="stylesheet">
    <style>
        .footer{position: fixed;  bottom:0;  left:0;  width:100%;  background-color:#0a0a0b ;  border-style:solid;  border-top-color: #1f1f1f;  color: #fef6ff;}
    </style>
</head>
<body>
<h2>管理员主页</h2>
<hr  style="height:1px;border:none;border-top:1px solid #555555;">
  <div>
    <div class="col-xs-3 first">
         <div class="tile">
           <img src="../../resource/img/icons/svg/gift-box.svg" alt="Infinity-Loop" class="tile-image">
           <a class="btn btn-primary btn-large btn-block" href="../home/upload_activity">提交活动</a>
         </div>
       </div>
       <div class="col-xs-3 centre">
            <div class="tile">
              <img src="../../resource/img/icons/svg/clipboard.svg" alt="Infinity-Loop" class="tile-image">
              <a class="btn btn-primary btn-large btn-block" href="../home/manage_activity">管理活动</a>
            </div>
          </div>
      <div class="col-xs-3 centre">
           <div class="tile">
             <img src="../../resource/img/icons/svg/pencils.svg" alt="Infinity-Loop" class="tile-image">
             <a class="btn btn-primary btn-large btn-block" href="../home/manage_comment">管理评论</a>
           </div>
        </div>
      <div class="col-xs-3 last">
              <div class="tile">
                <img src="../../resource/img/icons/svg/mail.svg" alt="Infinity-Loop" class="tile-image">
                <a class="btn btn-primary btn-large btn-block" href="../home/activity_situation">查询详细信息</a>
             </div>
      </div>
        <?php echo $authority;?>
  </div>
</body>
<footer class="footer">
    <div>
        <br><br>
        <p align="center">上海电力学院 计算机与技术学院 2013055创新创业班</p>
    </div>
</footer>
</html>
