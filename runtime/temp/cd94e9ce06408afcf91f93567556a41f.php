<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\PHPTutorial\WWW\open\public/../application/admin\view\user\useredit.html";i:1531733137;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑管理员</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="__CSS__/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__CSS__/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="__CSS__/animate.min.css" rel="stylesheet">
    <link href="__CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__CSS__/style.min.css?v=4.1.0" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="__CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>编辑管理员</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="commentForm" method="post" onsubmit="return toVaild()">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">管理员名称：</label>
                            <div class="input-group col-sm-4">
                                <input id="username" type="text" class="form-control" name="username" required="" aria-required="true" value="<?php echo $user['username']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">管理员角色：</label>
                            <div class="input-group col-sm-4">
                                <select class="form-control" name="typeid" required="" aria-required="true">
                                    <option value="0">请选择</option>
                                    <?php if(!empty($role)): if(is_array($role) || $role instanceof \think\Collection): if( count($role)==0 ) : echo "" ;else: foreach($role as $key=>$vo): ?>
                                    <option value="<?php echo $vo['id']; ?>" <?php if($user['typeid'] == $vo['id']): ?>selected<?php endif; ?>><?php echo $vo['rolename']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                                </select>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label">登录密码：</label>
                            <div class="input-group col-sm-4">
                                <input id="password" type="text" class="form-control" name="password"  placeholder="再次输入修改密码">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">推荐人（手机号）：</label>
                            <div class="input-group col-sm-4">
                                <input id="rtid" type="text" class="form-control" name="rtid" required="" aria-required="true" value="<?php echo $user['rtid']; ?>" placeholder="请填写手机号">

                            </div>
                        </div>




                        <div class="form-group">
                            <label class="col-sm-3 control-label">余额：</label>
                            <div class="input-group col-sm-4">
                                <input id="money" type="number" class="form-control" name="money" required="" aria-required="true" value="<?php echo $user['money']; ?>">

                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-sm-3 control-label">userid：</label>
                            <div class="input-group col-sm-4" style="display: inline-block; float: left;">
                                <input id="userid" type="text" class="form-control" name="userid" required="" value="<?php echo $user['userid']; ?>" aria-required="true">

                            </div>
                            <span class="tishileft"></span>
                        </div> -->                        
                        <!--<div class="form-group">-->
                            <!--<label class="col-sm-3 control-label">充值房卡数量：</label>-->
                            <!--<div class="input-group col-sm-4">-->
                                <!--<input id="cardcount" type="text" class="form-control" name="cardcount" value="<?php echo $user['cardcount']; ?>" required=""  aria-required="true">-->

                            <!--</div>-->
                        <!--</div>-->

                        <div class="form-group">
                            <label class="col-sm-3 control-label">允许交易：</label>
                            <div class="input-group col-sm-4">
                                <div class="radio i-checks col-sm-4">
                                    <label><input type="radio" value="1" <?php if($user['ispay']): ?>checked<?php endif; ?> checked  name="ispay"> <i></i> 是</label>
                                </div>
                                <div class="radio i-checks col-sm-4">
                                    <label><input type="radio" value="0" <?php if(!$user['ispay']): ?>checked<?php endif; ?> name="ispay"> <i></i> 否</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否登录后台：</label>
                            <div class="input-group col-sm-4">
                                <?php if(is_array($status) || $status instanceof \think\Collection): if( count($status)==0 ) : echo "" ;else: foreach($status as $key=>$vo): ?>
                                <div class="radio i-checks col-sm-4">
                                    <label>
                                        <input type="radio" value="<?php echo $key; ?>" <?php if($key == $user['status']): ?>checked<?php endif; ?> name="status"> <i></i> <?php echo $vo; ?></label>
                                </div>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">账号封禁：</label>
                            <div class="input-group col-sm-4">
                                <div class="radio i-checks col-sm-4">
                                    <label><input type="radio" value="1" <?php if($user['state']): ?>checked<?php endif; ?> checked  name="state"> <i></i> 否</label>
                                </div>
                                <div class="radio i-checks col-sm-4">
                                    <label><input type="radio" value="0" <?php if(!$user['state']): ?>checked<?php endif; ?> name="state"> <i></i> 是</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-3">
                                <!--<input type="button" value="提交" class="btn btn-primary" id="postform"/>-->
                                <button class="btn btn-primary" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="__JS__/jquery.min.js?v=2.1.4"></script>
<script src="__JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__JS__/content.min.js?v=1.0.0"></script>
<script src="__JS__/plugins/validate/jquery.validate.min.js"></script>
<script src="__JS__/plugins/validate/messages_zh.min.js"></script>
<script src="__JS__/plugins/iCheck/icheck.min.js"></script>
<script src="__JS__/plugins/sweetalert/sweetalert.min.js"></script>
<script src="__JS__/plugins/layer/laydate/laydate.js"></script>
<script src="__JS__/plugins/suggest/bootstrap-suggest.min.js"></script>
<script src="__JS__/plugins/layer/layer.min.js"></script>
<script type="text/javascript">

    //表单提交
    function toVaild(){
        var jz;
        var url = "./useredit";
        $.ajax({
            type:"POST",
            url:url,
            data:{'data' : $('#commentForm').serialize()},// 你的formid
            async: false,
            beforeSend:function(){
                jz = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
            },
            error: function(request) {
                layer.close(jz);
                swal("网络错误!", "", "error");
            },
            success: function(data) {
                //关闭加载层
                layer.close(jz);
                if(data.code == 1){
                    swal(data.msg, "", "success");
                }else{
                    swal(data.msg, "", "error");
                }

            }
        });

        return false;
    }

    //表单验证
    $(document).ready(function(){
        $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",});
    });
    $.validator.setDefaults({
        highlight: function(e) {
            $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
        },
        success: function(e) {
            e.closest(".form-group").removeClass("has-error").addClass("has-success")
        },
        errorElement: "span",
        errorPlacement: function(e, r) {
            e.appendTo(r.is(":radio") || r.is(":checkbox") ? r.parent().parent().parent() : r.parent())
        },
        errorClass: "help-block m-b-none",
        validClass: "help-block m-b-none"
    });


    $(function(){
       $("#userid").blur(function(){
        var userid= $("#userid").val();
        if(userid==''){
            $('.tishileft').text('userid不能为空');
            return false;
        }
        $.ajax({
            type:'post',
            async:false,
            url:'<?php echo url('Login/checkuserid'); ?>',
            data:{'userid':userid},
            success:function(data){
                if(data.code == 1){
                    $('.tishileft').text(data.msg); 
                    $('.btn-primary').removeAttr('disabled');                  
                }else{
                    $('.tishileft').text(data.msg); 
                    $('.btn-primary').attr('disabled','disabled');
                    return false;                   
                }
                                    
            }

        });
        return false;

      });  

       $("#userid").focus(function(){
        $('.tishileft').text('');
       })

    })

</script>
</body>
</html>
