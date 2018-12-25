<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"D:\PHPTutorial\WWW\open\public/../application/shop\view\orders\detail.html";i:1532675638;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>订单详情</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/static/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="/static/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/static/admin/css/style.min.css?v=4.1.0" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <style>
        .th{padding: 10px 10px}
        .td{padding: 10px 10px}
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

                        <div class="form-group">
                            <label class="col-sm-3 control-label">订单号：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo $info['oid']; ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">商品名称：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo $info->title; ?></span>
                            </div>
                        </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">合计：</label>
                        <div class="input-group col-sm-4">
                            <span><?php echo $info->total_money; ?></span>
                        </div>
                    </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">收货地址：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo $info['address']; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">订单状态：</label>
                            <div class="input-group col-sm-4">
                                <span><?php echo $info['state']; ?></span>
                            </div>
                        </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">备注：</label>
                        <div class="input-group col-sm-4">
                            <span><?php echo !empty($info['msg'])?$info['msg']:'没有备注'; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">快递单号：</label>
                        <div class="input-group col-sm-4">
                            <span><?php echo (isset($info['courier']) && ($info['courier'] !== '')?$info['courier']:''); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">下单时间：</label>
                        <div class="input-group col-sm-4">
                            <span><?php echo $info['uptime']; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">订单商品：</label>
                        <div class="input-group col-sm-4">
                            <span></span>
                        </div>
                    </div>
                    <div class="form-group">
                       <table>
                           <tbody>
                                <thead><th class="th">商品名称</th><th class="th">商品价格</th><th class="th">商品数量</th><th class="th">商品规格</th></thead>
                           <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                           <tr><td class="td"><?php echo $vo['name']; ?></td><td class="td"><?php echo $vo['money']; ?></td><td class="td"><?php echo $vo['num']; ?></td><td class="td"><?php echo $vo['key']; ?></td></tr>
                           <?php endforeach; endif; else: echo "" ;endif; ?>
                           </tbody>
                       </table>
                        <div class="input-group col-sm-4">
                            <span></span>
                        </div>
                    </div>





                </div>
            </div>

        </div>
    </div>
</div>


</body>
</html>
