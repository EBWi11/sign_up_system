<html>
<head>
  <title>提交活动</title>
  <script src = "../../jquery-2.1.1.js"></script>
  <link href="../../resource/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../resource/dist/css/flat-ui.css" rel="stylesheet">
  <link href="../../resource/docs/assets/css/demo.css" rel="stylesheet">
  <style>
  .form{margin-left: 25%;margin-right: auto;}
  .footer{position: fixed;  bottom:0;  left:0;  width:100%;  background-color:#0a0a0b ;  border-style:solid;  border-top-color: #1f1f1f;  color: #fef6ff;}
  </style>
</head>
<body>
  <h3>提交活动</h3>
  <hr  style="height:1px;border:none;border-top:1px solid #555555;">
  <div class="col-xs-3 form" >
    <div class="form-group">
    活动名称:<input id = "name" type = "text" class="form-control"/><br>
    发起组织:<input id = "organizer" type = "text" class="form-control"/><br>
    活动地点:<input id = "location" type = "text" class="form-control"/><br>
    开始时间:<input id = "start_time" type = "text" class="form-control" placeholder="格式:年-月-日 时:分:秒"/><br>
    结束时间:<input id = "end_time" type = "text" class="form-control" placeholder="格式:年-月-日 时:分:秒"/><br>
    学院:<input id = "college" type = "text" class="form-control"/><br>
    备注:<textarea id = "remark" class="form-control"></textarea><br>
    <p id="error"></p>
    <button onclick = "check()" class="btn btn-block btn-lg btn-primary">提交</button>
  </div>
  </div>
  <div>
    <script>
    function check()
    {
      var name = $("#name").val();
      var organizer = $("#organizer").val();
      var location = $("#location").val();
      var start_time = $("#start_time").val();
      var end_time = $("#end_time").val();
      var college = $("#college").val();
      var remark = $("#remark").val();
      if(name&&organizer&&location&&start_time&&end_time&&college&&remark)
      {
          $.ajax({
            type:"post",
            url:"../../index.php/home/upload",
            dataType:"json",
            data:"name="+name+"&organizer="+organizer+"&location="+location+"&start_time="+start_time+"&end_time="+end_time+"&college="+college+"&remark="+remark,
            success:function(req)
            {
              if(req.check=="1")
              {
                window.location.href="../../index.php/home/admin_home";
              }
              else
              {
                document.getElementById('error').innerHTML = "上传失败";
              }
            }


          });
      }
      else
      {
          alert("请将信息录入完整");
      }
    }
    </script>
  </div>
</body>
<footer class="footer">
  <div>
    <p align="center">上海电力学院 计算机与技术学院 2013055创新创业班</p>
  </div>
</footer>
</html>
