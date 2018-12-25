<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"D:\PHPTutorial\WWW\open\public/../application/api\view\index\index.html";i:1529745405;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <title>接口说明</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="__JS__/jquery.min.js"></script>
        
        <style>
            table{
                width: 150%;
            }
            td{
                max-width: 20%;
            }
            tr td{
                font-size: 20px;
            }
            .f1{
                font-size: 22px;
            }
            table,table tr,table tr td{
                border:1px solid #0094ff;
                border-collapse:collapse;
            }
        </style>

    </head>
    <body>
        <h1>
            <div>接口说明</div>
        </h1>
        <table>
            <tr class="f1"><th>接口名称</th><th>接口地址</th><th>访问方式</th><th>传入参数说明</th><th>返回值说明</th><th>示例</th></tr>

            <tr>
                <td>发送验证码<br/></td>
                <td>http://<span class="host"></span>/Api/Index/send/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

phone          手机号
client_type    连接类型 web h5 native
type           0未注册，1已注册
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据     
                    </pre>
                </td>
                <td>
                    http://<span class="host"></span>/Api/Index/send/phone/13311111111<br/>
                </td>
            </tr>

            <tr>
                <td>登录2<br/></td>
                <td>http://<span class="host"></span>/v2/Api/Index/login/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

phone          手机号
password       密码
client_type    连接类型 web h5 native
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

            <tr>
                <td>图形验证码<br/></td>
                <td>http://<span class="host"></span>/Api/Index/verify/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

type           验证码标识（int数据）
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据     
                    </pre>
                </td>
                <td>
                    http://<span class="host"></span>/Api/Index/send/phone/13311111111<br/>
                </td>
            </tr>
            
            <tr>
                <td>算术验证码<br/></td>
                <td>http://<span class="host"></span>/Api/Index/validates/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

<!-- type           验证码标识（int数据） -->
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据     
                    </pre>
                </td>
                <td>
                    http://<span class="host"></span>/Api/Index/send/phone/13311111111<br/>
                </td>
            </tr>

            <tr>
                <td>注册<br/></td>
                <td>http://<span class="host"></span>/Api/Index/ajaxreg/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

phone          手机号
nickname       昵称
password       密码
code           验证码
rtid           推荐人ID
sid            赠送注册积分 默认-1（糖果）
type           图像验证马标识
piccode        图像验证码
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据     
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

            <tr>
                <td>登录<br/></td>
                <td>http://<span class="host"></span>/Api/Index/login/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

phone          手机号
password       密码
verify          验证码
type           验证码标识
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据     
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>   

            <tr>
                <td>退出<br/></td>
                <td>http://<span class="host"></span>/Api/Index/out/</td>
                <td>POST|GET</td>
                <td>
                    <pre>


                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
   
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr> 

            <tr>
                <td>找回密码<br/></td>
                <td>http://<span class="host"></span>/Api/Index/forget/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

phone          手机号
password       密码
code           验证码
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据     
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>                      

            <tr>
                <td>获取糖果总数<br/></td>
                <td>http://<span class="host"></span>/Api/Purse/getMoney</td>
                <td>POST|GET</td>
                <td>
                    <pre>

token           登录令牌

                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据     
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

            <tr>
                <td>获取糖果流水<br/></td>
                <td>http://<span class="host"></span>/Api/Purse/getBill</td>
                <td>POST|GET</td>
                <td>
                    <pre>

token           登录令牌
*p             页数
*row           每页条数
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据

data.allpage    总页数
data.list       数据数组    
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

            <tr>
                <td>获取转账流水<br/></td>
                <td>http://<span class="host"></span>/Api/Purse/getBill</td>
                <td>POST|GET</td>
                <td>
                    <pre>

sid            币种（-1糖果）
*p             页数
*row           每页条数
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据

data.allpage    总页数
data.list       数据数组    
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

            <tr>
                <td>分享二维码<br/></td>
                <td>http://<span class="host"></span>/Api/Index/share</td>
                <td>POST|GET</td>
                <td>
                    <pre>

uid            用户ID
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    二维码地址
                        
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

            <tr>
                <td>获取所有积分<br/></td>
                <td>http://<span class="host"></span>/Api/User/getCoin</td>
                <td>POST|GET</td>
                <td>
                    <pre>

token           登录令牌
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据

