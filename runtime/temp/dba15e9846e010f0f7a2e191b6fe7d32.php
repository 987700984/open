<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"D:\PHPTutorial\WWW\open\public/../application/admin\view\login.html";i:1531376799;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>比特信-糖果币开放管理平台</title>
    <link href="__CSS__/bootstrap.min.css" rel="stylesheet">
    <link href="__CSS__/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="__CSS__/animate.min.css" rel="stylesheet">
    <link href="__CSS__/style.min.css" rel="stylesheet">
    <link href="__CSS__/login.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>

    <style>

    </style>

</head>

<body class="signin">

    <div class="yy-wrapper">
        <div class="yy-main">
            <div class="yy-content">
                <div class="yy-box clearfix">
                    <div class="login-box box-sizing">
                        <form method="post" action="index.html">
                            <p class="login-title">欢迎登录糖果币开放管理平台</p>
                            <p id="err_msg" style="margin-top: 20px;"></p>
                            <div class="uname-box"><input type="text" class="uname" placeholder="请输入用户名" id="username" /> </div>
                            <div class="pword-box"><input type="password" class="pword" placeholder="******" id="password" /></div>
                            
                            <div class="yzm-box">
                                <input type="text" class="yzm" placeholder="验证码" name="code" id="code"/>
                                <img class="yzm_img" src="<?php echo url('checkVerify'); ?>" onclick="javascript:this.src='<?php echo url('checkVerify'); ?>?tm='+Math.random();" style="float:right;cursor: pointer; height:46px;"/>
                            </div>

                            <input class="login_btn float" type="button" id="login_btn" value="登录"/>
                            <input class="login_btn float" type="button" id="reg" value="代理商申请"/>

                        </form>
                    </div>
                    <div class="qr-box"><img src="__IMG__/qr_code_img.png" alt="二维码"><p>扫描二维码下载WE Token</p></div>
                </div>
                <div class="login-footer">
                  Copyright © 2016-2020  版权所有   深圳市比特信科技有限公司
                </div>
            </div>
        </div>
    </div>


<!--
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">
                <div class="logopanel m-b">
                </div>
                <div class="m-b"></div>
                <h4>糖果币开放管理平台</h4>
                <ul class="m-b">

                </ul>
            </div>
        </div>
        <div class="col-sm-5">
            <form method="post" action="index.html">
                <p class="m-t-md" id="err_msg">登录到 后台</p>
                <input type="text" class="form-control uname" placeholder="用户名" id="username" />
                <input type="password" class="form-control pword m-b" placeholder="密码" id="password" />
                <div style="margin-bottom:70px">
                    <input type="text" class="form-control" placeholder="验证码" style="color:black;width:120px;float:left;margin:0px 0px;" name="code" id="code"/>
                    <img src="<?php echo url('checkVerify'); ?>" onclick="javascript:this.src='<?php echo url('checkVerify'); ?>?tm='+Math.random();" style="float:right;cursor: pointer"/>
                </div>
                <input class="btn btn-success btn-block" id="login_btn" value="登录"/>
            </form>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
                           互联在线(股票代码:835727)旗下游戏：技术100%保障游戏安全、绝无外挂、自然随机的朋友圈棋牌游戏！
        </div>
    </div>
</div>
-->

<script src="__JS__/jquery.min.js?v=2.1.4"></script>
<script src="__JS__/bootstrap.min.js?v=3.3.6"></script>
<script type="text/javascript">
    document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==13){ // enter 键
            $('#login_btn').click();
        }
    };
    var lock = false;
    $(function () {
        $('#login_btn').click(function(){
            if(lock){
                return;
            }
            lock = true;
            $('#err_msg').hide();
            $('#login_btn').removeClass('btn-success').addClass('btn-danger').val('登陆中...');
            var username = $('#username').val();
            var password = $('#password').val();
            var code = $('#code').val();
            $.post("<?php echo url('login/doLogin'); ?>",{'username':username, 'password':password, 'code':code},function(data){
                lock = false;
                $('#login_btn').val('登录').removeClass('btn-danger').addClass('btn-success');
                if(data.code!=1){
                    $('#err_msg').show().html("<span style='color:red'>"+data.msg+"</span>");
                    return;
                }else{
                    window.location.href=data.data;
                }
            });
        });


        $('#reg').click(function(){
            window.location.href = "<?php echo url('login/reg'); ?>";
        })


    });
</script>
</body>
</html>