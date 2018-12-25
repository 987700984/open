<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"D:\PHPTutorial\WWW\open\public/../application/admin\view\card\index.html";i:1531303004;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>实名审核列表</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/static/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/static/admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link href="/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="/static/admin/css/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <style>
        #cusTable .container-item{
            width: 50px;
        }
        #showImg{
            display: none;
            width: 600px;
            position: absolute;
            top:10px;
            left: 50%;
            margin-left: -300px;
        }
        #showImg img{
            width: 600px;
        }
        #close{
            font-size: 50px;
            color: #FFFFFF;
            position: absolute;
            top:0px;
            right: 15px;
            padding: 0;
            margin: 0;
            cursor:pointer;
        }
    </style>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>实名审核列表</h5>
        </div>
        <div class="ibox-content">
            <div class="form-group clearfix col-sm-1">
                <!--<a href="./userAdd"><button class="btn btn-outline btn-primary" type="button">添加用户</button></a>-->
            </div>
            <!--搜索框开始-->
            <form id='commentForm' role="form" method="post" class="form-inline">
                <div class="content clearfix m-b">
                    <div class="form-group">
                        <label>用户ID</label>
                        <input type="text" class="form-control" id="username" name="username">
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
                        <th data-field="uid">用户ID</th>
                        <th data-field="name">真实姓名</th>
                        <th data-field="idcar">身份证号码</th>
                        <th data-field="pic">手持身份证</th>
                        <th data-field="pic1">身份证正面</th>
                        <th data-field="pic2">身份证反面</th>
                        <th data-field="status">状态</th>
                        <th data-field="operate">操作</th>
                        </thead>
                    </table>

                    <div id="showImg"><span id="close">X</span></span><img src="" /></div>

                </div>
            </div>
            <!-- End Example Pagination -->
        </div>
    </div>
</div>
<!-- End Panel Other -->
</div>

<!--<div class="layui-row" id="test" style="display: none;">-->
    <!--<div class="layui-col-md10">-->

            <!--<div class="form-group">-->
                <!--<label class="col-sm-2 control-label"></label>-->
                <!--<div class="input-group col-sm-8" style="margin-top: 20px;">-->
                    <!--<textarea id="titl"  class="form-control" name="titl" style="width:100%;"></textarea>-->
                <!--</div>-->
            <!--</div>-->

            <!--&lt;!&ndash;<div class="form-group">&ndash;&gt;-->
                <!--&lt;!&ndash;<div class="col-sm-offset-2">&ndash;&gt;-->
                    <!--&lt;!&ndash;&lt;!&ndash;<input type="button" value="提交" class="btn btn-primary" id="postform"/>&ndash;&gt;&ndash;&gt;-->
                    <!--&lt;!&ndash;<div class="input-group col-sm-8">&ndash;&gt;-->
                        <!--&lt;!&ndash;<button class="btn btn-primary" id="notong" type="button">提交</button>&ndash;&gt;-->
                    <!--&lt;!&ndash;</div>&ndash;&gt;-->
                <!--&lt;!&ndash;</div>&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->

    <!--</div>-->
<!--</div>-->
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
                    searchText:$('#username').val()
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

    function edit(uid,s){
        layer.confirm('确认修改？', {icon: 3, title:'提示'}, function(index){
            //do something
            $.post("<?php echo url('card/edit'); ?>", {'uid' : uid,'status':s}, function(res){
                if(res.code == 1){
                    layer.alert('修改成功');
                    initTable();
                }else{
                    layer.alert('修改失败');
                }
            });

            layer.close(index);
        })
    }




</script>
<script>

        function edit1(uid,s) {
            layer.open({
                type: 1,
                title: "不通过原因",
                skin: "myclass",
                area: ["40%",'40%'],
                content: '<div class="form-group"><label class="col-sm-2 control-label"></label><div class="input-group col-sm-8" style="margin-top: 20px;"><textarea id="titl" cols="10" rows="8"  class="form-control" name="titl" style="width:100%;"></textarea></div></div>',
                btn:['确定'],
                yes:function (index, layero) {
                    console.log($('#titl'));
                    var conten=$('#titl').val();
                    console.log(conten);
                    if(conten==''){
                        layer.msg('原因不能为空');
                        return false;
                    }
                    $.post("<?php echo url('card/edit'); ?>", {'uid': uid, 'status': s,'content':conten}, function (res) {
                        if (res.code == 1) {
                            layer.alert('修改成功');
                            var index = parent.layer.getFrameIndex(window.name);
                            layer.closeAll();
                            initTable();
                        } else {
                            layer.alert('修改失败');
                        }
                    })
                }
            })




        }

</script>

<script>
    // 图片展示
$(function () {
    $('#cusTable').on('click','.container-item',function () {
        var imgUrl = $(this).attr("bigUrl");
        $('#showImg img').attr("src",imgUrl);
        $('#showImg').show();

    });
    $('#showImg').on('click','#close',function () {
        $('#showImg').hide();

    });
});
</script>

</body>
</html>
