<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
        <title>浏览器定位</title>
        <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=307d8183b8289d804cbf41d086c0c904"></script>
    <body>
        <div id='container'></div>
        <div id="tip"></div>
        <script type="text/javascript">
            var map, geolocation, geocoder;
            //加载地图，调用浏览器定位服务
           map = new AMap.Map('container', {
                resizeEnable: false
            });
            map.plugin('AMap.Geolocation', function () {
                geolocation = new AMap.Geolocation({
                    enableHighAccuracy: true, //是否使用高精度定位，默认:true
                    timeout: 10000, //超过10秒后停止定位，默认：无穷大
                    buttonOffset: new AMap.Pixel(5, 10), //定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
                    zoomToAccuracy: true, //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
                    buttonPosition: 'RB'
                });
                map.addControl(geolocation);
                geolocation.getCurrentPosition();
                AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息
                AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
            });
            AMap.service('AMap.Geocoder', function () {//回调函数
                //TODO: 使用geocoder 对象完成相关功能
                geocoder = new AMap.Geocoder();
            })
       
            //解析定位结果
            function onComplete(data) {
                var lnglatXY = [data.position.getLng(), data.position.getLat()];//地图上所标点的坐标
                geocoder.getAddress(lnglatXY, function (status, result) {
                    if (status === 'complete' && result.info === 'OK') {
                        //获得了有效的地址信息: 城市province 编码adcode
                        console.log(result.regeocode.addressComponent );
                    } else {
                        //获取地址失败
                    }
                });
            }
            //解析定位错误信息
            function onError(data) {
                //document.getElementById('tip').innerHTML = '定位失败';
            }

        </script>
    </body>
</html>