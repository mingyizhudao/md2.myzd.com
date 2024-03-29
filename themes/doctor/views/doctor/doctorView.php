<!DOCTYPE html> 
<html lang="zh" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta charset="utf-8" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="pragma" content="no-cache" />
        <title>医生</title>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=307d8183b8289d804cbf41d086c0c904"></script>
        <?php
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.0.css');
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/custom.min.1.1.css');
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/zepto.min.1.0.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/doctor/common.min.1.0.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/main.min.1.0.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.1.css');
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/custom.min.1.0.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/doctor/jquery.formvalidate.min.1.1.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/doctorView.js?ts=' . time(), CClientScript::POS_END);
        ?>
    </head>
    <?php
    /**
     * $data.
     */
    $urlResImage = Yii::app()->theme->baseUrl . "/images/";
    $urlHospital = $this->createAbsoluteUrl('doctor/ajaxSearchHospital');
    $urlSubmitProfile = $this->createUrl("doctor/ajaxProfile");
    $urlSubmitDoctorView = $this->createUrl("doctor/ajaxDoctor");
    $urlDoctorBasicView = $this->createUrl('doctor/basicView', array('id' => $model->id));
    $urlLoadAllStates = $this->createUrl('region/loadStates');
    $urlLoadCity = $this->createUrl('region/loadCities', array('state' => ''));
    $urlMajorView = $this->createUrl('doctor/majorView', array('id' => ''));
    ?>
    <style>
        #search {
            background: #f3f3f3;
        }
        .area-select{
            padding: 10px 0;
            background: #fff;
        }
        .area-select:after{
            content: '';
            display: block;
            clear: both;
        }
        .area-select .pick-t{
            width: 50%;
            float: left;
            text-align: center;
            height: 25px;
            line-height: 25px;
            margin: 5px 0 0 0;
        }
        .area-select .pick-t:first-child{
            border-right: 1px solid #ccc;
        }
        .hospital-list{
            background: #fff;
        }
        .hospital-list li{
            list-style: none;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        #pick-province-layer, #pick-city-layer{
            float: left;
            position: absolute;
            background: rgba(0, 0, 0, 0.21);
            top: 48px;
            bottom: 0px;
            overflow-y: scroll;
        }
        #pick-province-layer>div, #pick-city-layer>div{
            background: #fff;
        }
        #pick-province-layer p, #pick-city-layer p{
            padding: 5px 10px;
            background: #eee;
            color: #ccc;
        }
        #pick-province-layer li, #pick-city-layer li{
            padding: 5px 10px;
        }
        #pick-province-layer{
            display: none;
        }
        #pick-city-layer{
            display: none;
        }
        .searchInput >input{
            margin: 0;
            border: 0;
            padding: 0;
            height: 100%;
            box-shadow: none;
        }
        .area-select img {
            margin-left: 10px;
            width: 12px;
            height: 7px;
        }
        .btn-back{
            height: 40px;
            position: absolute;
            z-index: 1;
            background: url('http://static.mingyizhudao.com/147029023304790') center no-repeat;
            border: 0px;
            box-shadow: none;
            background-size: 30px;
        }
        .nav-crumbs {
            display: flex;
            align-items: center;
            margin: 40px 0 0 0;
        }
        .nav-crumbs i{
            background: #32c9c0;
            display: inline-block;
            width: 4px;
            height: 4px;
            border-radius: 2px;
            margin: 0 0 0 5px;
        }
        .nav-crumbs span{
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #32c9c0;
            border-radius: 10px;
            color: #fff;
            text-align: center;
            line-height: 20px;
            margin: 0 5px;
        }
        .from-out{
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 0px;
            display: flex;
            align-items: center;
        }
        .from-contanier{
        }
        .from-contanier input,.from-contanier select{
            margin-bottom: 0;
            border: none!important;
            border-bottom: 1px solid #d4d4d4!important;
            border-radius: 0px!important;
            -webkit-box-shadow: none!important;
            box-shadow: none!important;
            padding: 10px 0px!important;
            background: #fff;
        }
        #gtest{

        }
        #gtest >div{
            width: 800px;
            float: right;
        }
        #gtest span{
            display: inline-block;
            width: 50px;
            margin: 0 15px;
            background: #333;
        }
        #search_section{
            display: none;
        }
        div.error{margin-top:5px;}
        h1{
            padding-bottom:0px;
        }
        #jingle_loading_mask {
            background-color: #222;
        }
        #jingle_loading_mask {
            display: none;
            position: absolute;
            z-index: 90;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            opacity: 0;
        }
        #jingle_loading.loading {
            background-color: #2C3E50;
        }
        #jingle_loading.loading {
            top: 50%;
            left: 50%;
            margin: -75px 0 0 -75px;
            opacity: .9;
            text-align: center;
            width: 150px;
            height: 150px;
            border-radius: 10px;
        }
        #jingle_loading {
            display: none;
            position: absolute;
            left: 0;
            right: 0;
            z-index: 98;
            min-height: 50px;
        }
        #jingle_loading.loading i.icon {
            color: #fff;
            font-size: 4em;
            line-height: 110px;
            margin: 0;
        }
        #jingle_loading p{
            color: #fff;
        }
        #searchInput_section{
            display: none;
        }
        .icon_search {
            position: absolute;
            left: 20px;
            top: 9px;
            width: 15px;
            height: 25px;
            background: url('http://static.mingyizhudao.com/146243645256928') no-repeat;
            background-size: 15px 15px;
            background-position: 0 5px;
        }
        .icon_input {
            color: #000;
            margin-bottom: 0;
            border-radius: 5px!important;
            -webkit-box-shadow: none!important;
            box-shadow: none!important;
            padding: 0 10px 0 30px!important;
            height: 30px!important;
            border: none!important;
            margin-top: 7px;
            background-color: #E0DFDE;
            line-height: 30px;
        }
        .icon_clear {
            position: absolute;
            top: 9px;
            right: 50px;
            padding: 0 10px;
            width: 35px;
            height: 25px;
            background: url('http://static.mingyizhudao.com/146717942005220') no-repeat;
            background-size: 15px 15px;
            background-position: 10px 5px;
        }
        ul, ol {
            margin-bottom: 0px;
        }
        #cat_name{
            height: 43px;
            padding: 10px 0px;
            border-bottom: 1px solid #d4d4d4!important;
        }
        #hospital_name{
            height: 43px;
            padding: 10px 0px;
            border-bottom: 1px solid #d4d4d4!important;
            overflow: hidden;
            text-overflow: ellipsis;
            -o-text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
        }
        .select-area{
            position: relative;
        }
        .select-area .select-value{
            display:inline-block;
            padding: 10px 0px;
            width: 100%;
            border-bottom: 1px solid #d4d4d4;
        }
        .select-area select{
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
        }
    </style>
    <body>
        <div id="jingle_loading" style="display: block;" class="loading initLoading"><i class="icon spinner"></i><p>加载中...</p><div id="tag_close_popup" data-target="closePopup" class="icon cancel-circle"></div></div>
        <div id="jingle_toast" class="toast" style="display: none;"><a href="#">请选择您的执业医院</a></div>
        <div id="jingle_loading_mask" style="opacity: 0; display: block;"></div>
        <section id="searchInput_section">
            <header class="">
                <div class="w100 pl10 pr10 grid">
                    <div class="col-1">
                        <i class="icon_search"></i>
                        <input class="icon_input" name="hospitalName" type="text" placeholder="搜索医院名称">
                            <a class="icon_clear hide"></a>
                    </div>
                    <div id="backHospital" class="col-0 pl10 color-black">
                        取消
                    </div>
                </div>
            </header>
            <article class="active" data-scroll="true">
                <div>
                    <ul id="searchHospital" class="list">

                    </ul>
                </div>
            </article>
        </section>
        <section id="search_section">
            <header id="bookingList_header" class="list_header bg-green">
                <div class="grid w100">
                    <div class="col-0 pl5 pr10">
                        <a id="btn-back-search" href="javascript:;">
                            <div class="pl5">
                                <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                            </div>
                        </a>
                    </div>
                    <div class="col-1 pt7 pb7 pr10">
                        <div class="searchInput">请输入医院名称</div>
                    </div>
                </div>
            </header>
            <article id="search" class="active" data-scroll="true">
                <div id='container' style="display:none"></div>
                <div class="area-select w100">
                    <div id="pick-province" class="pick-t">
                        <span>选择省份</span>
                        <img src="http://static.mingyizhudao.com/146735870119173"></div>
                    <div id="pick-city" class="pick-t">
                        <span>选择城市</span>
                        <img src="http://static.mingyizhudao.com/146735870119173"></div>
                    <div id="pick-province-layer" class="w100">
                        <div>
                            <p>热门省份</p>
                            <ul class="hotCitiesList">
                                <li data-id="1">北京</li>
                                <li data-id="9">上海</li>
                                <li data-id="19">广州</li>
                            </ul>
                            <p>全国省份</p>
                            <ul class="stateList">

                            </ul>
                        </div>
                    </div>
                    <div id="pick-city-layer" class="w100">
                        <div>
                            <p id="hotCP">热门城市</p>
                            <ul id="hotCList" class="hotCitiesList">
                                <li>北京</li>
                                <li>上海</li>
                                <li>广州</li>
                            </ul>
                            <p>全国城市</p>
                            <ul class="cityList">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="p10">
                    <h5>医院列表</h5>
                    <ul id="hospital-list" class="hospital-list">
                        <li>搜索中...</li> 
                    </ul>
                </div>
            </article>
        </section>

        <div id="section_container">
            <section id="main_section" class="active" data-init="true">
                <footer id="doctorSubmitBtn" class="bg-green color-white">
                    <div class="text-center">
                        继续填写手术信息
                    </div>
                    <div class="text-center">
                        <img src="http://static.mingyizhudao.com/147029008156318" class="w24p">
                    </div>
                </footer>
                <article class="active" data-scroll="true">
                    <a href="<?php echo $urlDoctorBasicView; ?>">
                        <div class="btn-back w100"></div>
                    </a>
                    <div class="pad10">
                        <div class="nav-crumbs">
                            <i></i><span>2</span>
                            职业信息
                            <i></i><i></i><span>3</span>
                        </div>
                        <div class="from-out">
                            <div class="from-contanier p10 w100">
                                <?php
                                $form = $this->beginWidget('CActiveForm', array(
                                    'id' => 'doctor-form',
                                    'htmlOptions' => array('data-url-action' => $urlSubmitDoctorView, 'data-url-return' => $urlMajorView),
                                    'enableClientValidation' => false,
                                    'clientOptions' => array(
                                        'validateOnSubmit' => true,
                                        'validateOnType' => true,
                                        'validateOnDelay' => 500,
                                        'errorCssClass' => 'error',
                                    ),
                                    'enableAjaxValidation' => false,
                                ));
                                echo CHtml::hiddenField("DoctorForm[id]", $model->id);
                                echo CHtml::hiddenField("DoctorForm[hospital_id]", $model->hospital_id);
                                echo CHtml::hiddenField("DoctorForm[category_id]", $model->category_id);
                                ?>
                                <input type="hidden" name="DoctorForm[hospital_name]" value="<?php echo $model->hospital_name; ?>"/>
                                <input type="hidden" name="DoctorForm[cat_name]" value="<?php echo $model->cat_name; ?>"/>
                                <div class="grid">
                                    <div class="col-0 w80p pt10">
                                        执业医院
                                    </div>
                                    <div class="col-1">
                                        <div id="hospital_name">
                                            <?php
                                            if ($model->hospital_name == '') {
                                                echo '<span class="color-gray">选择您的执业医院</span>';
                                            } else {
                                                echo $model->hospital_name;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid">
                                    <div class="col-0 w80p pt10">
                                        专业
                                    </div>
                                    <div class="col-1">
                                        <div id="cat_name">
                                            <?php
                                            if ($model->cat_name == '') {
                                                echo '<span class="color-gray">选择您的专业</span>';
                                            } else {
                                                echo $model->cat_name;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid">
                                    <div class="col-0 w80p pt10">
                                        医疗职称
                                    </div>
                                    <div class="col-1 select-area">
                                        <div class="color-gray select-value"></div>
                                        <?php
                                        echo $form->dropDownList($model, 'clinic_title', $model->loadOptionsClinicalTitle(), array(
                                            'name' => 'DoctorForm[clinic_title]',
                                            'data-text' => $model->clinic_title == null ? '' : $model->clinic_title,
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="grid">
                                    <div class="col-0 w80p pt10">
                                        教学职称
                                    </div>
                                    <div class="col-1 select-area">
                                        <div class="color-gray select-value"></div>
                                        <?php
                                        echo $form->dropDownList($model, 'academic_title', $model->loadOptionsAcademicTitle(), array(
                                            'name' => 'DoctorForm[academic_title]',
                                            'data-text' => $model->academic_title == null ? '' : $model->academic_title,
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <?php
                                $this->endWidget();
                                ?>
                            </div>
                        </div>
                    </div>
                </article>
                <script>
                    $(document).ready(function () {
                        var optionsSubcat = '<?php echo json_encode($model->options_subcat); ?>';
                        optionsSubcat = JSON.parse(optionsSubcat);
                        $('#jingle_loading.initLoading').remove();
                        $('#jingle_loading_mask').remove();
                        ajaxLoadAllStates();
                        //select默认灰色、选中黑色
                        $(".select-area .select-value").each(function () {
                            if ($(this).next("select").attr('data-text') != '') {
                                $(this).removeClass('color-gray');
                                $(this).text($(this).next("select").find("option:selected").text());
                            } else {
                                $(this).text('选择您的医疗职称');
                            }
                        });
                        $('.select-area select').click(function () {
                            var value = $(this).find("option:selected").text();
                            var selecteValue = $(this).parent('.select-area').find('.select-value');
                            if (selecteValue.text() == '选择您的医疗职称') {
                                selecteValue.removeClass('color-gray');
                                selecteValue.text(value);
                            }
                        });
                        $('.select-area select').change(function () {
                            var value = $(this).find("option:selected").text();
                            var selecteValue = $(this).parent('.select-area').find('.select-value');
                            selecteValue.removeClass('color-gray');
                            selecteValue.text(value);
                        });
                        //选择专业
                        $('#cat_name').click(function () {
                            var innerHtml = '<div id="major-layer" style="height:400px;" data-scroll="true">' +
                                    '<ul class="list">';
                            for (var options in optionsSubcat) {
                                innerHtml += '<li data-id="' + options + '">' + optionsSubcat[options] + '</li>';
                            }
                            innerHtml += '</ul></div>';
                            J.popup({
                                html: innerHtml,
                                pos: 'center'
                            });
                            $('#major-layer ul>li').click(function () {
                                var layer = $(this).text();
                                var layerId = $(this).attr('data-id');
                                $('input[name="DoctorForm[cat_name]"]').val(layer);
                                $('#cat_name').html(layer);
                                $('#DoctorForm_category_id').val(layerId);
                                $('#cat_name').parent().find('div.error').remove();
                                J.hideMask();
                            });
                        });
                        //选择职业医院
                        $('#hospital_name').click(function () {
                            $('#search_section').css('display', 'block');
                            if ($('#pick-city>span').text() == '选择城市') {
                                mapInit();
                            }
                        });
                        $('#btn-back-search').click(function () {
                            $('#search_section').css('display', 'none');
                            if ($('#pick-province-layer').css('display') == 'block') {
                                $('#pick-province').trigger('click');
                            }
                            if ($('#pick-city-layer').css('display') == 'block') {
                                $('#pick-city').trigger('click');
                            }
                        });

                        $('#pick-province').click(function () {
                            $('#pick-city').find('img').attr('src', 'http://static.mingyizhudao.com/146735870119173');
                            if ($('#pick-province-layer').css('display') == 'none') {
                                $('#pick-province').find('img').attr('src', 'http://static.mingyizhudao.com/147080773889815');
                                $('#pick-city-layer').css('display', 'none');
                                $('#pick-province-layer').css('display', 'block');
                            } else {
                                $('#pick-province').find('img').attr('src', 'http://static.mingyizhudao.com/146735870119173');
                                $('#pick-province-layer').css('display', 'none');
                            }
                        });
                        $('#pick-city').click(function () {
                            $('#pick-province').find('img').attr('src', 'http://static.mingyizhudao.com/146735870119173');
                            if ($('#pick-province>span').text()) {
                                $('#hotCList').hide();
                                $('#hotCP').hide();
                            }
                            if ($('#pick-city-layer').css('display') == 'none') {
                                $('#pick-city').find('img').attr('src', 'http://static.mingyizhudao.com/147080773889815');
                                $('#pick-province-layer').css('display', 'none');
                                $('#pick-city-layer').css('display', 'block');
                            } else {
                                $('#pick-city').find('img').attr('src', 'http://static.mingyizhudao.com/146735870119173');
                                $('#pick-city-layer').css('display', 'none');
                            }

                        });
                        $('#pick-province-layer').click(function () {
                            $('#pick-province-layer').css('display', 'none');
                        });
                        $('#pick-city-layer').click(function () {
                            $('#pick-city-layer').css('display', 'none');
                        });
                        $('.hotCitiesList>li').click(function () {
                            var statId = $(this).attr('data-id');
                            $('#pick-province').find('span').attr('data-common', 1);
                            $('#pick-province-layer ul.stateList>li').each(function () {
                                if ($(this).attr('data-id') == statId) {
                                    $(this).trigger('click');
                                }
                            });
                        });
                        //搜索医院
                        $('.searchInput').click(function () {
                            $('#search_section').css('display', 'none');
                            //清空输入框内容和搜索结果
                            $('#searchInput_section').find('.icon_clear').addClass('hide');
                            $('input[name="hospitalName"]').val('');
                            $('#searchHospital').html('');
                            $('#searchInput_section').css('display', 'block');
                            $('input[name="hospitalName"]').focus();
                        });
                        //返回定位
                        $('#backHospital').click(function () {
                            $('#searchInput_section').css('display', 'none');
                            $('#search_section').css('display', 'block');
                        });
                        //搜索
                        $('input[name="hospitalName"]').on('input', function (e) {
                            e.preventDefault();
                            var hospitalName = $('input[name="hospitalName"]').val();
                            hospitalName = Trim(hospitalName);
                            if (hospitalName == '') {
                                $('#searchInput_section').find('.icon_clear').addClass('hide');
                                $('#searchHospital').html('');
                                return;
                            } else if (hospitalName.match(/[a-zA-Z]/g) != null) {
                                $('#searchInput_section').find('.icon_clear').removeClass('hide');
                            } else {
                                $('#searchInput_section').find('.icon_clear').removeClass('hide');
                                ajaxSearchHospital(hospitalName);
                            }
                        });
                        //清空
                        $('#searchInput_section').find('.icon_clear').click(function () {
                            $(this).addClass('hide');
                            $('input[name="hospitalName"]').val('');
                            $('#searchHospital').html('');
                        });
                        function ajaxSearchHospital(hospitalName) {
                            $.ajax({
                                url: '<?php echo $urlHospital; ?>?name=' + hospitalName,
                                success: function (data) {
                                    readyHospital(data);
                                }
                            });
                        }
                        function readyHospital(data) {
                            var hospital = data.results.hospital;
                            var innerHtml = '';
                            if (hospital.length > 0) {
                                for (var i = 0; i < hospital.length; i++) {
                                    innerHtml += '<li data-id="' + hospital[i].id + '">' + hospital[i].name + '</li>';
                                }
                            } else {
                                innerHtml += '<li>未查询到</li>';
                            }
                            $('#searchHospital').html(innerHtml);
                            $('#searchHospital').find('li').click(function () {
                                var hpName = $(this).text();
                                var hpId = $(this).attr('data-id');
                                //未查询到
                                if (hpName == '未查询到') {
                                    return false;
                                }
                                $('input[name="DoctorForm[hospital_name]"]').val(hpName);
                                $('#hospital_name').html(hpName);
                                $('#DoctorForm_hospital_id').val(hpId);
                                $('#hospital_name').parent().find('div.error').remove();
                                $('#searchInput_section').css('display', 'none');
                            });
                        }
                        function Trim(str) {
                            return str.replace(/(^\s*)|(\s*$)/g, "");
                        }
                    }
                    );
                    var map, geolocation, geocoder;
                    function mapInit() {
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
                    }

                    //解析定位结果
                    function onComplete(data) {
                        var lnglatXY = [data.position.getLng(), data.position.getLat()];//地图上所标点的坐标
                        geocoder.getAddress(lnglatXY, function (status, result) {
                            if (status === 'complete' && result.info === 'OK') {
                                //获得了有效的地址信息: 城市province 编码adcode
                                //console.log(result.regeocode.addressComponent);
                                var res = result.regeocode.addressComponent;
                                var _city = res.city ? res.city : res.province;
                                getHospitalList(_city);
//                                setCity(_city == '上海市' ? '上海' : _city);
                                setProvince(res.province.substr(0, _city.length - 1));
                                setCity(_city.substr(0, _city.length - 1));
                            } else {
                                //获取地址失败
                            }
                        });
                    }
                    //解析定位错误信息
                    function onError(data) {
                        console.log('定位失败');
                        //document.getElementById('tip').innerHTML = '定位失败';
                    }

                    function setCity(cName) {
                        $('#pick-city').find('span').text(cName);
                    }

                    function setProvince(pName) {
                        //console.log('pName', pName);
                        $('#pick-province').find('span').text(pName);
                    }

                    var hospitalList = [];
                    function getHospitalList(cityname) {
                        var urlHospital = "<?php echo $urlHospital; ?>";
                        $.ajax({
                            type: 'get',
                            url: urlHospital + '?cityname=' + cityname,
                            'success': function (data) {
                                //console.log(data);
                                if (data.status === true || data.status === 'ok') {
                                    hospitalList = data.results.hospital;
                                    reflashHospitalList(hospitalList);
                                }
                                else {
                                    //console.log(data);
                                    if (data.errors.captcha_code != undefined) {
                                        $('#captchaCode').parents('div.input').append('<div id="ForgetPasswordForm_captcha_code-error" class="error">' + data.errors.captcha_code + '</div>');
                                    }
                                }
                            },
                            'error': function (data) {
                                console.log(data);
                            },
                            'complete': function () {
                            }
                        });
                    }

                    function reflashHospitalList(list) {
                        if (!list || !list.length) {
                            $('#hospital-list').html('<li>未查询到</li>');
                            return false;
                        }
                        var listHtml = '';
                        for (var i = 0; i < list.length; i++) {
                            var _li = '<li id="' + list[i].id + '">' + list[i].name + '</li>';
                            listHtml += _li;
                        }
                        $('#hospital-list').html(listHtml);
                        initHospitalClick();
                    }
                    function initHospitalClick() {
                        $('ul#hospital-list>li').click(function () {
                            var hpName = $(this).text();
                            var hpId = $(this).attr('id');
                            $('input[name="DoctorForm[hospital_name]"]').val(hpName);
                            $('#hospital_name').html(hpName);
                            $('#DoctorForm_hospital_id').val(hpId);
                            $('#hospital_name').parent().find('div.error').remove();
                            $('#search_section').css('display', 'none');
                        });
                    }
                    function ajaxLoadAllStates() {
                        var urlLoadAllStates = '<?php echo $urlLoadAllStates; ?>';
                        $.ajax({
                            url: urlLoadAllStates,
                            success: function (data) {
                                setStatesHtml(data);
                            }
                        });
                    }
                    function setStatesHtml(data) {
                        var innerHtml = '';
                        if (data) {
                            for (var i = 0; i < data.length; i++) {
                                var state = data[i];
                                innerHtml += '<li data-id="' + state.id + '">' + state.name + '</li>';
                            }
                        }
                        $('#pick-province-layer ul.stateList').html(innerHtml);
                        $('#pick-province-layer ul.stateList>li').click(function () {
                            $('#pick-province').find('img').attr('src', 'http://static.mingyizhudao.com/146735870119173');
                            var stateId = $(this).attr('data-id');
                            var stateName = $(this).text();
                            $('#pick-province>span').text(stateName);
                            ajaxLoadCitiesByStateId(stateId);
                        });
                    }
                    function ajaxLoadCitiesByStateId(id) {
                        var urlLoadCity = '<?php echo $urlLoadCity; ?>/' + id;
                        $.ajax({
                            url: urlLoadCity,
                            success: function (data) {
                                setCitiesHtml(data);
                            }
                        });
                    }
                    function setCitiesHtml(data) {
                        var innerHtml = '';
                        var onlyOne = false;
                        if (data) {
                            for (var i = 0; i < data.length; i++) {
                                var city = data[i];
                                if (i == 0) {
                                    if (data.length == 1) {
                                        onlyOne = true;
                                        $('#pick-city>span').text(data[0].name);
                                    } else {
                                        $('#pick-city>span').text('选择城市');
                                    }
                                }
                                innerHtml += '<li data-id="' + city.id + '">' + city.name + '</li>';
                            }
                        }
                        $('#pick-city-layer ul.cityList').html(innerHtml);
                        $('#pick-city-layer ul.cityList>li').click(function () {
                            $('#pick-city').find('img').attr('src', 'http://static.mingyizhudao.com/146735870119173');
                            var cityname = $(this).text();
                            $('#pick-city>span').text(cityname);
                            getHospitalList(cityname);
                        });
                        if (onlyOne) {
                            $('#pick-city-layer ul.cityList>li').first().trigger('click');
                        } else {
                            if ($('#pick-province').find('span').attr('data-common') == 1) {
                                $('#pick-city-layer ul.cityList>li').each(function () {
                                    if ($(this).attr('data-id') == 200) {
                                        $(this).trigger('click');
                                    }
                                });
                            }
                        }
                    }
                </script>
            </section>
        </div>
    </body>
</html>