<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\PHPTutorial\WWW\open\public/../application/goods\view\goods\edit_spec.html";i:1533291398;}*/ ?>
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
        .th {
            padding: 5px 5px
        }

        .td {
            padding: 5px 5px
        }
    </style>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>编辑商品规格</h5>
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
                    <form class="form-horizontal m-t layui-form" id="commentForm" method="post"
                          onsubmit="return toVaild()">
                        <input type="hidden" name="goodsid" value="<?php echo $goods['goodsid']; ?>">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品分类：</label>
                            <div class="input-group col-sm-4">
                                <select name="cid" id="cid" lay-filter="cid">
                                    <option value="0">请选择分类</option>
                                    <?php if(is_array($category) || $category instanceof \think\Collection || $category instanceof \think\Paginator): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $vo['id']; ?>" <?php if($vo['id'] == $goods['cid']): ?>selected<?php endif; ?>><?php echo $vo['title']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="model" <?php if($goods['tid'] == 0): ?> style="display: none" <?php endif; ?>>
                        <label class="col-sm-2 control-label">商品模型：</label>
                        <div class="input-group col-sm-4">
                            <select name="tid" id="tid" lay-filter="tid">
                                <option value="0">请选择模型</option>
                                <?php if(is_array($goodtype) || $goodtype instanceof \think\Collection || $goodtype instanceof \think\Paginator): $i = 0; $__LIST__ = $goodtype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option <?php if($goods['tid'] == $vo['tid']): ?>selected="selected" <?php endif; ?> value="<?php echo $vo['tid']; ?>"><?php echo $vo['goodsname']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                </div>

                <div class="form-group" id="gui" <?php if($lis != true): ?>style="display: none" <?php endif; ?>>

                <div class="input-group col-sm-10 layui-input-block">
                    <label class="col-sm-1 control-label ">商品规格:</label>

                </div>
            </div>

            <div class="form-group layui-form-item" id="guige" lay-filter="guige" <?php if($lis != true): ?>style="display: none" <?php endif; ?>>
            <?php if($list == true): if(is_array($lis) || $lis instanceof \think\Collection || $lis instanceof \think\Paginator): $i = 0; $__LIST__ = $lis;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <div class="input-group col-sm-10 layui-input-block shu">
                <label class="col-sm-1 control-label" title="<?php echo $vo['spec']['spec_name']; ?>"><?php echo $vo['spec']['spec_name']; ?>:</label>
                <?php if(is_array($vo['item']) || $vo['item'] instanceof \think\Collection || $vo['item'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['item'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <input type="checkbox" lay-filter="spec" <?php if(in_array($v['itemid'],json_decode($goods['spec_item']))): ?>checked<?php endif; ?> name="spec[]" value="<?php echo $v['itemid']; ?>" title="<?php echo $v['item_name']; ?>"/>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="input-group col-sm-8" id="biao">
                <table id="cusTable" data-height="550">
                    <tbody>
                    <?php if($lis == true): ?>
                    <thead>

                    <?php if(is_array($lis) || $lis instanceof \think\Collection || $lis instanceof \think\Paginator): $i = 0; $__LIST__ = $lis;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <th class="th"><?php echo $vo['spec']['spec_name']; ?></th>
                    <?php endforeach; endif; else: echo "" ;endif; ?>

                    <th class="th">原价</th>
                    <th class="th">现价</th>
                    <th class="th">库存</th>
                    </thead>
                    <?php endif; if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <?php if(is_array($vo['key']) || $vo['key'] instanceof \think\Collection || $vo['key'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['key'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                        <td class="td"><?php echo $v; ?></td>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <td class="td"><input required="" aria-required="true" class="form-control"
                                              name="like[market_price][]" value="<?php echo $vo['market_price']; ?>"/></td>
                        <td class="td"><input required="" aria-required="true" class="form-control" name="like[price][]"
                                              value="<?php echo $vo['price']; ?>"/></td>
                        <td class="td"><input required="" aria-required="true" class="form-control"
                                              name="like[store_count][]" value="<?php echo $vo['store_count']; ?>"/></td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="form-group" id="ind">
            <?php if($list == true): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>


            <input type="hidden" name="ind[]" value="<?php echo $vo['key_id']; ?>"/>


            <?php endforeach; endif; else: echo "" ;endif; endif; ?>
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
<script src="/static/admin/js/plugins/suggest/bootstrap-suggest.min.js"></script>
<script src="/static/admin/js/plugins/layer/layer.min.js"></script>
<script src="/static/admin/js/plugins/layui/layui.js"></script>


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


    layui.use('form', function () {
        var form = layui.form;
        form.on('select(cid)', function (data) {

            $('#model').hide();
            $('#tid').empty();
            $('#tid').append('<option value="0">请选择模型</option>');
            $('#guige').hide();
            $('#guige').html('');
            $('#gui').hide();
            $('#biao').hide();
            $('#biao').html('');
            if (data.value) {

                $.post("<?php echo url('admin/index/get_goods_type'); ?>", {cid: data.value}, function (d) {

                    if (d) {
                        $('#model').show();
                        $('#tid').empty();
                        $('#tid').append('<option value="0">请选择模型</option>');

                        var len = d.length;
                        for (var i = 0; i < len; i++) {

                            $('#tid').append('<option value="' + d[i]['tid'] + '">' + d[i]['goodsname'] + '</option>');

                        }

                        form.render();
                    }


                })
            }

        });

        form.on('select(tid)', function (data) {
            $('#gui').hide();
            $('#guige').hide();
            $('#biao').hide();
            $('#guige').html('');
            $('#biao').html('');
            if (data.value) {
                $.post("<?php echo url('admin/index/get_spec'); ?>", {tid: data.value}, function (d) {
                    var len = d.length;
                    if (d.length > 0) {
                        $('#gui').show();
                        $('#guige').show();

                        var l = '';
                        for (var i = 0; i < len; i++) {
                            l += '<div class="input-group col-sm-10 layui-input-block shu">';
                            l += ' <label class="col-sm-2 control-label" title="' + d[i]['spec'] + '">' + d[i]['spec'] + ':</label>';
                            var le = d[i]['spec_item'].length;
                            for (var k = 0; k < le; k++) {
                                l += ' <input type="checkbox" lay-filter="spec" class="spec' + i + '" name="spec[]" value="' + d[i]['spec_item'][k]['itemid'] + '" title="' + d[i]['spec_item'][k]['item_name'] + '">';
                            }
                            l += '</div>';
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
    function toVaild() {
//       e.preventDefault();
        if ($('#cid').val() == 0) {
            alert("请选择分类");
            return false;
        }
        ;
        var jz;
        var url = "<?php echo url('goods/edit_spec'); ?>";

        $.ajax({
            type: "POST",
            url: url,
            data: {data: $('#commentForm').serialize()},// 你的formid
            beforeSend: function () {
                jz = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
            }, error: function (request) {
                layer.close(jz);
                swal("网络错误!", "", "error");
            },
            success: function (data) {
                //关闭加载层
                layer.close(jz);
                if (data.code == 0) {
                    swal("修改成功", "", "success");
                } else {
                    swal("修改失败", "", "error");
                }

            }
        });

        return false;
    }

    //表单验证
    $(document).ready(function () {
        $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",});
    });
    $.validator.setDefaults({
        highlight: function (e) {
            $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
        },
        success: function (e) {
            e.closest(".form-group").removeClass("has-error").addClass("has-success")
        },
        errorElement: "span",
        errorPlacement: function (e, r) {
            e.appendTo(r.is(":radio") || r.is(":checkbox") ? r.parent().parent().parent() : r.parent())
        },
        errorClass: "help-block m-b-none",
        validClass: "help-block m-b-none"
    });

</script>
</body>
</html>
