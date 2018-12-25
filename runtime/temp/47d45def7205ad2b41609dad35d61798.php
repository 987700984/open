<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"D:\PHPTutorial\WWW\open\public/../application/admin\view\index\index.html";i:1530494339;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>糖果币开放管理平台</title>
    <link rel="shortcut icon" href="favicon.ico"> 
	<link href="__CSS__/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__CSS__/font-awesome.min.css?v=4.4.0" rel="stylesheet">

    <link href="__CSS__/animate.min.css" rel="stylesheet">
    <link href="__CSS__/style.min.css?v=4.1.0" rel="stylesheet">
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

            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>矿机出币统计</h5>
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
                        <p>出币总数：<?php echo $money; ?></p>
                        <!-- <a href="https://www.jiguang.cn/stat/#/app/ff234d08109531341d2c79ca/overview" target= _blank>查看详情</a> -->
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
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
                        <div id="agent" style="height:300px;"></div>
                    </div>
                </div>                
            </div>                

            <div class="col-sm-6">
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
                        <div id="order" style="height:300px;"></div>
                    </div>
                </div>                
            </div> 

        </div>
    </div>
    <script src="__JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="/static/admin/js/content.min.js?v=1.0.0"></script>
    <script type="text/javascript" src="/static/admin/plugins/echarts/echarts.min.js"></script>
    <script type="text/javascript" src="/static/admin/plugins/echarts/shine.js"></script>
       <script type="text/javascript">
var dom = document.getElementById("agent");
var myChart = echarts.init(dom);
var app = {};
option = null;
option = {
    title : {
        text: '代理人数统计',
        x:'center'
    },
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient: 'vertical',
        left: 'left',
        data: ['待审核','已审核','已拒绝']
    },
    series : [
        {
            name:'代理',
            type: 'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:<?php echo $agent_ing; ?>, name:'待审核'},
                {value:<?php echo $agent_end; ?>, name:'已审核'},
                {value:<?php echo $agent_bad; ?>, name:'已拒绝'},
            ],
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
};
;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
       </script>

       <script type="text/javascript">
var dom = document.getElementById("order");
var myChart = echarts.init(dom);
var app = {};
option = null;
option = {
    title : {
        text: '订单统计',
        x:'center'
    },
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient: 'vertical',
        left: 'left',
        data: ['待审核','已审核','已拒绝']
    },
    series : [
        {
            name:'订单',
            type: 'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:<?php echo $order_1; ?>, name:'待付款'},
                {value:<?php echo $order_2; ?>, name:'待发货'},
                {value:<?php echo $order_3; ?>, name:'已完成'},
                {value:<?php echo $order_0; ?>, name:'无效'},
                {value:<?php echo $order_; ?>, name:'已退款'},
            ],
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
};
;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
       </script>

</body>
</html>
