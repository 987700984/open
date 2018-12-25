<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"D:\PHPTutorial\WWW\open\public/../application/goods\view\goods\goodsadd.html";i:1533521041;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加商品</title>
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
                    <form class="form-horizontal m-t layui-form" id="commentForm" method="post" onsubmit="return toVaild()">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">商品名称：</label>
                            <div class="input-group col-sm-4">
                                <input id="goodsname" type="text" class="form-control" name="goodsname" required="" aria-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">商品分类：</label>
                            <div class="input-group col-sm-4">
                                <select name="cid" id="cid"  lay-filter="cid">
                                    <option value="0">请选择分类</option>
                                    <?php if(is_array($category) || $category instanceof \think\Collection || $category instanceof \think\Paginator): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['title']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="model" style="display: none">
                            <label class="col-sm-3 control-label">商品模型：</label>
                            <div class="input-group col-sm-4">
                                <select name="tid" id="tid" lay-filter="tid">
                                    <option value="0">请选择模型</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="gui" style="display: none" >

                            <div class="input-group col-sm-10 layui-input-block">
                                <label class="col-sm-2 control-label ">商品规格:</label>

                            </div>
                        </div>

                        <div class="form-group layui-form-item" id="guige" lay-filter="guige" style="display: none" >

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
                                <input id="yxzq" type="text" class="form-control" name="yxzq" required="" value="0" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">矿机算力：</label>
                            <div class="input-group col-sm-4">
                                <input id="kjsl" type="text" class="form-control" name="kjsl" required="" value="0" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">回本天数：</label>
                            <div class="input-group col-sm-4">
                                <input id="month" type="text" class="form-control" value="" name="month" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">代理等级：</label>
                            <div class="input-group col-sm-4">
                                <input id="agent_level" type="text" class="form-control" name="agent_level" required="" value="0" aria-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">商品状态：</label>
                            <div class="input-group col-sm-4">
                                <select  name="goodsstatus" required="" aria-required="true">
                                    <option value="">-请选择-</option>
                                    <option value="0" >启用</option>
                                    <option value="1">停用</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否计算分佣：</label>
                            <div class="input-group col-sm-4">
                                <select  name="iscommission" required="" aria-required="true">
                                    <option value="">-请选择-</option>
                                    <option value="0" >不计算</option>
                                    <option value="1" >计算</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否虚拟产品：</label>
                            <div class="input-group col-sm-4">
                                <select  name="is_virtual" required="" aria-required="true">
                                    <option value="0" >虚拟商品</option>
                                    <option value="1" >实体商品</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">支付方式：</label>
                            <div class="input-group col-sm-4">

                                    <input type="checkbox" lay-filter="pay" value="0" name="pay_type[]"  title="支付宝"/>
                                    <input type="checkbox" lay-filter="pay" value="1" name="pay_type[]"  title="余额"/>
                                    <input type="checkbox" lay-filter="pay" value="2" name="pay_type[]"  title="btjz"/>
                                    <input type="checkbox" lay-filter="pay" value="3" name="pay_type[]"  title="ETH"/>
                           
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">图片：</label>
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
                            <label class="col-sm-3 control-label">简介：</label>
                            <div class="input-group col-sm-8">
                                <textarea id="articlecontent" name="content" style="width:100%;height:400px;"></textarea>
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
    function CartesianProduct(arr) {

        var res = new Array();

        for(var keys=0;keys<arr.length;keys++) {

            if(res.length != 0){
                var tmp = new Array();

                for(var i=0;i<res.length;i++) {
                    for(var j=0;j<arr[keys].length;j++) {
                        tmp.push(res[i]+','+arr[keys][j]);
                    }
                }

                res = tmp;
            }else{
                res = arr[keys];
            }
        }
        return res;
    }

    layui.use('form', function() {
        var form = layui.form;
        form.on('select(cid)', function(data){
            if(data.value){

                $.post("<?php echo url('admin/index/get_goods_type'); ?>",{cid:data.value},function (d) {

                    if(d){
                        $('#model').show();
                        $('#tid').empty();
                        $('#tid').append('<option value="0">请选择模型</option>');
                        var len=d.length;
                        for(var i=0;i<len;i++){
                            $('#tid').append('<option value="' + d[i]['tid'] + '">'  + d[i]['goodsname'] + '</option>');
                        }
                        form.render('select');

                    }else{
                        $('#model').hide();
                        $('#gui').hide();
                        $('#guige').hide();
                    }
                })
            }

        });

        form.on('select(tid)', function(data){

            if(data.value){
                $.post("<?php echo url('admin/index/get_spec'); ?>",{tid:data.value},function (d) {
                    var len=d.length;
                    if(d.length>0){
                        $('#gui').show();
                        $('#guige').show();

                        var l='';
                        for(var i=0;i<len;i++){
                            l+='<div class="input-group col-sm-10 layui-input-block shu">';
                            l+=' <label class="col-sm-2 control-label" title="'+d[i]['spec']+'">'+d[i]['spec']+':</label>';
                            var le=d[i]['spec_item'].length;
                            for (var k=0;k<le;k++){
                                l+=' <input type="checkbox" lay-filter="spec" class="spec'+i+'" name="spec[]" value="'+d[i]['spec_item'][k]['itemid']+'" title="'+d[i]['spec_item'][k]['item_name']+'">';
                            }
                            l+='</div>';
                        }

                        $('#guige').html(l);
                        form.render();
                    }

                })
            }

        });
        form.on('checkbox(spec)', function (data) {


            var th = '<table id="cusTable" data-height="550"><tbody><thead>';
            var ind = '';
            var shu = $('.shu');
            var a = new Array();
            var c = new Array();
            for (var i = 0; i < shu.length; i++) {
                var len = shu.eq(i).children('input:checked').length;
                var chil = shu.eq(i).children('input:checked');
                var b = new Array();
                var d = new Array();
                if (len > 0) {

                    for (var k = 0; k < len; k++) {

                        b.push(chil.eq(k).attr('title'));
                        d.push(chil.eq(k).val());
                    }
                    th += '<th class="th">' + shu.eq(i).children('label').attr('title') + '</th>';
                    a.push(b);
                    c.push(d);

                }

            }
            th += '<th class="th">原价</th><th class="th">现价</th><th class="th">库存</th></thead>';
//            console.log(a);
            a = CartesianProduct(a);
            c=CartesianProduct(c);
//            console.log(a);
//
//            console.log(c);
            if (a.length > 0) {

                for (var i = 0; i < a.length; i++) {
                    c[i]=c[i].split(',');
                    ind += '<input type="hidden" name="ind[' + i + ']" value="' + c[i] + '"/>';
                    var tr = '<tr>';

                    if (!(a[i] instanceof Array)) {
                        a[i]=a[i].split(',');

                        for (var k = 0; k < a[i].length; k++) {
                            tr += '<td class="td">' + a[i][k] + '</td>'
                        }

                    }


                    tr += '<td class="td"><input required="" aria-required="true" class="form-control" name="like[market_price][]/"></td><td class="td"><input required="" class="form-control" aria-required="true" name="like[price][]/"></td><td class="td"><input class="form-control" required="" aria-required="true" name="like[store_count][]/"></td>'
                    tr += '</tr>';
                    th += tr;
                }

                th += '</tbody></table>';

                $('#biao').show();
                $('#biao').html(th);
                $('#ind').html(ind);
            } else {
                $('#biao').html('');
                $('#ind').html('');
            }

        });
    })




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
