<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"D:\PHPTutorial\WWW\open\public/../application/admin\view\index\index.html";i:1533977274;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>糖果币开放管理平台</title>
    <link rel="shortcut icon" href="favicon.ico"> 
	<link href="/static/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/static/admin/js/plugins/layui/css/layui.css" rel="stylesheet">
    <link href="/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="/static/admin/css/style.min.css?v=4.1.0" rel="stylesheet">
</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <h2>糖果币开放管理平台</h2>
        <div class="row">
            <!-- <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>用户统计</h5>
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
                        <p>当前总用户<?php echo $user_all; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;今日新增<?php echo $user_today_reg; ?>  <?php echo strtotime('2018-05-26 14:00:00'); ?></p>
                        <a href="https://www.jiguang.cn/stat/#/app/ff234d08109531341d2c79ca/overview" target= _blank>查看详情</a>
                    </div>
                </div>
            </div> -->

            <!--<div class="col-sm-6">-->
                <!--<div class="ibox float-e-margins">-->
                    <!--<div class="ibox-title">-->
                        <!--<h5>矿机出币统计</h5>-->
                        <!--<div class="ibox-tools">-->
                            <!--<a class="collapse-link">-->
                                <!--<i class="fa fa-chevron-up"></i>-->
                            <!--</a>-->
                            <!--<a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">-->
                                <!--<i class="fa fa-wrench"></i>-->
                            <!--</a>-->
                            <!--<a class="close-link">-->
                                <!--<i class="fa fa-times"></i>-->
                            <!--</a>-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<div class="ibox-content" style="height:380px">-->
                        <!--<p>出币总数：<?php echo $money; ?></p>-->
                        <!--&lt;!&ndash; <a href="https://www.jiguang.cn/stat/#/app/ff234d08109531341d2c79ca/overview" target= _blank>查看详情</a> &ndash;&gt;-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->



            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>资金统计</h5>
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
                        <div>
                            <form id='commentForm' role="form" method="post" class="form-inline" >

                                        <select id="cid" class="form-control"   name="cid">
                                            <option value="" >请选择</option>
                                            <option value="1" >按小时</option>
                                            <option value="2" >按天数</option>
                                            <option value="3" >按月份</option>
                                        </select>

                                <button  class="btn btn-primary" type="button" style="margin-top:5px;margin-left: 20px;" id="search"><strong>搜 索</strong></button>

                            </form>
                        </div>
                        <div id="tixian" style="height:300px;"></div>
                    </div>
                </div>
            </div>



            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>系统回购统计</h5>
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
                        <div>
                            <form  role="form" method="post" class="form-inline" >

                                <select id="gid" class="form-control"   name="gid">
                                    <option value="" >请选择</option>
                                    <option value="1" >按小时</option>
                                    <option value="2" >按天数</option>
                                    <option value="3" >按月份</option>
                                </select>

                                <button  class="btn btn-primary" type="button" style="margin-top:5px;margin-left: 20px;" id="search1"><strong>搜 索</strong></button>

                            </form>
                        </div>
                        <div id="huigou" style="height:300px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>用户统计</h5>
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
                        <div>
                            <form  role="form" method="post" class="form-inline" >

                                <select id="uid" class="form-control"   name="uid">
                                    <option value="" >请选择</option>
                                    <option value="1" >按小时</option>
                                    <option value="2" >按天数</option>
                                    <option value="3" >按月份</option>
                                </select>

                                <button  class="btn btn-primary" type="button" style="margin-top:5px;margin-left: 20px;" id="search2"><strong>搜 索</strong></button>

                            </form>
                        </div>
                        <div id="yonghu" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>订单统计</h5>
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
                    <div>
                        <form  role="form" method="post" class="form-inline" >

                            <select id="oid" class="form-control"   name="oid">
                                <option value="" >请选择</option>
                                <option value="1" >按小时</option>
                                <option value="2" >按天数</option>
                                <option value="3" >按月份</option>
                            </select>

                            <button  class="btn btn-primary" type="button" style="margin-top:5px;margin-left: 20px;" id="search3"><strong>搜 索</strong></button>

                        </form>
                    </div>
                    <div id="dingdan" style="height:500px;"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="/static/admin/js/jquery.min.js?v=2.1.4"></script>
    <script src="/static/admin/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="/static/admin/js/content.min.js?v=1.0.0"></script>
    <script type="text/javascript" src="/static/admin/plugins/echarts/echarts.min.js"></script>
    <script src="/static/admin/js/plugins/layui/layui.js"></script>
    <script type="text/javascript" src="/static/admin/plugins/echarts/shine.js"></script>


       <script type="text/javascript">


