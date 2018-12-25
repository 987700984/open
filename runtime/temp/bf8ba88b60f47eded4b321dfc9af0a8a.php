<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\PHPTutorial\WWW\open\public/../application/admin\view\agent\agent_tixian.html";i:1531725077;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会员提现列表</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="__CSS__/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__CSS__/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="__CSS__/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link href="__CSS__/animate.min.css" rel="stylesheet">
    <link href="__CSS__/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="__CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>会员提现列表</h5>
        </div>
        <div class="ibox-content">
            <!--<div class="form-group clearfix col-sm-1">-->
                <!--<a href="./add"><button class="btn btn-outline btn-primary" type="button">添加总代理</button></a>-->
            <!--</div>-->
            <!--搜索框开始-->
            <form id='commentForm' role="form" method="post" class="form-inline"  >
                <div class="content clearfix m-b">
                    <div class="form-group" style="margin-left:30px;">
                        <label>会员手机号（会员ID）：</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group" style="margin-left:30px;">
                        <label>提现类型：</label>
                       <select id="level" name="type">
                           <option value="">请选择</option>
                           <option value="1">支付宝</option>
                           <option value="2">银行卡</option>
                           <option value="3">微信</option>
                       </select>
                    </div>

                    <div class="form-group" style="margin-left:30px;">
                        <label>提现状态：</label>
                        <select id="ti_status" class="form-control" name="ti_status">
                            <option value="">请选择</option>
                            <option value="0">待提现</option>
                            <option value="1">提现成功</option>
                            <option value="2">审核不通过</option>
                            <option value="3">后台手动发放</option>
                        </select>
                    </div>
                    <!--<div class="form-group" style="margin-left:30px;">-->
                        <!--<label>币种：</label>-->
                        <!--<select class="form-control" id="sid" name="sid">-->
                            <!--<option value="">请选择</option>-->
                            <!--<?php if(is_array($coin) || $coin instanceof \think\Collection): $i = 0; $__LIST__ = $coin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
                            <!--<option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>-->
                            <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                        <!--</select>-->
                    <!--</div>-->
                    <div class="form-group" style="margin-left: 30px;" >
                        <button class="btn btn-primary" type="button" style="margin-top:5px" id="search"><strong>搜 索</strong>
                        </button>
                    </div>
                </div>
            </form>
            <!--搜索框结束-->
            <div class="hr-line-dashed"></div>

            <div class="example-wrap">
                <div class="example">
                    <table id="cusTable" data-height="550">
                        <thead>
                        <th data-field="uid">会员ID</th>
                        <th data-field="phone">会员手机</th>
                        <th data-field="type">提现类型</th>
                        <th data-field="ali_phone">支付宝账号</th>
                        <th data-field="real_name">真实姓名</th>
                        <th data-field="bank_type">银行卡类型</th>
                        <th data-field="bank_number">银行卡账号</th>
                        <th data-field="amount">提现金额</th>
                        <th data-field="poundage">手续费</th>
                        <th data-field="ti_status">状态<th>
                        <th data-field="addtime">时间</th>
                        <th data-field="operate">操作</th>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- End Example Pagination -->
        </div>
    </div>
</div>
<!-- End Panel Other -->
</div>
<!-- 会员代理分配 -->
<div class="zTreeDemoBackground left" style="display: none" id="role">
    <input type="hidden" id="nodeid">
    <div class="form-group">
        <div class="col-sm-5 col-sm-offset-2">
           <textarea name="content" cols="40" rows="8" class="layui-textarea" id="content"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-4" style="margin-bottom: 15px">
            <input type="button" value="确认" class="btn btn-primary" id="postform"/>
        </div>
    </div>
</div>
<script type="text/javascript">
    zNodes = '';
