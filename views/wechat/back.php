<!DOCTYPE html><html lang="zh-CN"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1"><title></title><script>document.addEventListener('DOMContentLoaded', function() {
        document.documentElement.style.fontSize = document.documentElement.clientWidth / 20 + 'px'
    })
    let origin = window.location.origin;
    window.siteinfo = {
        "uniacid": "3",
        "acid": "3",
        "multiid": "0",
        "version": "1.1.5",
        "AppURL": origin + "/index.php",
        "siteroot": origin + "/index.php",
        "design_method": "3",
        "tabBar": {
            "color": "#ccc",
            "selectedColor": "#ff4c92",
            "borderStyle": "black",
            "backgroundColor": "#ffffff",
            "list": [{
                    "pagePath": "/pages/index/index",
                    "text": "A"
                },
                {
                    "pagePath": "/pages/categories/index",
                    "text": "B"
                },
                {
                    "pagePath": "/pages/cart/index",
                    "text": "C"
                },
                {
                    "pagePath": "/pages/goods/list",
                    "text": "D"
                },
                {
                    "pagePath": "/pages/user/index",
                    "text": "E"
                }
            ]
        }
    }



    <?php if (!empty($tabBar)) {
    echo "window.siteinfo.tabBar = " . json_encode($tabBar);
}?>

    console.log("window.siteinfo",window.siteinfo)

    var coverSupport = 'CSS' in window && typeof CSS.supports === 'function' && (CSS.supports('top: env(a)') || CSS.supports('top: constant(a)'))
    document.write('<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0' + (coverSupport ? ', viewport-fit=cover' : '') + '" />')</script><link rel="stylesheet" href="/assets/wechat/static/index.3e73f18a.css"></head><body><noscript><strong>Please enable JavaScript to continue.</strong></noscript><div id="app"></div><script src="https://res2.wx.qq.com/open/js/jweixin-1.6.0.js"></script><script src="/assets/wechat/static/js/chunk-vendors.f0e1d886.js"></script><script src="/assets/wechat/static/js/index.b3ffae1a.js"></script></body></html>