layui.use('laydate', function(){
     laydate = layui.laydate;
});
//统计下拉事件
$('#oid').change(function () {
    var cid=$(this).val();
    $('#test6').remove();
    if(cid!=''){
        $(this).after('<input id="test6" type="text" style="margin-left: 20px;"/>');
        switch (cid){
            case '1':

                //日期范围选择
                laydate.render({
                    elem: '#test6'

                });
                break;
            case '2':
                laydate.render({
                    elem: '#test6'
                    ,type: 'month'

                });
                break;
            case '3':
                //年范围选择
                laydate.render({
                    elem: '#test6'
                    ,type: 'year'

                });
                break;
        }


    }
})

    //统计下拉事件
    $('#cid').change(function () {
        var cid=$(this).val();
        $('#test7').remove();
        if(cid!=''){
            $(this).after('<input id="test7" type="text" style="margin-left: 20px;"/>');
            switch (cid){
                case '1':

                    //日期范围选择
                    laydate.render({
                        elem: '#test7'

                    });
                    break;
                case '2':
                    laydate.render({
                        elem: '#test7'
                        ,type: 'month'

                    });
                    break;
                case '3':
                    //年范围选择
                    laydate.render({
                        elem: '#test7'
                        ,type: 'year'

                    });
                    break;
            }


        }
    })

//统计下拉事件
$('#gid').change(function () {
    var cid=$(this).val();
    $('#test8').remove();
    if(cid!=''){
        $(this).after('<input id="test8" type="text" style="margin-left: 20px;"/>');
        switch (cid){
            case '1':

                //日期范围选择
                laydate.render({
                    elem: '#test8'

                });
                break;
            case '2':
                laydate.render({
                    elem: '#test8'
                    ,type: 'month'

                });
                break;
            case '3':
                //年范围选择
                laydate.render({
                    elem: '#test8'
                    ,type: 'year'

                });
                break;
        }


    }
})


