<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"D:\PHPTutorial\WWW\open\public/../application/bill\view\bill\out.html";i:1533611877;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>币流水导出</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/static/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="/static/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/static/admin/css/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="/static/admin/css/bootstrap-fileinput.css" rel="stylesheet">

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
                    <h5>币流水导出</h5>
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
                    <form class="form-horizontal m-t" id="commentForm" method="post">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">币种名称：</label>
                            <div class="input-group col-sm-8">
                                <select name="soretype" id="billname">
                                    <option value="0">全部</option>
                                    <?php if(is_array($soretype) || $soretype instanceof \think\Collection || $soretype instanceof \think\Paginator): $i = 0; $__LIST__ = $soretype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">用户手机号/ID：</label>
                            <div class="input-group col-sm-8">
                                <input id="phone" type="text" class="form-control" name="phone" style="width:100%;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">开始时间：</label>
                            <div class="input-group col-sm-4">

                                <input type="text" class="form-control" id="starttime" name="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'zh-cn'})" value="<?php echo date('Y-m-d H:i:s',strtotime(date("Y-m-d"),time())); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">结束时间：</label>
                            <div class="input-group col-sm-4">

                                <input type="text" class="form-control" id="endtime" name="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'zh-cn'})" value="<?php echo date('Y-m-d H:i:s',time()); ?>">
                            </div>
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

<script src="/static/admin/js/plugins/My97DatePicker/WdatePicker.js"></script>


</body>
</html>