</script>
<script src="__JS__/jquery.min.js"></script>
<script src="__JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__JS__/content.min.js?v=1.0.0"></script>
<script src="__JS__/plugins/bootstrap-table/bootstrap-table.min.js"></script>
<script src="__JS__/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
<script src="__JS__/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
<script src="__JS__/plugins/suggest/bootstrap-suggest.min.js"></script>
<script src="__JS__/plugins/layer/laydate/laydate.js"></script>
<script src="__JS__/plugins/sweetalert/sweetalert.min.js"></script>
<script src="__JS__/plugins/layer/layer.min.js"></script>
<link rel="stylesheet" href="__JS__/plugins/zTree/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="__JS__/plugins/zTree/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="__JS__/plugins/zTree/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="__JS__/plugins/zTree/jquery.ztree.exedit-3.5.js"></script>
<script type="text/javascript">
    function initTable() {
        //先销毁表格
        $('#cusTable').bootstrapTable('destroy');
        //初始化表格,动态从服务器加载数据
        $("#cusTable").bootstrapTable({
            method: "get",  //使用get请求到服务器获取数据
            url: "./agent_tixian", //获取数据的地址
            striped: true,  //表格显示条纹
            pagination: true, //启动分页
            pageSize: 10,  //每页显示的记录数
            pageNumber:1, //当前第几页
            pageList: [5, 10, 15, 20, 25],  //记录数可选列表
            sidePagination: "server", //表示服务端请求
            //设置为undefined可以获取pageNumber，pageSize，searchText，sortName，sortOrder
            //设置为limit可以获取limit, offset, search, sort, order
            queryParamsType : "undefined",
            queryParams: function queryParams(params) {   //设置查询参数
                var param = {
                    pageNumber: params.pageNumber,
                    pageSize: params.pageSize,
                    searchText:$('#rolename').val(),
                    type:$('#level').val(),
                    phone:$('#phone').val(),
                    ti_status:$('#ti_status').val(),
//                    sid:$('#sid').val()
                };
                return param;
            },
            onLoadSuccess: function(res){  //加载成功时执行

                layer.msg("加载成功", {time : 1000});
            },
            onLoadError: function(){  //加载失败时执行
                layer.msg("加载数据失败");
            }
        });
    }

    $(document).ready(function () {
        //调用函数，初始化表格
        initTable();

        //当点击查询按钮的时候执行
        $("#search").bind("click", initTable);

    });

    function roleDel(id){
        layer.confirm('确认通过提现吗?', {icon: 3, title:'提示'}, function(index){
//            layer.alert('功能在开发中');return;
            //do something
            $.post('./up_status', {'id' : id}, function(res){
                if(res.status == 1){
                    layer.msg(res.msg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){
                        initTable();
                    });

                }else{
                    layer.msg(res.msg);
                }
            });

            layer.close(index);
        })

    }
    var index = '';
    var index2 = '';
    function NDel(id){
        layer.confirm('确认手动提现吗?', {icon: 3, title:'提示'}, function(index){
//            layer.alert('功能在开发中');return;
            //do something
            $.post('./person_pay', {'id' : id}, function(res){
                if(res.status == 1){
                    layer.msg(res.msg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){
                        initTable();
                    });

                }else{
                    layer.msg(res.msg);
                }
            });

            layer.close(index);
        })

    }
    function NoDel(id){
        $("#nodeid").val(id);
        //加载层
//         //0代表加载的风格，支持0-2
        layer.open({
            type: 1,
            title:'不通过的理由',
            area: ['400px', '300px'],
            content: $('#role') //这里content是一个DOM，注意：最好该元素要存放在body最外层，否则可能被其它的相对元素所影响
        });

    }

    $("#postform").click(function(){
        index2 = layer.load(0, {shade: false});
        var id = $("#nodeid").val();
        var content=$("#content").val();
        //写入库
        $.post('./up_status', { id : id, content : content}, function(res){

            if(res.code == 1){
                  window.location.reload();
            }

        }, 'json')
    })
</script>
</body>
</html>
