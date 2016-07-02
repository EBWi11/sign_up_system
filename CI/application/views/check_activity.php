<html>
<head>
    <title>查看活动</title>
    <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <style>
        .footer{position: fixed;  bottom:0;  left:0;  width:100%;  background-color:#0a0a0b ;  border-style:solid;  border-top-color: #1f1f1f;  color: #fef6ff;}
    </style>
    <script>
    function check(static,aid)
    {
      if(static == 2)//未报名，报名
      {
        $.ajax({
          type:"post",
          url:"../home/sign_up_activity",
          dataType:"json",
          data:"aid="+aid,
          success:function(req)
          {
            if(req.check==1)
            {
              location.href = "../home/check_activity";
            }
            else if(req.check==3)
            {
              alert("该活动当前无法报名！请联系学院管理员！");
            }
          }
        });
      }
    }

    function cancel_signup(static,number,aid)
    {
      if(static == 1 && number && aid)
      {
        $.ajax({
          type:"post",
          url:"../../index.php/home/cancel_signup",
          dataType:"json",
          data:"number="+number+"&aid="+aid,
          success:function(req)
          {
            if(req.check=="1")
            {
              alert("取消成功！");
              location.href = "../home/check_activity";
            }
            else
            {
              alert("最晚在活动开始前一天时间取消！");
            }
          }
        });

      }
    }
    </script>

    <style>
        .table td, .table th {
            border-top: 1px solid #000000;
        }
        .table{width: 85%;margin-left: 5%}
    </style>
</head>
<body>
</body>
<footer class="footer">
    <div>
        <br><br>
        <p align="center">上海电力学院 计算机与技术学院 2013055创新创业班</p>
    </div>
</footer>
</html>
