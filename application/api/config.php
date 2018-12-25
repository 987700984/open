<?php
// +----------------------------------------------------------------------
// | 互联在线
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://www.hlzx.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: arno <1065800888@qq.com>
// +----------------------------------------------------------------------
return [

    //模板参数替换
    'view_replace_str'       => array(
        '__CSS__'    => '/static/mobile/css',
        '__JS__'     => '/static/mobile/js',
        '__IMG__' => '/static/mobile/img',
        '__3RD__' => '/static/mobile/3rd',
    	'__ADMINJS__' => '/static/admin/js',
    ),

    'Wxkey' => [
        'APPID' => '',
        'SECRET' => ''
    ],
    
    'Msgkey' => [
        'user' => '',
        'pasd' => ''
    ],
    
    //智付KEY
    'dinpaykey' => [
        'merchant_private_key' => '-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKofHWEn/VTRAdTS
sfa8ni8eBcLTShVnxT+/mu/ny6a9/Mqd5jiVmrrndQCu1nwGDs58zw5pLvxfLqfI
ZcczNcN+6pn520TB+d45JaRWDDHwh71CzknAgrMh9SofNWnqRQK+D37dd3XZgPXE
jOxnjmx0ZAEUx0ys2JtN4sjuWvQJAgMBAAECgYBgXJOFruMeIe2JoXbQrfJj+fuF
aa0zBr6B54RZk9CVOFRyaJI5RvSIHgb2RhKT2dKVP7kBDq9goIGK5EZSWT1//Fay
dR44tI+rjz++PiGl+TwGAi/hKDCEt/Sa
1D5wbyfCmt325SLoAQJBANhtCe6ZVinXX4IBBS/VWGLRsLVk3pgNN7sz/yQh8LnB
R9hmAu83DHeXe2gVKtrG6le1U688vT6zK777Ed8muYkCQQDJOo3MDmesCNchw55r
XNKgW+aynrdhnAuiXtuppUJFQ0ernGZe4XplalFQTb/Bpc6oB/3CRbfKHMMUly7h
Jl6iGATOBbbsbbj1zTf2ikfrBt3RAkAb1jfLZKq+x7JK/26o13xjppLxXrdrj1Sk
r46flzblsfr2KHqY4YQ3B1AcVcUTGsbNw908xLw5/2SVMpzPl7EBAkBA60zHo18/
SQ0bCNLR35ry4iEEfZgbOuH+I0gqdIzey0ydn6DGQm76CCrrv9ji3xGomL5sZMwy
BeI1xnc3odxi
-----END PRIVATE KEY-----',
        
        'merchant_public_key' => '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCqHx1hJ/1U0QHU0rH2vJ4vHgXC
00oVZ8U
+dtEwfneOSWkVgwx8Ie9Qs5JwIKzIfUqHzVp6kUCvg9+3Xd12YD1xIzsZ45sdGQB
FMdMrNibTeLI7lr0CQIDAQAB
-----END PUBLIC KEY-----',
        
        'encryption_key' => '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCH1CghkCOyrcTUHc/g+0irDePW
a8TlqK1cletnSdDxby5CrntnYiYrxFRaQ+TuNTfC6LLiYfsch7MjPdgbK0Y7rILv
wFBtexlpbH/S7dCiNQIDAQAB
-----END PUBLIC KEY-----',
        
        'dinpay_public_key' => '-----BEGIN PUBLIC KEY-----+bo6bPXv
5Ypg5Xm36b/DMA/5eL8ZiG3X7xa6VOu893g6j4HNuhLgMQ1J+4Xdy/SsO6euPK93
iKkE3Gh53negXDeoxMMtZNaAnrISdVXac8K6kmrIIJF4SvwW57Jfxtf1mqIhpTwN
nAVN0Ul7AXqdsdKNTwIDAQAB
-----END PUBLIC KEY-----',
        
        'merchant_code' => '',
    ],  
    
];
