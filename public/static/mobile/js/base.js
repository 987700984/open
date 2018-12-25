
// 布局和文字大小自适应

// !function (N, M) {
//     function L() {
//         var a = I.getBoundingClientRect().width;
//         a / F >= 720 && (a = 720 * F);
//         var d = a / 7.2;
//         I.style.fontSize = d + "px", D.rem = N.rem = d
//     }
//
//     var K, J = N.document, I = J.documentElement, H = J.querySelector('meta[name="viewport"]'), G = J.querySelector('meta[name="flexible"]'), F = 0, E = 0, D = M.flexible || (M.flexible = {});
//     if (H) {
//         console.warn("将根据已有的meta标签来设置缩放比例");
//         var C = H.getAttribute("content").match(/initial\-scale=([\d\.]+)/);
//         C && (E = parseFloat(C[1]), F = parseInt(1 / E))
//     } else {
//         if (G) {
//             var B = G.getAttribute("content");
//             if (B) {
//                 var A = B.match(/initial\-dpr=([\d\.]+)/), z = B.match(/maximum\-dpr=([\d\.]+)/);
//                 A && (F = parseFloat(A[1]), E = parseFloat((1 / F).toFixed(2))), z && (F = parseFloat(z[1]), E = parseFloat((1 / F).toFixed(2)))
//             }
//         }
//     }
//     // if (!F && !E) {
//     //     var y = N.navigator.userAgent, x = (!!y.match(/android/gi), !!y.match(/iphone/gi)), w = x && !!y.match(/OS 9_3/), v = N.devicePixelRatio;
//     //     console.log(v)
//     //     F = x && !w ? v >= 3 && (!F || F >= 3) ? 3 : v >= 2 && (!F || F >= 2) ? 2 : 1 : 1, E = 1 / F
//     //     console.log(F)
//     // }
//     if (!F && !E) {
//         //devicePixelRatio这个属性是可以获取到设备的dpr
//         var devicePixelRatio = window.devicePixelRatio;
//         // console.log(devicePixelRatio);
//         // console.log(1)
//         //判断dpr是否为整数
//         var isRegularDpr = devicePixelRatio.toString().match(/^[1-9]\d*$/g)
//         if (isRegularDpr) {
//             // 对于是整数的dpr，对dpr进行操作
//             if (devicePixelRatio >= 3 && (!F || F >= 3)) {
//                 F = 2;
//             } else if (devicePixelRatio >= 2 && (!F || F >= 2)){
//                 F = 2;
//             } else {
//                 F = 1;
//             }
//         } else {
//             // 对于其他的dpr，人采用dpr为1的方案
//             F = 1;
//         }
//         E = 1 / F;
//         console.log(E+'----'+F)
//     }
//     if (I.setAttribute("data-dpr", F), !H) {
//         if (H = J.createElement("meta"), H.setAttribute("name", "viewport"), H.setAttribute("content", "initial-scale=" + E + ", maximum-scale=" + E + ", minimum-scale=" + E + ", user-scalable=no"), I.firstElementChild) {
//             I.firstElementChild.appendChild(H)
//         } else {
//             var u = J.createElement("div");
//             u.appendChild(H), J.write(u.innerHTML)
//         }
//     }
//     N.addEventListener("resize", function () {
//         clearTimeout(K), K = setTimeout(L, 300)
//     }, !1), N.addEventListener("pageshow", function (b) {
//         b.persisted && (clearTimeout(K), K = setTimeout(L, 300))
//     }, !1), "complete" === J.readyState ? J.body.style.fontSize = 12 * F + "px" : J.addEventListener("DOMContentLoaded", function () {
//         J.body.style.fontSize = 12 * F + "px"
//     }, !1), L(), D.dpr = N.dpr = F, D.refreshRem = L, D.rem2px = function (d) {
//         var c = parseFloat(d) * this.rem;
//         return "string" == typeof d && d.match(/rem$/) && (c += "px"), c
//     }, D.px2rem = function (d) {
//         var c = parseFloat(d) / this.rem;
//         return "string" == typeof d && d.match(/px$/) && (c += "rem"), c
//     }
// }(window, window.lib || (window.lib = {}));