//统计下拉事件
$('#uid').change(function () {
    var cid=$(this).val();
    $('#test9').remove();
    if(cid!=''){
        $(this).after('<input id="test9" type="text" style="margin-left: 20px;"/>');
        switch (cid){
            case '1':

                //日期范围选择
                laydate.render({
                    elem: '#test9'

                });
                break;
            case '2':
                laydate.render({
                    elem: '#test9'
                    ,type: 'month'

                });
                break;
            case '3':
                //年范围选择
                laydate.render({
                    elem: '#test9'
                    ,type: 'year'

                });
                break;
        }


    }
})



       </script>

        <script type="text/javascript">
            function yonghu(d,monnth) {

                $.post('<?php echo url("index/yonghu"); ?>',{type:d,month:monnth},function (d) {
                    if(d){
                        var dom = document.getElementById("yonghu");
                        var myChart = echarts.init(dom);
                        var app = {};
                        option = null;
                        option = {
                            title : {
                                text: '用户统计'
                            },
                            tooltip : {
                                trigger: 'axis'
                            },
                            legend: {
                                data:['注册量','登录量']
                            },
                            toolbox: {
                                show : true,
                                feature : {
                                    mark : {show: true},
                                    dataView : {show: true, readOnly: false},
                                    magicType : {show: true, type: ['line', 'bar']},
                                    restore : {show: true},
                                    saveAsImage : {show: true}
                                }
                            },
                            calculable : true,
                            xAxis : [
                                {
                                    type : 'category',
                                    data : d.key
                                }
                            ],
                            yAxis : [
                                {
                                    type : 'value'
                                }
                            ],
                            series : [
                                {
                                    name:'注册量',
                                    type:'bar',
                                    data:d.tixian,
                                    markPoint : {
                                        data : [
                                            {type : 'max', name: '最大值'},
                                            {type : 'min', name: '最小值'}
                                        ]
                                    },

                                },
                                {
                                    name:'登录量',
                                    type:'bar',
                                    data:d.fanyong,
                                    markPoint : {
                                        data : [
                                            {type : 'max', name: '最大值'},
                                            {type : 'min', name: '最小值'}
                                        ]
                                    },

                                },

                            ]
                        };
                        if (option && typeof option === "object") {
                            myChart.setOption(option, true);
                        }
                    }

                })

            }


            //用户统计初始化
            yonghu(3,1);

            //用户统计按时间搜索点击事件
            $('#search2').click(function () {
                var type=$('#uid').val();
                var month=$('#test9').val();
                if(type=='' || month==''){
                    return false;
                }
                yonghu(type,month);
            })


        </script>

    <script type="text/javascript">
        function tixian(d,monnth) {

            $.post('<?php echo url("index/tixian"); ?>',{type:d,month:monnth},function (d) {
                if(d){
                    var dom = document.getElementById("tixian");
                    var myChart = echarts.init(dom);
                    var app = {};
                    option = null;
                    option = {
                        title : {
                            text: '用户资金流水'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['返佣','提现','收入','消费']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: true},
                                dataView : {show: true, readOnly: false},
                                magicType : {show: true, type: ['line', 'bar']},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        calculable : true,
                        xAxis : [
                            {
                                type : 'category',
                                data : d.key
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'返佣',
                                type:'bar',
                                data:d.fanyong,
                                markPoint : {
                                    data : [
                                        {type : 'max', name: '最大值'},
                                        {type : 'min', name: '最小值'}
                                    ]
                                },

                            },
                            {
                                name:'提现',
                                type:'bar',
                                data:d.tixian,
                                markPoint : {
                                    data : [
                                        {type : 'max', name: '最大值'},
                                        {type : 'min', name: '最小值'}
                                    ]
                                },

                            },
                            {
                                name:'收入',
                                type:'bar',
                                data:d.order,
                                markPoint : {
                                    data : [
                                        {type : 'max', name: '最大值'},
                                        {type : 'min', name: '最小值'}
                                    ]
                                },

                            },
                            {
                                name:'消费',
                                type:'bar',
                                data:d.xiaofei,
                                markPoint : {
                                    data : [
                                        {type : 'max', name: '最大值'},
                                        {type : 'min', name: '最小值'}
                                    ]
                                },

                            },

                        ]
                    };
                    if (option && typeof option === "object") {
                        myChart.setOption(option, true);
                    }
                }

            })

        }


        //资金统计初始化
        tixian(3,1);
            
        //资金统计按时间搜索点击事件
        $('#search').click(function () {
            var type=$('#cid').val();
            var month=$('#test7').val();
            if(type=='' || month==''){
                return false;
            }
            tixian(type,month);
        })
        

        </script>


    <script type="text/javascript">
        function huigou(d,monnth) {

            $.post('<?php echo url("index/huigou"); ?>',{type:d,month:monnth},function (d) {
                if(d){
                    var dom = document.getElementById("huigou");
                    var myChart = echarts.init(dom);
                    var app = {};
                    option = null;
                    option = {
                        title : {
                            text: '系统回购'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['已处理','未处理','已失败']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: true},
                                dataView : {show: true, readOnly: false},
                                magicType : {show: true, type: ['line', 'bar']},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        calculable : true,
                        xAxis : [
                            {
                                type : 'category',
                                data : d.key
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'已处理',
                                type:'bar',
                                data:d.fanyong,
                                markPoint : {
                                    data : [
                                        {type : 'max', name: '最大值'},
                                        {type : 'min', name: '最小值'}
                                    ]
                                },

                            },
                            {
                                name:'未处理',
                                type:'bar',
                                data:d.tixian,
                                markPoint : {
                                    data : [
                                        {type : 'max', name: '最大值'},
                                        {type : 'min', name: '最小值'}
                                    ]
                                },

                            },
                            {
                                name:'已失败',
                                type:'bar',
                                data:d.shibai,
                                markPoint : {
                                    data : [
                                        {type : 'max', name: '最大值'},
                                        {type : 'min', name: '最小值'}
                                    ]
                                },

                            },

                        ]
                    };
                    if (option && typeof option === "object") {
                        myChart.setOption(option, true);
                    }
                }

            })

        }


        //资金统计初始化
        huigou(3,1);

        //资金统计按时间搜索点击事件
        $('#search1').click(function () {
            var type=$('#gid').val();
            var month=$('#test8').val();
            if(type=='' || month==''){
                return false;
            }
            huigou(type,month);
        })


    </script>



    <script type="text/javascript">
        //订单统计
        function dingdan(d,monnth) {

            $.post('<?php echo url("index/dingdan"); ?>',{type:d,month:monnth},function (d) {
                if(d){
                    var dom = document.getElementById("dingdan");
                    var myChart = echarts.init(dom);
                    var app = {};
                    option = null;
                    option = {
                        title : {
                            text: '订单统计'
                        },
                        tooltip : {
                            trigger: 'axis',
                            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                            }
                        },
                        legend: {
                            data: ['已取消', '待付款','已付款','已发货','已收货']
                        },
                        grid: {
                            left: '5%',
                            right: '1%',
                            bottom: '1%',
                            containLabel: true
                        },
                        xAxis:  {
                            type: 'value'
                        },
                        yAxis: {
                            type: 'category',
                            data: d.key
                        },
                        series: [
                            {
                                name: '已取消',
                                type: 'bar',
                                stack: '总量',
                                label: {
                                    normal: {
                                        show: true,
                                        position: 'insideRight'
                                    }
                                },
                                data: d.quxiao
                            },
                            {
                                name: '待付款',
                                type: 'bar',
                                stack: '总量',
                                label: {
                                    normal: {
                                        show: true,
                                        position: 'insideRight'
                                    }
                                },
                                data: d.daifu
                            },
                            {
                                name: '已付款',
                                type: 'bar',
                                stack: '总量',
                                label: {
                                    normal: {
                                        show: true,
                                        position: 'insideRight'
                                    }
                                },
                                data: d.yifu
                            },
                            {
                                name: '已发货',
                                type: 'bar',
                                stack: '总量',
                                label: {
                                    normal: {
                                        show: true,
                                        position: 'insideRight'
                                    }
                                },
                                data: d.yifa
                            },
                            {
                                name: '已收货',
                                type: 'bar',
                                stack: '总量',
                                label: {
                                    normal: {
                                        show: true,
                                        position: 'insideRight'
                                    }
                                },
                                data: d.yishou
                            }
                        ]
                    };
                    if (option && typeof option === "object") {
                        myChart.setOption(option, true);
                    }
                }

            })

        }


        //用户统计初始化
        dingdan(3,1);

        //用户统计按时间搜索点击事件
        $('#search3').click(function () {
            var type=$('#oid').val();
            var month=$('#test6').val();
            if(type=='' || month==''){
                return false;
            }
            dingdan(type,month);
        })


    </script>
</body>
</html>
