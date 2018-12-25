<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\PHPTutorial\WWW\open\public/../application/admin\view\agent\money_detail.html";i:1531810605;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>个人资金流水列表 </title>
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
            <h5>个人资金流水列表</h5>
        </div>
        <div class="ibox-content">
            <!--<div class="form-group clearfix col-sm-1">-->
                <!--<a href="./add"><button class="btn btn-outline btn-primary" type="button">添加总代理</button></a>-->
            <!--</div>-->
            <!--搜索框开始-->
            <form id='commentForm' role="form" method="post" class="form-inline"  >
                <div class="content clearfix m-b">
                    <label>冻结金额：</label> <span><?php echo $use['ice_money']; ?></span><label style="margin-left: 20px;">已提现金额：</label> <span><?php echo $use['has_money']; ?></span><label style="margin-left: 20px;">剩余金额：</label> <span><?php echo $use['money']; ?></span>

                </div>
            </form>
            <!--搜索框结束-->
            <div class="hr-line-dashed"></div>

            <div class="example-wrap">
                <div class="example">
                    <table id="cusTable" data-height="550">
                        <thead>
                        <th data-field="content">资金类型</th>
                        <th data-field="type">状态</th>
                        <th data-field="price">金额</th>
                        <th data-field="agentid">下级UID</th>
                        <th data-field="addime">时间</th>
                        <!--<th data-field="operate">操作</th>-->
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
            <ul id="treeType" class="ztree"></ul>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-4" style="margin-bottom: 15px">
            <input type="button" value="确认分配" class="btn btn-primary" id="postform"/>
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
            url: "./money_log", //获取数据的地址
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
                    type:$('#type').val(),
                    phone:$('#phone').val(),
                    uid:"<?php echo $uid; ?>"
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
        layer.confirm('确认删除此会员代理?', {icon: 3, title:'提示'}, function(index){
            //do something
            $.getJSON('./roleDel', {'id' : id}, function(res){
                if(res.code == 1){
                    layer.alert('删除成功', function(){
                        initTable();
                    });
                }else{
                    layer.alert('删除失败');
                }
            });

            layer.close(index);
        })

    }
    var index = '';
    var index2 = '';
    //分配权限
    function giveQx(id){
        $("#nodeid").val(id);
        //加载层
        index2 = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2

        //获取权限信息
        $.getJSON('./giveAccess', {'type' : 'get', 'id' : id}, function(res){
            layer.close(index2);
            if(res.code == 1){
                zNodes = JSON.parse(res.data);  //将字符串转换成obj

                //页面层
                index = layer.open({
                    type: 1,
                    area:['350px', '600px'],
                    title:'权限分配',
                    skin: 'layui-layer-demo', //加上边框
                    content: $('#role')
                });
                //设置位置
                layer.style(index, {
                    top: '150px'
                });

                //设置zetree
                var setting = {
                    check:{
                        enable:true
                    },
                    data: {
                        simpleData: {
                            enable: true
                        }
                    }
                };

                $.fn.zTree.init($("#treeType"), setting, zNodes);
                var zTree = $.fn.zTree.getZTreeObj("treeType");
                zTree.expandAll(true);

            }else{
                layer.alert(res.msg);
            }

        });
    }
    //确认分配权限
    $("#postform").click(function(){
        var zTree = $.fn.zTree.getZTreeObj("treeType");
        var nodes = zTree.getCheckedNodes(true);
        var NodeString = '';
        $.each(nodes, function (n, value) {
            if(n>0){
                NodeString += ',';
            }
            NodeString += value.id;
        });
        var id = $("#nodeid").val();
        //写入库
        $.post('./giveAccess', {'type' : 'give', 'id' : id, 'rule' : NodeString}, function(res){
            layer.close(index);
            if(res.code == 1){
                layer.alert(res.msg, function(){
                    initTable();
                });
            }else{
                layer.alert(res.msg);
            }

        }, 'json')
    })
</script>
</body>
</html>
