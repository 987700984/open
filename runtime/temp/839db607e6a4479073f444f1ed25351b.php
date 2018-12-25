<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\PHPTutorial\WWW\open\public/../application/goods\view\goods\goodsadd.html";i:1529574915;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加商品</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="__CSS__/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__CSS__/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="__CSS__/animate.min.css" rel="stylesheet">
    <link href="__CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__CSS__/bootstrap-fileinput.css" rel="stylesheet">
    <link href="__CSS__/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="__CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>添加商品</h5>
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
                            <label class="col-sm-3 control-label">商品名称：</label>
                            <div class="input-group col-sm-4">
                                <input id="goodsname" type="text" class="form-control" name="goodsname" required="" aria-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">商品分类：</label>
                            <div class="input-group col-sm-4">
                                <select name="cid" id="cid">
                                    <option value="0">请选择分类</option>
                                    <?php if(is_array($category) || $category instanceof \think\Collection): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['title']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">商品原价：</label>
                            <div class="input-group col-sm-4">
                                <input id="old_price" type="text" class="form-control" name="old_price" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">商品价格：</label>
                            <div class="input-group col-sm-4">
                                <input id="goodsprice" type="text" class="form-control" name="goodsprice" required="" aria-required="true">
                            </div>
                        </div>                                                      
                        <div class="form-group">
                            <label class="col-sm-3 control-label">商品库存：</label>
                            <div class="input-group col-sm-4">
                                <input id="total" type="text" class="form-control" name="total" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">限购数量：</label>
                            <div class="input-group col-sm-4">
                                <input id="fjed" type="text" class="form-control" name="fjed" required="" value="100" aria-required="true">（默认100）
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">运行周期：</label>
                            <div class="input-group col-sm-4">
                                <input id="yxzq" type="text" class="form-control" name="yxzq" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">矿机算力：</label>
                            <div class="input-group col-sm-4">
                                <input id="kjsl" type="text" class="form-control" name="kjsl" required="" aria-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">图片：</label>
                            <div class="fileinput fileinput-new" data-provides="fileinput"  id="exampleInputUpload">
                                <div class="fileinput-new thumbnail" style="width: 200px;height: auto;max-height:150px;">
                                    <img id='picImg' style="width: 100%;height: auto;max-height: 140px;" src="/static/admin/images/upload.jpg" alt="" />
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                <div>
                                    <span class="btn btn-primary btn-file">
                                        <span class="fileinput-new">选择文件</span>
                                        <span class="fileinput-exists">换一张</span>
                                        <input type="file" name="pic" id="picID" accept="image/gif,image/jpeg,image/x-png"  />
                                    </span>
                                    <a href="javascript:;" class="btn btn-warning fileinput-exists" data-dismiss="fileinput">移除</a>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">简介：</label>
                            <div class="input-group col-sm-8">
                                <textarea id="articlecontent" name="content" style="width:100%;height:400px;"></textarea>
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
<script src="__JS__/bootstrap-fileinput.js"></script>
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
<script src="__EDITOR__/kindeditor.js"></script>
<script>
    KindEditor.ready(function(K) {
        var options = {uploadJson:"<?php echo url('admin/login/urlupload'); ?>"};window.editor = K.create('#articlecontent',options);
    });
</script>
<script type="text/javascript">

    //表单提交
    function toVaild(){ 
        editor.sync();
        var jz;

        if ($('#cid').val() == 0) {
            alert("请选择分类");
            return false;
        };
        var url = "./goodsAdd";
        var fileObj = document.getElementById("picID").files[0]; // js 获取文件对象
        if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
            alert("请选择图片");
            return false;
        }

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
            },
            error: function(request) {
                layer.close(jz);
                swal("网络错误!", "", "error");
            },
            success: function(data) {
                //关闭加载层
                layer.close(jz);
                if(data.code == 0){
                    swal("新增成功", "", "success");
                }else{
                    swal("新增失败", "", "error");
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
