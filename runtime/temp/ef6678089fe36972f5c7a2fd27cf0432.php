<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"D:\PHPTutorial\WWW\open\public/../application/proxy\view\proxy\edit.html";i:1528004699;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑应用</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="__CSS__/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__CSS__/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="__CSS__/animate.min.css" rel="stylesheet">
    <link href="__CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__CSS__/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="__CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="__CSS__/bootstrap-fileinput.css" rel="stylesheet">

</head>
 <style>
         .checkbox-group input{display:none;opacity:0;}
        .checkbox-group input[type=checkbox]+label, .checkbox-group input[type=radio]+label {
            line-height: 1;
            position: relative;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            /*cursor: pointer;*/
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            margin:2px;
        }
        .checkbox-group input[type=checkbox]+label:before, .checkbox-group input[type=radio]+label:before {
            line-height: 20px;
            display: inline-block;
            width: 28px;
            height: 18px;
            margin-right: 8px;
            margin-top: 8px;
            content: '';
            color: #fff;
            border: 1px solid #dce4e6;
            background-color: #f3f6f8;
            border-radius: 3px;
        }
        .checkbox-group input[type=checkbox]:checked+label:before,.checkbox-group input[type=radio]:checked+label:before{
            /*content:'\2022';圆点*/
            content:'\2713';
            color:#fff;
            background-color: #31b968;
            border-radius: 5px;
            font-size:16px;
            text-align: center;
            border-color: #31b968;
        }
       
    </style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>编辑应用</h5>
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
                        <div class="form-group">
                            <label class="col-sm-2 control-label">应用名称：</label>
                            <div class="input-group col-sm-8">
                                <input id="name" type="text" class="form-control" name="name" style="width:100%;"required="" aria-required="true" value="<?php echo $name; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">释放周期：</label>
                            <div class="input-group col-sm-4">
                                
                                <input type="text" class="form-control" id="starttime" name="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'zh-cn'})" value="<?php echo $starttime; ?>">
                            </div>
                        </div>  

                        <div class="form-group">
                            <label class="col-sm-2 control-label">冻结时间：</label>
                            <div class="input-group col-sm-4">
                                
                                <input type="text" class="form-control" id="endtime" name="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'zh-cn'})" value="<?php echo $endtime; ?>">
                            </div>
                        </div>  
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">发币量：</label>
                            <div class="input-group col-sm-8">
                                <input id="num" type="text" class="form-control" name="num" style="width:100%;"required="" value="<?php echo $num; ?>" aria-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">兌换比例比：</label>
                            <div class="input-group col-sm-8">
                                <input id="ratio" type="text" class="form-control" name="ratio" style="width:100%;"required="" value="<?php echo $ratio; ?>" aria-required="true">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">审核状态：</label>
                            <div class="input-group col-sm-8">
                                <select name="status" id="">
                                    <option value="1" <?php if($status==1): ?> selected<?php endif; ?>>审核通过</option>
                                    <option value="2" <?php if($status==2): ?> selected<?php endif; ?>>审核未通过</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <div class="form-group">
                            <div class="col-sm-offset-2">
                                <!--<input type="button" value="提交" class="btn btn-primary" id="postform"/>-->
                                <div class="input-group col-sm-4">
                                    <button class="btn btn-primary" type="submit">提交</button>
                                </div>
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
<script src="__JS__/bootstrap-fileinput.js"></script>
<script src="__JS__/content.min.js?v=1.0.0"></script>
<script src="__JS__/plugins/validate/jquery.validate.min.js"></script>
<script src="__JS__/plugins/validate/messages_zh.min.js"></script>
<script src="__JS__/plugins/iCheck/icheck.min.js"></script>
<script src="__JS__/plugins/sweetalert/sweetalert.min.js"></script>
<script src="__JS__/plugins/layer/laydate/laydate.js"></script>
<script src="__JS__/plugins/suggest/bootstrap-suggest.min.js"></script>
<script src="__JS__/plugins/layer/layer.min.js"></script>
<script src="__JS__/plugins/My97DatePicker/WdatePicker.js"></script>

<script type="text/javascript">

    //表单提交
    function toVaild(){
        var jz;
        var url = "<?php echo url('proxy/edit'); ?>";
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
                if(data.code == 0){
                    swal("编辑成功", "", "success");
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

</script>
</body>
</html>