data.id        ID
data.uid       用户ID
data.sid       积分ID
data.name      积分名称
data.sore      数量
data.addtime   最后更新时间 
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>            

            <tr>
                <td>获取积分流水<br/></td>
                <td>http://<span class="host"></span>/Api/User/getCoinBill</td>
                <td>POST|GET</td>
                <td>
                    <pre>

token           登录令牌
sid            币种ID
*p             页数
*row           每页条数
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据

data.allpage   总页数
data.list      流水数组
list.id        ID
list.uid       用户ID
list.sid       积分ID
list.content   备注
list.price     数量
list.type      类型  0增加  1减少
list.type2     类型  默认是后台增加，1为推荐，2为 转让
list.addtime   发送时间 
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr> 
            <tr>
                <td>公告列表<br/></td>
                <td>http://<span class="host"></span>/Api/Notice/getList</td>
                <td>POST|GET</td>
                <td>
                    <pre>

*p             页数
*row           每页条数
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据

data.allpage   总页数
data.list      新闻数组
list.id        ID
list.title     文章标题
list.file      图片数组
list.reviews   回复数
list.addtime   发布时间 
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>
            
            <tr>
                <td>公告详情<br/></td>
                <td>http://<span class="host"></span>/Api/Notice/getNotice</td>
                <td>POST|GET</td>
                <td>
                    <pre>

id             公告ID
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据

data.news      新闻
data.reviews   回复 
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>





            <tr>
                <td>新闻列表<br/></td>
                <td>http://<span class="host"></span>/Api/News/getList</td>
                <td>POST|GET</td>
                <td>
                    <pre>

*p             页数
*row           每页条数
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据

data.allpage   总页数
data.list      新闻数组
list.id        ID
list.title     文章标题
list.file      图片数组
list.reviews   回复数
list.addtime   发布时间 
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

            <tr>
                <td>新闻详情<br/></td>
                <td>http://<span class="host"></span>/Api/News/getNews</td>
                <td>POST|GET</td>
                <td>
                    <pre>

id             新闻ID
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
result.data    返回数据

data.news      新闻
data.reviews   回复 
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

            <tr>
                <td>发表回复<br/></td>
                <td>http://<span class="host"></span>/Api/News/reviews</td>
                <td>POST|GET</td>
                <td>
                    <pre>

id             新闻ID
content        回复内容
                    </pre>
                <td>
                    <pre>
返回 json格式数组（result）
result.status  1|0 => 访问成功|访问失败
result.msg     提示消息
                    </pre>
                </td>
                <td>
                    <br/>
                </td>
            </tr>

            <tr>
                <td>比特币K线<br></td>
                <td>http://<span class="host"></span>/api/Coinbase/btcusd</td>
                <td>POST|GET</td>
                <td>
                    <pre>
                        
*period         hour|day|week|month|year
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data

                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>以太坊K线<br></td>
                <td>http://<span class="host"></span>/api/Coinbase/ethusd</td>
                <td>POST|GET</td>
                <td>
                    <pre>
                        
*period         hour|day|week|month|year
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data

                    </pre>
                </td>
                <td>

                </td>
            </tr>


            <tr>
                <td>莱特币K线<br></td>
                <td>http://<span class="host"></span>/api/Coinbase/ltcusd</td>
                <td>POST|GET</td>
                <td>
                    <pre>
                        
*period         hour|day|week|month|year
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data

                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>coinmarketcap  行情列表<br></td>
                <td>http://<span class="host"></span>/api/Price/getList</td>
                <td>POST|GET</td>
                <td>
                    <pre>
                         
*p           起始条数
*row         条数
*convert     参考币
(AUD, BRL, CAD, CHF, CLP, CNY, CZK, DKK, EUR, GBP, HKD, HUF, IDR, ILS, INR, JPY, KRW, MXN, MYR, NOK, NZD, PHP, PKR, PLN, RUB, SEK, SGD, THB, TRY, TWD, ZAR)
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data

                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>coinmarketcap  行情详情<br></td>
                <td>http://<span class="host"></span>/api/Price/details</td>
                <td>POST|GET</td>
                <td>
                    <pre>
 
id          虚拟币ID(列表数据中有)
*convert     参考币
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data

                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>coinmarketcap  全球数据<br></td>
                <td>http://<span class="host"></span>/api/Price/getGlobal</td>
                <td>POST|GET</td>
                <td>
                    <pre>
 
