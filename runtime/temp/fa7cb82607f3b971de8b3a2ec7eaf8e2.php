<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"D:\PHPTutorial\WWW\open\public/../application/admin\view\c2cbuy\det.html";i:1532314661;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>订单详情</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="__CSS__/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__CSS__/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="__CSS__/animate.min.css" rel="stylesheet">
    <link href="__CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__CSS__/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="__CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <style>
        #cusTable .container-item{
            width: 50px;
        }
        #showImg{
            display: none;
            width: 600px;
            position: absolute;
            top:10px;
            left: 50%;
            margin-left: -300px;
        }
        #showImg img{
            width: 600px;
        }
        #close{
            font-size: 50px;
            color: #FFFFFF;
            position: absolute;
            top:0px;
            right: 15px;
            padding: 0;
            margin: 0;
            cursor:pointer;
        }
    </style>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>订单详情</h5>
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
                            <label class="col-sm-2 control-label">订单编号：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['cid']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">用户ID：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['uid']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">币种：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['s_name']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">数量：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['num']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">单价：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['num']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">总价：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['total_money']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">联系方式：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['phone']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">支付宝账号：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['zhifu_id']; ?></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">支付宝姓名：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['zhifu_name']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">支付宝二维码：</label>
                            <div class="input-group col-sm-8">
                                <img class="img" src="<?php echo $res['zhifu_code']; ?>" bigurl="<?php echo $res['zhifu_code']; ?>" height="100px"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">微信账号：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['weixin_id']; ?></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">微信昵称：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['weixin_name']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">微信二维码：</label>
                            <div class="input-group col-sm-8">
                                <img class="img" src="<?php echo $res['weixin_code']; ?>" bigurl="<?php echo $res['weixin_code']; ?>" height="100px"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">开户行地址：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['yh_add']; ?></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">银行卡号：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['yh_id']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">持卡人姓名：</label>
                            <div class="input-group col-sm-8">
                                <span><?php echo $res['yh_name']; ?></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-offset-2">
                                <!--<input type="button" value="提交" class="btn btn-primary" id="postform"/>-->
                                <div class="input-group col-sm-4">
                                    <button class="btn btn-primary" type="button" onclick="edit(1)" style="padding-right: 20px">通过</button>
                                    <button class="btn btn-primary" type="button" onclick="edit(2)" style="padding-right: 20px">人工打款</button>
                                    <button class="btn btn-primary" type="button" onclick="edit(0)">不通过</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="showImg"><span id="close">X</span></span><img src="" /></div>

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
<script src="__JS__/plugins/My97DatePicker/WdatePicker.js"></script>

<script>
    // 图片展示
    $(function () {
        $('.img').on('click',function () {
            var imgUrl = $(this).attr("bigUrl");
            $('#showImg img').attr("src",imgUrl);
            $('#showImg').show();

        });
        $('#showImg').on('click','#close',function () {
            $('#showImg').hide();

        });
    });
</script>

<script type="text/javascript">
    //表单提交
    function edit(id){
        var jz;
        var url = "<?php echo url('c2cbuy/edit'); ?>";
        $.ajax({
            type:"POST",
            url:url,
            data:{'id':'<?php echo $res['id']; ?>','status':id},// 你的formid
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



</script>
</body>
</html>
