<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\PHPTutorial\WWW\open\public/../application/orders\view\orders\index.html";i:1533978343;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>订单管理</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/static/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/static/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link href="/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="/static/admin/css/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>订单管理</h5>
        </div>
        <div class="ibox-content">
            <div class="form-group clearfix col-sm-1">
                <!-- <a href="./ordersAdd"><button class="btn btn-outline btn-primary" type="button">添加订单</button></a> -->
            </div>
            <!--搜索框开始-->
            <form id='commentForm' role="form" method="post" class="form-inline">
                <div class="content clearfix m-b">
                    <div class="form-group">
                        <label>类型：</label>
                        <select name="type" id="type" class="form-control">
                            <option value="0">全部</option>
                            <?php if(is_array($type) || $type instanceof \think\Collection || $type instanceof \think\Paginator): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>数据来源：</label>
                        <input type="text" class="form-control" id="forr" name="forr">
                    </div>
                    <div class="form-group">
                        <label>订单状态：</label>
                        <select name="state" class="form-control" id="state">
                            <option value="all">全部</option>
                            <option value="3">已发货</option>
                            <option value="2">已付款</option>
                            <option value="1">待付款</option>
                            <option value="0">无效订单</option>
                            <option value="-1">已退款</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label>订单号/联系方式：</label>
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
                        <th data-field="oid">订单号</th>
                        <th data-field="forr">数据来源</th>
                        <th data-field="type">类型</th>
                        <th data-field="title">商品名称</th>
                        <th data-field="total_money">合计</th>  
                        <th data-field="address">收货地址</th> 
                        <th data-field="state">订单状态</th>
                        <th data-field="uptime">创建时间</th>
                        <th data-field="name">联系人</th> 
                        <th data-field="phone">联系方式</th> 
                        <th data-field="goods">商品明细</th>                                           
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
<script src="/static/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/static/admin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/static/admin/js/content.min.js?v=1.0.0"></script>
<script src="/static/admin/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
<script src="/static/admin/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
<script src="/static/admin/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
<script src="/static/admin/js/plugins/suggest/bootstrap-suggest.min.js"></script>
<script src="/static/admin/js/plugins/layer/laydate/laydate.js"></script>
<script src="/static/admin/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="/static/admin/js/plugins/layer/layer.min.js"></script>

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
                    forr:$('#forr').val(),
                    state:$('#state').val(),
                    
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

    function ordersDel(ordersid){
        layer.confirm('确认删除此订单?', {icon: 3, title:'提示'}, function(index){
            //do something
            $.getJSON('./ordersDel', {'ordersid' : ordersid}, function(res){
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