!function(x) {
    function w() {
        var a = r.getBoundingClientRect().width;
        a / v > 720 && (a = 720 * v), x.rem = a / 7.2, r.style.fontSize = x.rem + "px"
    }
    var v, u, t, s = x.document, r = s.documentElement, q = s.querySelector('meta[name="viewport"]');
    if (q) {
        console.warn("将根据已有的meta标签来设置缩放比例");
        var o = q.getAttribute("content").match(/initial\-scale=(["‘]?)([\d\.]+)\1?/);
        o && (u = parseFloat(o[2]), v = parseInt(1 / u))
    }
    // if (!v && !u) {
    //     var n = (x.navigator.appVersion.match(/android/gi), x.navigator.appVersion.match(/iphone/gi)), v = x.devicePixelRatio;
    //     v = n ? v >= 3 ? 3 : v >= 2 ? 2 : 1 : 1, u = 1 / v
    // }
    if (!v && !u) {
        //devicePixelRatio这个属性是可以获取到设备的dpr
        var devicePixelRatio = window.devicePixelRatio;
        // console.log(devicePixelRatio);
        // console.log(1)
        //判断dpr是否为整数
        var isRegularDpr = devicePixelRatio.toString().match(/^[1-9]\d*$/g)
        if (isRegularDpr) {
            // 对于是整数的dpr，对dpr进行操作
            if (devicePixelRatio >= 3 && (!v || v >= 3)) {
                v = 1;
            } else if (devicePixelRatio >= 2 && (!v || v >= 2)){
                v = 1;
            } else {
                v = 1;
            }
        } else {
            // 对于其他的dpr，人采用dpr为1的方案
            v = 1;
        }
        u = 1 / v;
    }
    if (r.setAttribute("data-dpr", v), !q) {
        if (q = s.createElement("meta"), q.setAttribute("name", "viewport"), q.setAttribute("content", "initial-scale=" + u + ", maximum-scale=" + u + ", minimum-scale=" + u + ", user-scalable=no"), r.firstElementChild) {
            r.firstElementChild.appendChild(q)
        } else {
            var m = s.createElement("div");
            m.appendChild(q), s.write(m.innerHTML)
        }
    }
    x.dpr = v, x.addEventListener("resize", function() {
        clearTimeout(t), t = setTimeout(w, 300)
    }, !1), x.addEventListener("pageshow", function(b) {
        b.persisted && (clearTimeout(t), t = setTimeout(w, 300))
    }, !1), "complete" === s.readyState ? s.body.style.fontSize = 12 * v + "px" : s.addEventListener("DOMContentLoaded", function() {
        s.body.style.fontSize = 12 * v + "px"
    }, !1), w()
}(window);




// !function(){var e="@charset \"utf-8\";html{color:#000;overflow-y:scroll;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}html *{outline:0;-webkit-text-size-adjust:none;-webkit-tap-highlight-color:rgba(0,0,0,0)}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td,hr,button,article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{margin:0;padding:0}input,select,textarea{font-size:100%}table{border-collapse:collapse;border-spacing:0}fieldset,img{border:0}abbr,acronym{border:0;font-variant:normal}del{text-decoration:line-through}address,caption,cite,code,dfn,em,th,var{font-style:normal;font-weight:500}ol,ul{list-style:none}caption,th{text-align:left}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:500}q:before,q:after{content:''}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-.5em}sub{bottom:-.25em}ins,a{text-decoration:none}",d=document.createElement("style");if(document.getElementsByTagName("head")[0].appendChild(d),d.styleSheet){d.styleSheet.disabled||(d.styleSheet.cssText=e)}else{try{d.innerHTML=e}catch(f){d.innerText=e}}}();!function(J,I){function H(){var a=E.getBoundingClientRect().width;a/B>540&&(a=540*B);var d=a/10;E.style.fontSize=d+"px",z.rem=J.rem=d}var G,F=J.document,E=F.documentElement,D=F.querySelector('meta[name="viewport"]'),C=F.querySelector('meta[name="flexible"]'),B=0,A=0,z=I.flexible||(I.flexible={});if(D){console.warn("将根据已有的meta标签来设置缩放比例");var y=D.getAttribute("content").match(/initial\-scale=([\d\.]+)/);y&&(A=parseFloat(y[1]),B=parseInt(1/A))}else{if(C){var x=C.getAttribute("content");if(x){var w=x.match(/initial\-dpr=([\d\.]+)/),v=x.match(/maximum\-dpr=([\d\.]+)/);w&&(B=parseFloat(w[1]),A=parseFloat((1/B).toFixed(2))),v&&(B=parseFloat(v[1]),A=parseFloat((1/B).toFixed(2)))}}}if(!B&&!A){var u=(J.navigator.appVersion.match(/android/gi),J.navigator.appVersion.match(/iphone/gi)),t=J.devicePixelRatio;B=u?t>=3&&(!B||B>=3)?3:t>=2&&(!B||B>=2)?2:1:1,A=1/B}if(E.setAttribute("data-dpr",B),!D){if(D=F.createElement("meta"),D.setAttribute("name","viewport"),D.setAttribute("content","initial-scale="+A+", maximum-scale="+A+", minimum-scale="+A+", user-scalable=no"),E.firstElementChild){E.firstElementChild.appendChild(D)}else{var s=F.createElement("div");s.appendChild(D),F.write(s.innerHTML)}}J.addEventListener("resize",function(){clearTimeout(G),G=setTimeout(H,300)},!1),J.addEventListener("pageshow",function(b){b.persisted&&(clearTimeout(G),G=setTimeout(H,300))},!1),"complete"===F.readyState?F.body.style.fontSize=12*B+"px":F.addEventListener("DOMContentLoaded",function(){F.body.style.fontSize=12*B+"px"},!1),H(),z.dpr=J.dpr=B,z.refreshRem=H,z.rem2px=function(d){var c=parseFloat(d)*this.rem;return"string"==typeof d&&d.match(/rem$/)&&(c+="px"),c},z.px2rem=function(d){var c=parseFloat(d)/this.rem;return"string"==typeof d&&d.match(/px$/)&&(c+="rem"),c}}(window,window.lib||(window.lib={}));




