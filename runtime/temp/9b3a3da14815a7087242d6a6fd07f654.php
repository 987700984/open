<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\PHPTutorial\WWW\open\public/../application/notice\view\notice\noticeadd.html";i:1530328900;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加公告</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/static/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="/static/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/static/admin/css/bootstrap-fileinput.css" rel="stylesheet">
    <link href="/static/admin/css/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>添加公告</h5>
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
                            <label class="col-sm-2 control-label">选择币种：</label>
                            <div class="input-group col-sm-8">
                                <select name="sid">
                                    <?php if(is_array($soretype) || $soretype instanceof \think\Collection || $soretype instanceof \think\Paginator): $i = 0; $__LIST__ = $soretype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">公告名称：</label>
                            <div class="input-group col-sm-8">
                                <input id="title" type="text" class="form-control" name="title" style="width:100%;"required="" aria-required="true">
                            </div>
                        </div>
                       
                        
                        <!--<div class="form-group">-->
                            <!--<label class="col-sm-2 control-label">图片：</label>-->
                            <!--<div class="fileinput fileinput-new" data-provides="fileinput"  id="exampleInputUpload">-->
                                <!--<div class="fileinput-new thumbnail" style="width: 200px;height: auto;max-height:150px;">-->
                                    <!--<img id='picImg' style="width: 100%;height: auto;max-height: 140px;" src="/static/admin/images/upload.jpg" alt="" />-->
                                <!--</div>-->
                                <!--<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>-->
                                <!--<div>-->
                                    <!--<span class="btn btn-primary btn-file">-->
                                        <!--<span class="fileinput-new">选择文件</span>-->
                                        <!--<span class="fileinput-exists">换一张</span>-->
                                        <!--<input type="file" name="pic" id="picID" accept="image/gif,image/jpeg,image/x-png"  />-->
                                    <!--</span>-->
                                    <!--<a href="javascript:;" class="btn btn-warning fileinput-exists" data-dismiss="fileinput">移除</a>-->
                                <!--</div>-->
                            <!--</div>-->

                        <!--</div>-->

                        <div class="form-group">
                            <label class="col-sm-2 control-label">公告内容：</label>
                            <div class="input-group col-sm-8">
                                <textarea id="articlecontent" name="content" style="width:100%;height:400px;"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">推荐：</label>
                            <div class="input-group col-sm-8">
                                <select name="recommend" id="">
                                    <option value="0" >不推荐</option>
                                    <option value="1" >推荐</option>
                                </select>
                            </div>
                        </div>                           
                        <div class="form-group">
                            <div class="col-sm-offset-2">
                                <!--<input type="button" value="提交" class="btn btn-primary" id="postform"/>-->
                                <div class="input-group col-sm-8">
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
<script src="/static/admin/js/plugins/My97DatePicker/WdatePicker.js"></script>
<script src="/static/editor/kindeditor.js"></script>
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
        var url = "./noticeAdd";
        // var fileObj = document.getElementById("picID").files[0]; // js 获取文件对象
        // if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
        //     alert("请选择图片");
        //     return;
        // }

        $.ajax({
            type:"POST",
            url:url,
            data:$('#commentForm').serialize(),// 你的formid
            dataType : "json",
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
