<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"D:\PHPTutorial\WWW\open\public/../application/admin\view\user\detail.html";i:1533026084;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户详情</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/static/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="/static/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/static/admin/css/style.min.css?v=4.1.0" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>用户详情</h5>
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
                        <div class="form-group">
                            <label class="col-sm-3 control-label">名称：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo $user['username']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">角色名称：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo $user->ustype->rolename; ?></span>
                            </div>
                        </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">手机号：</label>
                        <div class="input-group col-sm-4">
                            <span><?php echo (isset($user->phone) && ($user->phone !== '')?$user->phone:'0'); ?></span>
                        </div>
                    </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">推荐人：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo !empty($user['rtid'])?$user['rtid']:'0'; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">二级推荐人：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo !empty($user['rtid2'])?$user['rtid2']:'0'; ?></span>
                            </div>
                        </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">一级好友人数：</label>
                        <div class="input-group col-sm-4">
                            <span><a href="<?php echo url('user/rtid_list',['id'=>$user['id'],'typ'=>1]); ?>"><?php echo $user['rtidcount']; ?></a></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">二级好友人数：</label>
                        <div class="input-group col-sm-4">
                            <span><a href="<?php echo url('user/rtid_list',['id'=>$user['id'],'typ'=>2]); ?>"><?php echo $user['rtid2count']; ?></a></span>
                        </div>
                    </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">余额：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo (isset($user['money']) && ($user['money'] !== '')?$user['money']:'0.00'); ?></span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否允许交易：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo $user['ispay']==1?'是':'否'; ?></span>
                            </div>
                        </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">账号封禁：</label>
                        <div class="input-group col-sm-4">
                            <span><?php echo $user['state']==1?'是':'否'; ?></span>
                        </div>
                    </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">最后登录IP：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo (isset($user['last_login_ip']) && ($user['last_login_ip'] !== '')?$user['last_login_ip']:'0.0.0.0'); ?></span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">最后登录时间：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo date("Y-m-d H:i:s",$user['last_login_time']); ?></span>
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







                </div>
            </div>

        </div>
    </div>
</div>
<script src="/static/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/static/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/static/admin/js/content.min.js?v=1.0.0"></script>
<script src="/static/admin/js/plugins/validate/jquery.validate.min.js"></script>
<script src="/static/admin/js/plugins/validate/messages_zh.min.js"></script>
<script src="/static/admin/js/plugins/iCheck/icheck.min.js"></script>
<script src="/static/admin/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="/static/admin/js/plugins/layer/laydate/laydate.js"></script>
<script src="/static/admin/js/plugins/suggest/bootstrap-suggest.min.js"></script>
<script src="/static/admin/js/plugins/layer/layer.min.js"></script>
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
