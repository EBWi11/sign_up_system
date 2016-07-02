<html>
<head>
  <title>学生登陆</title>
  <script src = "../../jquery-2.1.1.js"></script>
    <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
  <style>
  .log-in{margin-left: 35%;margin-right: 35%;}
  .form-control{background-color: rgba(148, 127, 130, 0.25);color:#000000}
  .b1{margin: 0% 0% 0% 80%;}
  .b2{margin: 0% 0% 0% 80%;}
      .right{color: #0ea11b;}
      .error{ color: #ff0c00;}
      .p1{z-index:1;}
      .p2{  position:absolute;  left:0px;  top:0px;  z-index:-1;}
  .footer{position: fixed;  bottom:0;  left:0;  width:100%;  background-color:#0a0a0b ;  border-style:solid;  border-top-color: #1f1f1f;  color: #fef6ff;}
  </style>
<script>
function login()
{
    var number = $("#number").val();
    var password = $("#password").val();

    if(number&&password)
    {
        $.ajax({
          type:"post",
          url:"../../index.php/user_login/user_login",
          dataType:"json",
          data:"number="+number+"&password="+password,
          success:function(req)
          {
            if(req.check=="1")
            {
              location.href="../../index.php/home/user_home";
            }
            else
            {
              document.getElementById('error').innerHTML = "学号或密码错误";
            }
          }
        });
    }
    else
    {
        alert("请将信息录入完整");
    }
}
    function input_email()
    {
        var email = $("#email").val();
        if(email)
        {
            $.ajax({
                type:"post",
                url:"input_email",
                dataType:"json",
                data:"email="+email,
                success:function(req)
                {
                    if(req.check=="1")
                    {
                        $(".error2").removeClass("right");
                        $(".error2").removeClass("error");
                        $(".error2").addClass("right");
                        document.getElementById('error2').innerHTML = "发送成功!请注意查收!";
                    }
                        else if(req.check=="2")
                    {
                        $(".error2").removeClass("right");
                        $(".error2").removeClass("error");
                        $(".error2").addClass("error");
                        document.getElementById('error2').innerHTML = "邮箱格式错误!";
                    }
                    else if(req.check=="3")
                    {
                        $(".error2").removeClass("right");
                        $(".error2").removeClass("error");
                        $(".error2").addClass("error");
                        document.getElementById('error2').innerHTML = "该邮箱并未登记!";
                    }
                }
            });
        }
        else {
            $(".error2").removeClass("right");
            $(".error2").removeClass("error");
            $(".error2").addClass("error");
            document.getElementById('error2').innerHTML = "请输入您的邮箱";
        }
    }

    function submit()
    {
        var code = $("#code").val();
        if(code)
        {
            $.ajax({
                type:"post",
                url:"validate",
                dataType:"json",
                data:"code="+code,
                success:function(req)
                {
                    if(req.check=="1")
                    {
                        $(".error2").removeClass("right");
                        $(".error2").removeClass("error");
                        $(".error2").addClass("right");
                        document.getElementById('error2').innerHTML = "验证成功!";

                        $(".get_code").removeClass("p1");
                        $(".get_code").addClass("p2");

                        $(".change_pwd").removeClass("p2");
                        $(".change_pwd").addClass("p1");
                    }
                    else
                    {
                        $(".error2").removeClass("right");
                        $(".error2").removeClass("error");
                        $(".error2").addClass("error");
                        document.getElementById('error2').innerHTML = "验证码错误!";
                    }
                }
            });
        }
        else {
            $(".error2").removeClass("right");
            $(".error2").removeClass("error");
            $(".error2").addClass("error");
            document.getElementById('error2').innerHTML = "请输入验证码!";
        }
    }

   function change_pwd()
{
    var pwd = $("#pwd").val();
    var pwd_rep = $("#pwd_rep").val();

    if(pwd && pwd_rep)
    {
        if(pwd==pwd_rep)
        {
            if(pwd.length>=8)
            {
                $.ajax({
                    type:"post",
                    url:"change_pwd_2",
                    dataType:"json",
                    data:"pwd="+pwd,
                    success:function(req)
                    {
                        if(req.check=="1")
                        {
                            location.href="../../index.php/home/user_home";
                        }
                        else if(req.check=="2")
                        {
                            $(".error2").removeClass("right");
                            $(".error2").removeClass("error");
                            $(".error2").addClass("error");
                            document.getElementById('error2').innerHTML = "密码过短!";
                        }
                        else
                        {
                            $(".error2").removeClass("right");
                            $(".error2").removeClass("error");
                            $(".error2").addClass("error");
                            document.getElementById('error2').innerHTML = "未知错误!请联系辅导员!";
                        }
                    }
                });
            }
            else
            {
                $(".error2").removeClass("right");
                $(".error2").removeClass("error");
                $(".error2").addClass("error");
                document.getElementById('error2').innerHTML = "密码过短!";
            }
        }
        else
        {
            $(".error2").removeClass("right");
            $(".error2").removeClass("error");
            $(".error2").addClass("error");
            document.getElementById('error2').innerHTML = "两次密码不相同!";
        }
    }
    else
    {
        $(".error2").removeClass("right");
        $(".error2").removeClass("error");
        $(".error2").addClass("error");
        document.getElementById('error2').innerHTML = "请填写完整后再提交!";
    }

}
</script>
</head>
<body>
<h3 align="center">学生登陆</h3>
<hr  style="height:1px;border:none;border-top:1px solid #555555;">
<body>
<div class="log-in">
    <div class="login-form">
      <div class="form-group">
        <input type="text" class="form-control login-field" value="" placeholder="Enter your ID" id="number" />
        <label class="login-field-icon fui-user" for="login-name"></label>
      </div>

      <div class="form-group">
        <input type="password" class="form-control login-field" value="" placeholder="Password" id="password" />
        <label class="login-field-icon fui-lock" for="login-pass"></label>
      </div>
      <div style="color:red">
        <p id="error"></p>
      </div>
      <button onclick="login()" class="btn btn-primary btn-lg btn-block">登陆</button>
        <button class="btn btn-danger btn-block btn-lg" data-toggle="modal" data-target="#myModal">忘记密码</button>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        请填写请输入您曾填写的邮箱来获得验证码
                    </h4>
                </div>

                <div class="modal-body get_code p1">
                    <form class="bs-example bs-example-form" role="form">
                        <div class="input-group">
                            <span class="input-group-addon">邮&nbsp;&nbsp;&nbsp;&nbsp;箱:</span>
                            <input type="text" class="form-control" placeholder="EMAIL" id = "email">
                        </div>
                        <br>
                    </form>
                    <input type = "button" class="btn btn-info b1" value = "发送验证码" onclick = "input_email()"><br><br>
                    <form class="bs-example bs-example-form" role="form">
                        <div class="input-group">
                            <span class="input-group-addon">验证码:</span>
                            <input type="text" class="form-control" placeholder="CAPTCHA" id = "code">
                        </div>
                        <br>
                    </form>
                    <input type = "button" class="btn btn-info b2" value = "确&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;认" onclick = "submit()">
                </div>


                <div class="modal-body change_pwd p2">
                    <form class="bs-example bs-example-form" role="form">
                        <div class="input-group">
                            <span class="input-group-addon">&nbsp;&nbsp;新密码&nbsp;&nbsp;</span>
                            <input type="password" class="form-control" placeholder="PASSWORD" id = "pwd">
                        </div>
                        <br>
                    </form>
                    <form class="bs-example bs-example-form" role="form">
                        <div class="input-group">
                            <span class="input-group-addon">确认密码:</span>
                            <input type="password" class="form-control" placeholder="PASSWORD REPEAT" id = "pwd_rep">
                        </div>
                        <br>
                    </form>
                    <input type = "button" class="btn btn-info b2" value = "确&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;认" onclick = "change_pwd()">
                </div>

                <div class="modal-footer">
                    <p class="error2" id="error2"></p>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
<footer class="footer">
    <div>
        <br><br>
        <p align="center">上海电力学院 计算机与技术学院 2013055创新创业班</p>
    </div>
</footer>
</html>
