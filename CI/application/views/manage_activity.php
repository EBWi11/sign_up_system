
<html>
<head>
  <title>manage activities</title>
  <script src = "../../jquery-2.1.1.js"></script>
  <script>

    function del_activity(aid)
    {
      $.ajax({
        type:"post",
        url:"../home/delete_activity",
        dataType:"json",
        data:'aid='+aid,
        success:function(req){
          location.href = "../home/manage_activity";
        }
      });
    }

    function modify_activity(aid)
    {
      $.ajax({
        type:"post",
        url:"../home/modify_activity",
        dataType:"json",
        data:'aid='+aid,
        success:function(req){
          //location.href = "../home/manage_activity";
        }
      });
    }

    function change_status(aid,c)
    {
      if(c==1)
      {
        $.ajax({
          type:"post",
          url:"../home/change_status",
          dataType:"json",
          data:'aid='+aid,
          success:function(req){
            if(req.check==1)
            {
              location.href = "../home/manage_activity";
            }
          }
        });
      }
    }

    function detail_activity(aid)
    {
        //$.post("../home/detail_activity", { aid: aid,status: "2"} );
        location.href = "../home/detail_activity/"+aid;
    }

    function comment(aid)
    {
       location.href = "../home/admin_watch_comment/"+aid;
    }
  </script>
  <style>
    .footer{position: fixed;  bottom:0;  left:0;  width:100%;  background-color:#0a0a0b ;  border-style:solid;  border-top-color: #1f1f1f;  color: #fef6ff;}
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
