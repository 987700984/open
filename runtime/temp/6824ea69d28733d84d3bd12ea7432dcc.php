<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\PHPTutorial\WWW\open\public/../application/soretype\view\soretype\index.html";i:1533265565;}*/ ?>
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
            <h5>币种设置</h5>
        </div>
        <div class="ibox-content">
            <div class="form-group clearfix col-sm-2">
                <a href="./config"><button class="btn btn-outline btn-primary" type="button">糖果中心配置</button></a>
                <a href="./soreAdd"><button class="btn btn-outline btn-primary" type="button">添加币种</button></a>
            </div>
            <!--搜索框开始-->
            <form id='commentForm' role="form" method="post" class="form-inline">
                <div class="content clearfix m-b">
                    <div class="form-group" style="margin-left:30px;">
                        <label>币种名称：</label>
                        <select id="level" name="level">
                            <option value="">请选择</option>
                            <?php if(is_array($level) || $level instanceof \think\Collection || $level instanceof \think\Paginator): $i = 0; $__LIST__ = $level;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
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
                        <th data-field="id">ID</th>
                        <th data-field="name">币种名称</th>
                        <th data-field="lv1">注册糖果</th>
                        <th data-field="lv2">一级推荐</th> 
                        <th data-field="lv3">二级推荐</th>   

                        <th data-field="exchange">兑换比例</th>
                        <th data-field="exchange_num">最低兑换数量</th>
                        <th data-field="time">开始兑换时间</th>

                        <th data-field="proc">C2C交易手续费比例</th>
                        <th data-field="min_proc">C2C交易最低手续费</th>
                        <th data-field="max_proc">C2C交易最高手续费</th>
                        <th data-field="min_num">C2C交易最低转账数量</th>

                        <th data-field="give">转账手续费比例</th>
                        <th data-field="min_give">转账最低手续费</th>
                        <th data-field="max_give">转账最高手续费</th>
                        <th data-field="num_give">转账最低转账数量</th>
                        <th data-field="poundage">提现手续费（%）</th>
                        <th data-field="min_poundage">最低提现额度</th>
                        <th data-field="num">每日限额</th> 
                        <th data-field="status">状态</th>
                        <!-- <th data-field="orderscreatetime">创建时间</th>
                        <th data-field="orderscreatepersonid">创建人ID</th> -->                                             
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
                    pageNumber: params.pageNumber,
                    pageSize: params.pageSize,
                    sid:$('#level').val(),
                };
                return param;
            },
            onLoadSuccess: function(){  //加载成功时执行
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

    function soreDel(id){
        layer.confirm('确认删除此订单?', {icon: 3, title:'提示'}, function(index){
            //do something
            $.getJSON('./soreDel', {'id' : id}, function(res){
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
</script>
</body>
</html>