*convert     参考币
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data

                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>EXX  行情列表<br></td>
                <td>http://<span class="host"></span>/api/Exx/tickers/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
                         
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
data.vol           : 成交量(最近的24小时)
data.last          : 最新成交价
data.sell          : 卖一价
data.buy           : 买一价
data.weekRiseRate  : 周涨跌幅
data.riseRate      : 24小时涨跌幅
data.high          : 最高价
data.low           : 最低价
data.monthRiseRate : 30日涨跌幅

                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>EXX  行情K线<br></td>
                <td>http://<span class="host"></span>/api/Exx/trades/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
 
currency       币名称(列表中有 eth_hsr)                        
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
data.amount     : 交易数量
data.price      : 交易价格
data.tid        : 交易生成ID
data.type       : 交易类型，buy(买)/sell(卖)
data.date       : 交易时间(时间戳)
data.trade_type : 委托类型，ask(卖)/bid(买)


                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Huobi  支持币<br></td>
                <td>http://<span class="host"></span>/api/Huobi/symbols/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
                       
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Huobi  获取行情<br></td>
                <td>http://<span class="host"></span>/api/Huobi/tickers/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
symbols        支持币+参考币(参考币 btc|eth|usdt) 如：htbtc,usdtbtc                    
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Huobi  获取行情列表<br></td>
                <td>http://<span class="host"></span>/api/Huobi/getlist/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
*p             页数
*row            条数          
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>            

            <tr>
                <td>Huobi  获取K线<br></td>
                <td>http://<span class="host"></span>/api/Huobi/kline/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
symbols        支持币+参考币(参考币 btc|eth|usdt) 如：htbtc,usdtbtc 
*period        时间  默认1day (1min, 5min, 15min, 30min, 60min, 1day, 1mon, 1week, 1year)                  
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Weex  支持币<br></td>
                <td>http://<span class="host"></span>/api/Weex/symbols/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
                       
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Weex  获取行情<br></td>
                <td>http://<span class="host"></span>/api/Weex/ticker/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
symbols        支持币+参考币(参考币 btc|eth|usdt) 如：htbtc,usdtbtc                    
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Weex  获取行情列表<br></td>
                <td>http://<span class="host"></span>/api/Weex/getlist/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
*p             页数
*row            条数                     
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>            

            <tr>
                <td>Weex  获取K线<br></td>
                <td>http://<span class="host"></span>/api/Weex/kline/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
symbols        支持币+参考币(参考币 btc|eth|usdt) 如：htbtc,usdtbtc 
*type        时间  默认1day (1min,3min,5min,15min,30min,1hour,2hour,4hour,6hour,12hour,1day,3day,1week)                  
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Bitfinex  支持币<br></td>
                <td>http://<span class="host"></span>/api/Bitfinex/symbols/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
                       
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Bitfinex  获取行情<br></td>
                <td>http://<span class="host"></span>/api/Bitfinex/tickers/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
symbols        支持币                   
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Bitfinex  获取行情列表<br></td>
                <td>http://<span class="host"></span>/api/Bitfinex/getlist/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
*p             页数
*row            条数                   
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>Bitfinex  获取K线<br></td>
                <td>http://<span class="host"></span>/api/Bitfinex/book/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
symbols        支持币                
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>积分兌换<br></td>
                <td>http://<span class="host"></span>/api/User/buy/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
token           登录令牌
sid            币种ID  (糖果为-1)
address        钱包地址
num            兌换数量
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>
    

            <tr>
                <td>积分赠送<br></td>
                <td>http://<span class="host"></span>/api/User/giveScore/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

token           登录令牌  
uid            赠送ID
sid            币种ID  (糖果为-1)
num            赠送数量
content        备注信息
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>钱包关于我们<br></td>
                <td>http://<span class="host"></span>/api/index/about/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>


            <tr>
                <td>钱包帮助信息<br></td>
                <td>http://<span class="host"></span>/api/index/help/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>钱包升级<br></td>
                <td>http://<span class="host"></span>/api/index/upgrade/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
system         手机系统(参数：android,iphone)
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>用户协议<br></td>
                <td>http://<span class="host"></span>/api/index/agreement/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
language         语言 (en|cn)
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>获取支持代币<br></td>
                <td>http://<span class="host"></span>/api/index/getcoinlist/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
             

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>获取用户代币<br></td>
                <td>http://<span class="host"></span>/api/user/getlist/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
