<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\PHPTutorial\WWW\open\public/../application/goods\view\goods\goodsedit.html";i:1533522899;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑商品</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/static/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="/static/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/static/admin/css/bootstrap-fileinput.css" rel="stylesheet">
    <link href="/static/admin/css/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="/static/admin/js/plugins/layui/css/layui.css" rel="stylesheet">
    <link href="/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <style>
        .th{padding: 5px 5px}
        .td{padding: 5px 5px}
    </style>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>编辑商品</h5>
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
                    <form class="form-horizontal m-t layui-form" id="commentForm" method="post" onsubmit="return toVaild()">
                        <input type="hidden" name="goodsid" value="<?php echo $goodsid; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品名称：</label>
                            <div class="input-group col-sm-4">
                                <input id="goodsname" type="text" class="form-control" name="goodsname" required="" aria-required="true" value="<?php echo $goodsname; ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品原价：</label>
                            <div class="input-group col-sm-4">
                                <input id="old_price" type="text" class="form-control" name="old_price" required="" value="<?php echo $old_price; ?>" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品价格：</label>
                            <div class="input-group col-sm-4">
                                <input id="goodsprice" type="text" class="form-control" name="goodsprice" required="" aria-required="true" value="<?php echo $goodsprice; ?>">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品库存：</label>
                            <div class="input-group col-sm-4">
                                <input id="total" type="text" class="form-control" name="total" value="<?php echo $total; ?>" required="" aria-required="true">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">限购数量：</label>
                            <div class="input-group col-sm-4">
                                <input id="fjed" type="text" class="form-control" value="<?php echo $fjed; ?>" name="fjed" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">运行周期：</label>
                            <div class="input-group col-sm-4">
                                <input id="yxzq" type="text" class="form-control" value="<?php echo $yxzq; ?>" name="yxzq" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">矿机算力：</label>
                            <div class="input-group col-sm-4">
                                <input id="kjsl" type="text" class="form-control" value="<?php echo $kjsl; ?>" name="kjsl" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">回本天数：</label>
                            <div class="input-group col-sm-4">
                                <input id="month" type="text" class="form-control" value="<?php echo $month; ?>" name="month" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">代理等级：</label>
                            <div class="input-group col-sm-4">
                                <input id="agent_level" type="text" class="form-control" value="<?php echo $agent_level; ?>" name="agent_level" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品状态：</label>
                            <div class="input-group col-sm-4">
                                <select  name="goodsstatus" required="" aria-required="true">
                                    <option value="">-请选择-</option>
                                    <option value="0" <?php if($goodsstatus == 0): ?>selected<?php endif; ?>>启用</option>
								    <option value="1" <?php if($goodsstatus == 1): ?>selected<?php endif; ?>>停用</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否计算分佣：</label>
                            <div class="input-group col-sm-4">
                                <select  name="iscommission" required="" aria-required="true">
                                    <option value="">-请选择-</option>
                                    <option value="0" <?php if($iscommission == 0): ?>selected<?php endif; ?>>不计算</option>
                                    <option value="1" <?php if($iscommission == 1): ?>selected<?php endif; ?>>计算</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否虚拟产品：</label>
                            <div class="input-group col-sm-4">
                                <select  name="is_virtual" required="" aria-required="true">
                                    <option value="0"  <?php if($goods['is_virtual'] == 0): ?>selected<?php endif; ?>>虚拟商品</option>
                                    <option value="1" <?php if($goods['is_virtual'] == 1): ?>selected<?php endif; ?>>实体商品</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">支付方式：</label>
                            <div class="input-group col-sm-4">

                                <input type="checkbox" lay-filter="pay" <?php if($goods['pay_type'] == true): if(in_array(0,json_decode($goods['pay_type'],true))): ?>checked<?php endif; endif; ?> value="0" name="pay_type[]"  title="支付宝"/>
                                <input type="checkbox" lay-filter="pay" <?php if($goods['pay_type'] == true): if(in_array(1,json_decode($goods['pay_type'],true))): ?>checked<?php endif; endif; ?> value="1" name="pay_type[]"  title="余额"/>
                                <input type="checkbox" lay-filter="pay" <?php if($goods['pay_type'] == true): if(in_array(2,json_decode($goods['pay_type'],true))): ?>checked<?php endif; endif; ?> value="2" name="pay_type[]"  title="btjz"/>
                                <input type="checkbox" lay-filter="pay" <?php if($goods['pay_type'] == true): if(in_array(3,json_decode($goods['pay_type'],true))): ?>checked<?php endif; endif; ?> value="3" name="pay_type[]"  title="ETH"/>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">图片：</label>
                            <div class="fileinput fileinput-new" data-provides="fileinput"  id="exampleInputUpload">
                                <div class="fileinput-new thumbnail" style="width: 200px;height: auto;max-height:150px;">
                                    <img id='picImg' style="width: 100%;height: auto;max-height: 140px;" src="<?php echo $pic; ?>" alt="" />
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                <div>
                                    <span class="btn btn-primary btn-file">
                                        <span class="fileinput-new">选择文件</span>
                                        <span class="fileinput-exists">换一张</span>
                                        <input type="file" name="pic" id="picID" value="<?php echo $pic; ?>" accept="image/gif,image/jpeg,image/x-png"  />
                                    </span>
                                    <a href="javascript:;" class="btn btn-warning fileinput-exists" data-dismiss="fileinput">移除</a>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">简介：</label>
                            <div class="input-group col-sm-8">
                                <textarea id="articlecontent" name="content" style="width:100%;height:400px;"><?php echo $content; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="col-sm-3 control-label"></label>
                            <div class="input-group col-sm-8" id="biao">

                            </div>
                        </div>
                        <div class="form-group"  id="ind">

                        </div>
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
<script src="/static/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/static/admin/js/bootstrap-fileinput.js"></script>
<script src="/static/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/static/admin/js/content.min.js?v=1.0.0"></script>
<script src="/static/admin/js/plugins/validate/jquery.validate.min.js"></script>
<script src="/static/admin/js/plugins/validate/messages_zh.min.js"></script>
<script src="/static/admin/js/plugins/iCheck/icheck.min.js"></script>
<script src="/static/admin/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="/static/admin/js/plugins/layer/laydate/laydate.js"></script>
<script src="/static/admin/js/plugins/suggest/bootstrap-suggest.min.js"></script>
<script src="/static/admin/js/plugins/layer/layer.min.js"></script>
<script src="/static/admin/js/plugins/layui/layui.js"></script>
<script src="/static/admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<script src="/static/editor/kindeditor.js"></script>
<script>
    KindEditor.ready(function(K) {
        var options = {uploadJson:"<?php echo url('admin/login/urlupload'); ?>"};window.editor = K.create('#articlecontent',options);
    });
</script>

<script type="text/javascript">


    layui.use('form', function() {
        var form = layui.form;

    })

    //表单提交
    function toVaild(){
        editor.sync();

        var jz;
        var url = "./goodsEdit";
        var fileObj = document.getElementById("picID").files[0]; // js 获取文件对象
        
        var formFile = new FormData();
        var data = formFile;
        // alert(data)
        formFile.append("data", $('#commentForm').serialize());  
        formFile.append("file", fileObj); //加入文件对象
        $.ajax({
            type:"POST",
            url:url,
            data:data,// 你的formid
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend:function(){
                jz = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
            },            error: function(request) {
                layer.close(jz);
                swal("网络错误!", "", "error");
            },
            success: function(data) {
                //关闭加载层
                layer.close(jz);
                if(data.code == 0){
                    swal("修改成功", "", "success");
                }else{
                    swal("修改失败", "", "error");
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
