<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\PHPTutorial\WWW\open\public/../application/orders\view\ordertype\index.html";i:1528004699;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>订单类型管理</title>
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
            <h5>订单类型管理</h5>
        </div>
        <div class="ibox-content">
            <div class="form-group clearfix col-sm-1">
                 <a href="<?php echo url('ordertype/add'); ?>"><button class="btn btn-outline btn-primary" type="button">添加订单类型</button></a>
            </div>
            <!--搜索框开始-->
            <form id='commentForm' role="form" method="post" class="form-inline">
                <div class="content clearfix m-b">
                    <div class="form-group">
                        <label>名称：</label>
                        <input type="text" class="form-control" id="goodsname" name="goodsname">
                    </div>
                    <div class="form-group">
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
                        <th data-field="id">序号</th>
                        <th data-field="name">名称</th>
                        <th data-field="operate" width="50">操作</th>
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
<script src="__JS__/jquery.min.js?v=2.1.4"></script>
<script src="__JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__JS__/content.min.js?v=1.0.0"></script>
<script src="__JS__/plugins/bootstrap-table/bootstrap-table.min.js"></script>
<script src="__JS__/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
<script src="__JS__/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
<script src="__JS__/plugins/suggest/bootstrap-suggest.min.js"></script>
<script src="__JS__/plugins/layer/laydate/laydate.js"></script>
<script src="__JS__/plugins/sweetalert/sweetalert.min.js"></script>
<script src="__JS__/plugins/layer/layer.min.js"></script>

<script type="text/javascript">
    function initTable() {
        //先销毁表格
        $('#cusTable').bootstrapTable('destroy');
        //初始化表格,动态从服务器加载数据
        $("#cusTable").bootstrapTable({
            method: "get",  //使用get请求到服务器获取数据
            url: "./index", //获取数据的地址
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
                    type:$('#type').val(),
                    pageNumber: params.pageNumber,
                    pageSize: params.pageSize,
                    searchText:$('#goodsname').val(),
                    
                };
                return param;
            },
            onLoadSuccess: function(data){  //加载成功时执行
                console.log(data);
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

    function del(id){
        layer.confirm('确认删除此订单?', {icon: 3, title:'提示'}, function(index){
            //do something
            $.getJSON("<?php echo url('ordertype/del'); ?>", {'id' : id}, function(res){
                if(res.code == 0){
                    layer.alert('删除成功');
                    initTable();
                }else{
                    layer.alert('删除失败');
                }
            });

            layer.close(index);
        })

    }

    function ordersEdit(id){
        layer.confirm('确认该订单已发货?', {icon: 3, title:'提示'}, function(index){
            //do something
            $.getJSON('./ordersEdit', {'id':id}, function(res){
                // console.log(res);
                if(res.code == 1){
                    layer.alert('发货成功');
                    initTable();
                }else{
                    layer.alert('发货失败');
                }
            });

            layer.close(index);
        })
    }

    function goods(id) {
        $.getJSON("<?php echo url('orders/getGoods'); ?>",{'id':id},function(res){
            var str = '<table class="table"><tr><td>名称</td><td>数量</td><td>单价</td></tr>';
            if(res.code == 0){

                for (var i=0;i<res.data.length;i++){
                    str += '<tr><td>'+res.data[i].name+'</td><td>'+res.data[i].num+'</td><td>'+res.data[i].money+'</td></tr>';
                }
                str += '</table>'
                
                //页面层
                layer.open({
                  type: 1,
                  title: '商品明细表',
                  content: str
                });

            }else{
               layer.alert(res.msg); 
            }
        });

    }
</script>
</body>
</html>