token          令牌
address        钱包地址
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>增加用户代币<br></td>
                <td>http://<span class="host"></span>/api/user/addlist/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
token          令牌
cid            代币ID

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr> 

            <tr>
                <td>删除用户代币<br></td>
                <td>http://<span class="host"></span>/api/user/dellist/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
token          令牌
cid            代币ID

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>                        

            <tr>
                <td>搜索代币<br></td>
                <td>http://<span class="host"></span>/api/user/search/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
token          令牌
keyword        关键词
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr> 

                        <tr>
                <td>邀请人数总数<br></td>
                <td>http://<span class="host"></span>/api/user/shareinfo/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
uid            用户id
phone          用户手机
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr> 

            </tr> 

                        <tr>
                <td>用户流水列表<br></td>
                <td>http://<span class="host"></span>/api/user/sharelist/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
uid            用户id
*p             页数
*row           每页条数
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr> 
            
            </tr> 

                        <tr>
                <td>币种信息<br></td>
                <td>http://<span class="host"></span>/api/market/getcoin/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
*p             页数
*row           每页条数
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr> 

            </tr> 

            <tr>
                <td>市场信息<br></td>
                <td>http://<span class="host"></span>/api/market/getmarket/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
  
*p             页数
*row           每页条数
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr> 

            <tr>
                <td>获取K线图<br></td>
                <td>http://<span class="host"></span>/api/market/getKline/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
coin           币种名称  
type           1,5,60,1440 分钟
row            条数
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>


            <tr>
                <td>获取用户自定义行情<br></td>
                <td>http://<span class="host"></span>/api/user/getCoins/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>编辑自定义行情列表<br></td>
                <td>http://<span class="host"></span>/api/user/setCoins/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
list           市场ID 多个用，隔开  

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>获取用户自定义行情<br></td>
                <td>http://<span class="host"></span>/api/user/getMarket/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
coin           搜索关键词  

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>
            
            <tr>
                <td>行情搜索<br></td>
                <td>http://<span class="host"></span>/api/user/research/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>推荐会员总数<br></td>
                <td>http://<span class="host"></span>/api/user/getMemberCount/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
sid            积分id -1（糖果）
row            实现数据条数,默认10条
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>



            <tr>
                <td>推荐会员详情列表<br></td>
                <td>http://<span class="host"></span>/api/user/getCandylist/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
type            1,一级会员 2,二级会员
sid            积分id -1（糖果）
*p             页数
*row           每页条数
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>矿机列表<br></td>
                <td>http://<span class="host"></span>/api/shop/shop_list/</td>
                <td>POST|GET</td>
                <td>
                    <pre>

*p             页数
*row           每页条数
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>矿机详情<br></td>
                <td>http://<span class="host"></span>/api/shop/shop_find/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
goodsid        矿机id
*p             页数
*row           每页条数
                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>矿机购买<br></td>
                <td>http://<span class="host"></span>/api/shop/shop_buy/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
goodsid        矿机id

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>算力难度<br></td>
                <td>http://<span class="host"></span>/api/index/calculation_force/</td>
                <td>POST|GET</td>
                <td>
                    <pre>


                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>用户矿机列表<br></td>
                <td>http://<span class="host"></span>/api/shop/shop_order/</td>
                <td>POST|GET</td>
                <td>
                    <pre>
*p             页数
*row           每页条数

                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td>用户矿机详情<br></td>
                <td>http://<span class="host"></span>/api/shop/shop_order_find/</td>
                <td>POST|GET</td>
                <td>
                    <pre>


                    </pre>
                </td><td>
                    <pre>
返回 json格式数组（result）
result.code  1|0 =&gt; 访问成功|访问失败
result.msg     提示消息

result.data
                    </pre>
                </td>
                <td>

                </td>
            </tr>

        </table>

    </body>

</html>
        <script>
            var host = location.host;
            onload();
            function onload() {
                // console.log($(".host"));
                $(".host").html(host);
                
            }
            function toopen(th) {
                var url = $(th).html();
                console.log(url);
                var dd = url.replace(/<\/?.+?>/g, "");
                var url = dd.replace(/ /g, "");//dd为得到后的内容
                console.log(url);
                window.open(url);
            }
        </script>

