<?php
$this->setPageTitle('名医主刀');
Yii::app()->clientScript->registerCssFile('http://myzd.oss-cn-hangzhou.aliyuncs.com/static/mobile/js/jquery.bxslider/jquery.bxslider.css');
Yii::app()->clientScript->registerScriptFile('http://myzd.oss-cn-hangzhou.aliyuncs.com/static/mobile/js/jquery.bxslider/jquery.bxslider.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://myzd.oss-cn-hangzhou.aliyuncs.com/static/mobile/js/jquery-1.9.1.min.js', CClientScript::POS_HEAD);
$urlBigEvent = $this->createUrl('home/page', array('view' => 'bigEvent', 'app' => 0));
$urlNewList = $this->createUrl('home/page', array('view' => 'newList', 'app' => 0));
$urlRobot = $this->createUrl('home/page', array('view' => 'robot', 'app' => 0));
$urlMyzd = $this->createUrl('home/page', array('view' => 'myzd', 'app' => 0));
$urlViewContractDoctors = $this->createUrl('doctor/viewContractDoctors');
$urlViewCommonweal = $this->createUrl('doctor/viewCommonweal', array('app' => 0));
$ajaxIndexannouncement = $this->createUrl('/apimd/indexannouncement');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
?>
<article id="home_article" class="active" data-scroll="true" data-active="home_footer">
    <div>
        <div id="banner" class="pb10">
            <div id="team-bxslider">
                <ul class="bxslider">

                </ul>
            </div>
        </div>
        <div class="grid pt20 pb20 bg-white">
            <div class="col-0 pl10 pr10 pb2 br-gray">
                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146898693233368" class="h16p">
            </div>
            <div class="col-0 pl10 pr10">
                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146898697093799" class="h20p">
            </div>
            <div id="information" class="col-1 pr10">
                <div id="team-bxslider">
                    <ul class="bxslider">
                    </ul>
                </div>
            </div>
        </div>
        <div class="pl5 pr5 pt10 pb10 grid">
            <div class="col-1 w50 bg-white mr5">
                <a href="<?php echo $urlViewCommonweal; ?>">
                    <div class="text-center pt20 pb20">
                        <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146880907449190" class="w80p">
                        <div class="pt10">
                            加入名医公益
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-1 w50 bg-white ml5">
                <a href="<?php echo $urlMyzd; ?>">
                    <div class="text-center pt20 pb20">
                        <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146880906689188" class="w80p">
                        <div class="pt10">
                            了解名医主刀
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="pb10">
            <a href="<?php echo $urlViewContractDoctors; ?>">
                <div class="pad20 grid bg-white">
                    <div class="col-1">
                        <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146880949657339" class="w70p">
                    </div>
                    <div class="col-0 pl5 pr5">
                        查看名医主刀签约专家
                    </div>
                    <div class="col-1">
                        <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146880950633542" class="w70p">
                    </div>
                </div>
            </a>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        //轮播图
        var html = '<li class="slide">' +
                '<a href="<?php echo $urlBigEvent; ?>">' +
                '<img class="w100" src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146881024079320">' +
                '</a>' +
                '</li>' +
                '<li class="slide">' +
                '<a href="<?php echo $urlNewList; ?>">' +
                '<img class="w100" src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146881024084983">' +
                '</a>' +
                '</li>' +
                '<li class="slide">' +
                '<a href="<?php echo $urlRobot; ?>">' +
                '<img class="w100" src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146881024062046">' +
                '</a>' +
                '</li>';
        $('#banner .bxslider').html(html);
        $('#banner .bxslider').bxSlider({
            mode: 'fade',
            slideMargin: 0,
            controls: false,
            auto: true
        });

        //咨询
        $.ajax({
            url: '<?php echo $ajaxIndexannouncement; ?>',
            success: function (data) {
                readyInf(data);
            }
        });
        function readyInf(data) {
            var inf = '';
            var doctors = data.results.doctors;
            if (doctors.length > 0) {
                for (var i = 0; i < doctors.length; i++) {
                    inf += '<li class="slide pt2 textOmitted">' + doctors[i] + '</li>';
                }
            }
            console.log(inf);
            $('#information .bxslider').html(inf);
            $('#information .bxslider').bxSlider({
                mode: 'fade',
                slideMargin: 0,
                controls: false,
                auto: true,
                pager: false
            });
        }

    });
</script>