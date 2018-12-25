<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"D:\PHPTutorial\WWW\open\public/../application/market\view\market\line.html";i:1528004699;}*/ ?>
<!DOCTYPE html>
<html style="height: 100%">
   <head>
       <meta charset="utf-8">
   </head>
   <body style="height: 100%; margin: 0">
       <div id="container" style="height: 100%"></div>
       <script type="text/javascript" src="/static/admin/plugins/echarts/echarts.min.js"></script>
       <script type="text/javascript" src="/static/admin/plugins/echarts/shine.js"></script>
       <script type="text/javascript">
var dom = document.getElementById("container");
var myChart = echarts.init(dom,'shine');
var app = {};
option = null;
option = {
    title: {
        text: 'K线图'
    },
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            label: {
                backgroundColor: '#283b56'
            }
        }
    },
    legend: {
        data:['价格', '涨幅']
    },
    toolbox: {
        show: true,
        feature: {
            dataView: {readOnly: false},
            restore: {},
            saveAsImage: {}
        }
    },
    dataZoom: {
        show: false,
        start: 0,
        end: 100
    },
    xAxis: [
        {
            type: 'category',
            boundaryGap: true,
            data: <?php echo $time; ?>
        },
        {
            type: 'category',
            boundaryGap: true,
            data: <?php echo $time; ?>
        }
    ],
    yAxis: [
        {
            type: 'value',
            scale: true,
            name: '价格',
            max: <?php echo $max; ?>,
            min: <?php echo $min; ?>,
            boundaryGap: [0.2, 0.2]
        },
        {
            type: 'value',
            scale: true,
            name: '涨幅',
            max: 1,
            min: -1,
            boundaryGap: [0.2, 0.2]
        }
    ],
    series: [
        {
            name:'涨幅',
            type:'bar',
            xAxisIndex: 1,
            yAxisIndex: 1,
            data:<?php echo $change; ?>
        },
        {
            name:'价格',
            type:'line',
            data:<?php echo $price; ?>
        }
    ]
};

app.count = 11;


if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
       </script>
   </body>
</html>