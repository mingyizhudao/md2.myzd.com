<!DOCTYPE html> 
<html lang="zh" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta charset="utf-8" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="pragma" content="no-cache" />
        <title><?php echo $this->pageTitle; ?></title>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <?php
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.0.css');
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/custom.min.1.1.css');
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/zepto.min.1.0.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/common.min.1.0.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/main.min.1.0.js', CClientScript::POS_END);
        $urlAjaxSubCat = $this->createUrl('doctor/ajaxSubCat', array('id' => 6));
        $urlAjaxSurgery = $this->createUrl('doctor/ajaxSurgery', array('id' => 6));
        $urlAjaxMajor = $this->createUrl('doctor/ajaxMajor', array('id' => 6));
        $urlSuccess = $this->createUrl('doctor/success');
        $urlDoctorView = $this->createUrl('doctor/doctorView', array('id' => $model->id));
        $urlResImage = Yii::app()->theme->baseUrl . "/images/";
        ?>
    </head>
    <style>
        h1{
            padding-bottom: 0px;
        }
        .addIcon{
            padding: 3px 8px;
            color: #CECECE;
            border: 1px solid #CECECE;
            border-radius: 15px;
        }

        .list>li{
            background: url('http://static.mingyizhudao.com/146967375501082') no-repeat;
            background-size: 8px 15px;
            background-position-x: 95%;
            background-position-y: 50%;
        }
        .aSearch{
            margin: 7px 0px;
            background-color: #161109;
            height: 30px;
            line-height: 1em;
            -webkit-box-align: center;
            display: -webkit-box;
            text-align: center;
            border-radius: 5px;
        }
        .bg-silvery{
            background-color: #F9F8F8;
        }
        #major{
            position: fixed;
            width: 100%;
            z-index: 99;
        }
        .majorList li{
            padding: 10px;
            text-align: center;
        }
        .operationMajorList li {
            padding: 10px;
            text-align: center;
        }
        .pt44{
            padding-top: 44px;
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
        }
        .selectedIcon{
            padding: 3px 5px;
            border: 1px solid #57534E;
            white-space:nowrap;
            word-break:keep-all;
            border-radius: 12px;
            margin-right: 10px;
        }
        footer.hide~article{
            bottom: 0px;
        }
        #operationMajor {
            position: fixed;
            width: 100%;
            z-index: 99;
        }
        .btn-back{
            height: 40px;
            z-index: 1;
            background: url('<?php echo $urlResImage; ?>btn-back.png') center no-repeat;
            border: 0px;
            box-shadow: none;
            background-size: 30px;
        }
        #operationPopup li{
            background: url('') no-repeat;
        }
    </style>
    <body>
        <div id="section_container">
            <section id="main_section" class="active" data-init="true">
                <div id="one" class="">
                    <header class="bg-green">
                        <h1 class="title">医生</h1>
                    </header>
                    <footer class="bg-white">
                        <div id="complete" class="w100 bg-green color-white text-center">
                            填写完成
                        </div>
                    </footer>
                    <article class="active" data-scroll="true">
                        <div class="pad10">
                            <div>
                                <a href="<?php echo $urlDoctorView; ?>">
                                    <div class="btn-back w100"></div>
                                </a>
                            </div>
                            <div>
                                手术专业信息
                            </div>
                            <div class="pt20">
                                擅长疾病（最多可选10项）
                            </div>
                            <div class="pt5">
                                <span id="addDisease" class="addIcon">
                                    添加擅长疾病
                                </span>
                            </div>
                            <div id="diseaseShow">

                            </div>
                            <div class="pt20">
                                擅长手术（最多可选10项）
                            </div>
                            <div class="pt5">
                                <span id="addOperation" class="addIcon">
                                    添加擅长手术
                                </span>
                            </div>
                            <div id="operationShow">

                            </div>
                        </div>
                    </article>
                </div>
                <div id="two" class="hide">
                    <header id="disease_header" class="bg-silvery">
                        <div class="w100 pl10 pr10 grid">
                            <div class="col-0 pl5 pr10">
                                <a id="diseasePage" href="javascript:;">
                                    <div class="pl5">
                                        <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                                    </div>
                                </a>
                            </div>
                            <div class="col-1 aSearch">
                                搜索术式名称
                            </div>
                        </div>
                    </header>
                    <footer id="span_footer" class="bg-white hide">
                        <div class="grid w100">
                            <div id="spanList" class="col-1" style="overflow: hidden;padding-top: 13px;">

                            </div>
                            <div id="confirmDisease" class="col-0" style="border-left: 1px solid #C8C8C8;width: 70px;text-align: center;padding-top: 12px;color: #137EFF;">

                            </div>
                        </div>
                    </footer>
                    <article class="active" data-scroll="true">
                        <div>
                            <div id="major" class="bg-silvery">
                                <div id="allSubSpecialty" class="pad10 text-center">
                                    全部亚专业
                                </div>
                                <ul class="majorList">

                                </ul>
                            </div>
                            <div class="pl10 pr10 pb10 pt44">
                                <ul id="diseaseList" class="list">

                                </ul>
                            </div>
                        </div>
                    </article>
                </div>
                <div id="three" class="hide">
                    <header class="bg-silvery">
                        <div class="w100 pl10 pr10 grid">
                            <div class="col-1">
                                <i class="icon_search"></i>
                                <input class="icon_input" type="text" placeholder="您可以输入患者姓名，医生姓名"/>
                                <a class="icon_clear"></a>
                            </div>
                            <div id="searchBtn" class="col-0 pt5 pl10 color-black">
                                搜索
                            </div>
                        </div>
                    </header>
                    <article class="active" data-scroll="true">
                        <div>
                            <div class="pad10">
                                <ul class="list">
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                    <li>开颅探查术</li>
                                </ul>
                                <div>
                                    <div>
                                        没有找到擅长术式？
                                    </div>
                                    <div>
                                        创建术式：肿
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
                <div id="four" class="hide">
                    <header class="bg-silvery">
                        <div class="w100 pl10 pr10 grid">
                            <div class="col-0 pl5 pr10">
                                <a id="operationPage" href="javascript:;">
                                    <div class="pl5">
                                        <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                                    </div>
                                </a>
                            </div>
                            <div class="col-1 aSearch">
                                搜索术式名称
                            </div>
                        </div>
                    </header>
                    <footer id="operation_footer" class="bg-white hide">
                        <div class="grid w100">
                            <div id="operationList" class="col-1" style="overflow: hidden;padding-top: 13px;">

                            </div>
                            <div id="confirmOperation" class="col-0" style="border-left: 1px solid #C8C8C8;width: 70px;text-align: center;padding-top: 12px;color: #137EFF;">

                            </div>
                        </div>
                    </footer>
                    <article class="active" data-scroll="true">
                        <div>
                            <div id="operationMajor" class="bg-silvery">
                                <div id="operationSubSpecialty" class="pad10 text-center">
                                    全部亚专业
                                </div>
                                <ul class="operationMajorList">

                                </ul>
                            </div>
                            <div class="pl10 pr10 pb10 pt44">
                                <ul id="operationListSelected" class="list">

                                </ul>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        </div>
    </body>
</html>
<script>
    $(document).ready(function () {
        //添加擅长疾病
        $('#addDisease').click(function () {
            $('#one').addClass('hide');
            $('#two').removeClass('hide');
            J.customConfirm('',
                    '<div class="mt10 mb10">请根据您对手术的擅长程度按顺序选择</div>',
                    '<a id="closeLogout" class="w100">我知道了</a>',
                    '',
                    function () {
                    },
                    function () {
                    });
            $('#closeLogout').click(function () {
                J.closePopup();
            });
        });
        //返回
        $('#diseasePage').click(function () {
            $('#two').addClass('hide');
            $('#one').removeClass('hide');
        });
        //加载亚专业
        $.ajax({
            url: '<?php echo $urlAjaxSubCat; ?>',
            success: function (data) {
                readySubSpecialty(data);
            }
        });
        function readySubSpecialty(data) {
            var innerHtml = '';
            var diseaseHtml = '';
            var subcatList = data.results.subcatList;
            if (subcatList.length > 0) {
                for (var i = 0; i < subcatList.length; i++) {
                    innerHtml += '<li class="changeSubSpecialty" data-i="' + i + '" data-id="' + subcatList[i].subCatId + '">' + subcatList[i].subCatName + '</li>';
                    var diseaseList = subcatList[i].diseaseList;
                    if (diseaseList.length > 0) {
                        for (var j = 0; j < diseaseList.length; j++) {
                            diseaseHtml += '<li class="selectLi grid" data-num="' + diseaseList[j].diseaseId + '">' +
                                    '<div class="col-1">' + diseaseList[j].diseaseName +
                                    '</div>' +
                                    '<div class="col-0 w100p">' +
                                    '</div>' +
                                    '</li>';
                        }
                    }
                }
            }
            $('.majorList').html(innerHtml);
            $('#diseaseList').html(diseaseHtml);
            diseaseSelected();
            //展开亚专业列表
            $('#allSubSpecialty').click(function () {
                if ($('.majorList').hasClass('hide')) {
                    $('.majorList').removeClass('hide');
                } else {
                    $('.majorList').addClass('hide');
                }
            });
            $('.changeSubSpecialty').click(function () {
                var diseaseList = subcatList[$(this).attr('data-i')].diseaseList;
                var innerList = '';
                if (diseaseList.length > 0) {
                    for (var i = 0; i < diseaseList.length; i++) {
                        innerList += '<li class="selectLi grid" data-num="' + diseaseList[i].diseaseId + '">' +
                                '<div class="col-1">' + diseaseList[i].diseaseName +
                                '</div>' +
                                '<div class="col-0 w100p">' +
                                '</div>' +
                                '</li>';
                    }
                }
                $('.majorList').addClass('hide');
                $('#diseaseList').html(innerList);
                diseaseSelected();
            });
        }

        //选择疾病
        //var nnn = 1;
        var dataArray = new Array();
        var nameArray = new Array();
        function diseaseSelected() {
            $('.selectLi').click(function () {
                //添加数组
                if ($(this).attr('data-active') != 1) {
                    $(this).attr('data-active', 1);
                    var num = dataArray.push($(this).attr('data-num'));
                    nameArray.push($(this).find('.col-1').html());
                    $(this).attr('data-num', $(this).attr('data-num'));
                    $(this).attr('data-id', num);
                    //nnn++;
                    if (num < 4) {
                        $(this).addClass('color-green');
                        $(this).find('.w100p').html(num);
                    } else {
                        $(this).addClass('color-gray');
                        $(this).find('.w100p').html('中');
                    }
                } else {
                    var numData = $(this).attr('data-id');
                    dataArray.splice(numData - 1, 1);
                    nameArray.splice(numData - 1, 1);
                    $('li').each(function () {
                        $(this).removeClass('color-green');
                        $(this).removeClass('color-gray');
                        $(this).removeAttr('data-active');
                        $(this).find('.w100p').html('');
                    });
                    for (var i = 0; i < dataArray.length; i++) {
                        var dataActive = dataArray[i];
                        $('li').each(function () {
                            if ($(this).attr('data-num') == dataActive) {
                                $(this).attr('data-active', 1);
                                $(this).attr('data-id', i + 1);
                                if (i < 3) {
                                    $(this).find('.w100p').html(i + 1);
                                    $(this).addClass('color-green');
                                } else {
                                    $(this).find('.w100p').html('中');
                                    $(this).addClass('color-gray');
                                }
                            }
                        });
                    }
                }
                if (nameArray.length > 0) {
                    var innerSpan = '';
                    nameArray.reverse();
                    for (var i = 0; i < nameArray.length; i++) {
                        innerSpan += '<span class="selectedIcon">' + nameArray[i] + '</span>';
                    }
                    $('#spanList').html(innerSpan);
                    $('#confirmDisease').html('确定(' + nameArray.length + ')');
                    $('#span_footer').removeClass('hide');
                    nameArray.reverse();
                } else {
                    $('#spanList').html('');
                    $('#confirmDisease').html('');
                    $('#span_footer').addClass('hide');
                }
            });
        }

        //完成疾病选择
        $('#confirmDisease').click(function () {
            var diseaseShow = '';
            if (nameArray.length > 0) {
                for (var i = 0; i < nameArray.length; i++) {
                    diseaseShow += '<div>' + (i + 1) + '.' + nameArray[i] + '</div>';
                }
            }
            $('#diseaseShow').html(diseaseShow);
            $('#two').addClass('hide');
            $('#one').removeClass('hide');
        });

        //添加擅长手术
        $('#addOperation').click(function () {
            $('#one').addClass('hide');
            $('#four').removeClass('hide');
        });
        //返回页面
        $('#operationPage').click(function () {
            $('#four').addClass('hide');
            $('#one').removeClass('hide');
        });

        //加载术式
        $.ajax({
            url: '<?php echo $urlAjaxSurgery; ?>',
            success: function (data) {
                readyOperation(data);
            }
        });
        function readyOperation(data) {
            var innerHtml = '';
            var operationSurgeryList = '';
            var subcatList = data.results.subcatList;
            if (subcatList.length > 0) {
                for (var i = 0; i < subcatList.length; i++) {
                    innerHtml += '<li class="changeOperationSub" data-i="' + i + '" data-id="' + subcatList[i].subCatId + '">' + subcatList[i].subCatName + '</li>';
                    var surgeryList = subcatList[i].surgeryList;
                    if (surgeryList.length > 0) {
                        for (var j = 0; j < surgeryList.length; j++) {
                            operationSurgeryList += '<li class="operationLi grid" data-num="' + surgeryList[j].surgeryId + '">' +
                                    '<div class="col-1">' + surgeryList[j].surgeryName +
                                    '</div>' +
                                    '<div class="col-0 w100p">' +
                                    '</div>' +
                                    '</li>';
                        }
                    }
                }
            }
            $('.operationMajorList').html(innerHtml);
            $('#operationListSelected').html(operationSurgeryList);
            operationSelected();
            $('#operationSubSpecialty').click(function () {
                if ($('.operationMajorList').hasClass('hide')) {
                    $('.operationMajorList').removeClass('hide');
                } else {
                    $('.operationMajorList').addClass('hide');
                }
            });
            $('.changeOperationSub').click(function () {
                var surgeryList = subcatList[$(this).attr('data-i')].surgeryList;
                var innerHtml = '';
                if (surgeryList.length > 0) {
                    for (var i = 0; i < surgeryList.length; i++) {
                        innerHtml += '<li class="operationLi grid" data-num="' + surgeryList[i].surgeryId + '">' +
                                '<div class="col-1">' + surgeryList[i].surgeryName +
                                '</div>' +
                                '<div class="col-0 w100p">' +
                                '</div>' +
                                '</li>';
                    }
                }
                $('.operationMajorList').addClass('hide');
                $('#operationListSelected').html(innerHtml);
                operationSelected();
            });
        }

        //选择手术
        //当前id
        var operationId = 0;
        //var operationNum = 1;
        var operationArray = new Array();
        var operationNameArray = new Array();
        function operationSelected() {
            $('.operationLi').click(function () {
                var operationObject = new Object();
                operationObject.id = '';
                operationObject.method = new Array();
                operationObject.num = new Array();

                //添加数组
                if ($(this).attr('data-active') != 1) {
                    $(this).attr('data-active', 1);
                    operationObject.id = $(this).attr('data-num');
                    var num = operationArray.push(operationObject);
                    operationNameArray.push($(this).find('.col-1').html());
                    $(this).attr('data-num', $(this).attr('data-num'));
                    $(this).attr('data-id', num);
                    operationId = num;
                    //operationNum++;
                    if (num < 4) {
                        $(this).addClass('color-green');
                        $(this).find('.w100p').html(num);
                    } else {
                        $(this).addClass('color-gray');
                        $(this).find('.w100p').html('中');
                    }
                }

                //展示弹窗
                var innerHtml = '<div id="operationPopup">' +
                        '<div>第一擅长术式</div>' +
                        '<div>颅内硬膜外血肿</div>' +
                        '<div>该术式擅长的方法</div>' +
                        '<ul class="list">';
                var methodActiveOne = '';
                var methodActiveTwo = '';
                var methodActiveThree = '';
                if (operationArray.length > 0) {
                    if (activeInspect(operationArray[operationId - 1].method, 1)) {
                        methodActiveOne = 'active';
                    }
                    if (activeInspect(operationArray[operationId - 1].method, 2)) {
                        methodActiveTwo = 'active';
                    }
                    if (activeInspect(operationArray[operationId - 1].method, 3)) {
                        methodActiveThree = 'active';
                    }
                }
                innerHtml += '<li class="grid methodLi ' + methodActiveOne + '" data-id="1">' +
                        '<div class="col-1">' +
                        '开放' +
                        '</div>' +
                        '<div class="col-0 w100p">' +
                        '' +
                        '</div>' +
                        '</li>' +
                        '<li class="grid methodLi ' + methodActiveTwo + '" data-id="2">' +
                        '<div class="col-1">' +
                        '控镜' +
                        '</div>' +
                        '<div class="col-0 w100p">' +
                        '' +
                        '</div>' +
                        '</li>' +
                        '<li class="grid methodLi ' + methodActiveThree + '" data-id="3">' +
                        '<div class="col-1">' +
                        '机器人' +
                        '</div>' +
                        '<div class="col-0 w100p">' +
                        '' +
                        '</div>' +
                        '</li>' +
                        '</ul>' +
                        '<div id="selectOver" class="pad10">' +
                        '选择完毕' +
                        '</div>' +
                        '</div>';
                J.popup({
                    html: innerHtml,
                    pos: 'center'
                });

                methodChange();

                function methodChange() {
                    $('.methodLi').click(function () {
                        if (!($(this).hasClass('active'))) {
                            var id = $(this).attr('data-id');
                            var innerHtml = '<div>' +
                                    '<ul class="list">' +
                                    '<li class="grid numLi" data-num="10,20">' +
                                    '<div class="col-1">' +
                                    '10-20' +
                                    '</div>' +
                                    '<div class="col-0 w100p">' +
                                    '' +
                                    '</div>' +
                                    '</li>' +
                                    '<li class="grid numLi" data-num="20,50">' +
                                    '<div class="col-1">' +
                                    '20-50' +
                                    '</div>' +
                                    '<div class="col-0 w100p">' +
                                    '' +
                                    '</div>' +
                                    '</li>' +
                                    '<li class="grid numLi" data-num="50,100">' +
                                    '<div class="col-1">' +
                                    '50-100' +
                                    '</div>' +
                                    '<div class="col-0 w100p">' +
                                    '' +
                                    '</div>' +
                                    '</li>' +
                                    '</ul>' +
                                    '</div>';
                            J.popup({
                                html: innerHtml,
                                pos: 'center'
                            });
                            $('.numLi').click(function () {
                                var dataNum = $(this).attr('data-num');
                                operationArray[operationId - 1].method.push(id);
                                operationArray[operationId - 1].num.push(dataNum);
                                J.hideMask();
                                var methodSelected = operationArray[operationId - 1].method;
                                var innerHtml = '<div>' +
                                        '<div>第一擅长术式</div>' +
                                        '<div>颅内硬膜外血肿</div>' +
                                        '<div>该术式擅长的方法</div>' +
                                        '<ul class="list">';
                                var methodOne = '';
                                var methodTwo = '';
                                var methodThree = '';
                                if (activeInspect(methodSelected, 1)) {
                                    methodOne = 'active';
                                }
                                if (activeInspect(methodSelected, 2)) {
                                    methodTwo = 'active';
                                }
                                if (activeInspect(methodSelected, 3)) {
                                    methodThree = 'active';
                                }
                                innerHtml += '<li class="grid methodLi ' + methodOne + '" data-id="1">' +
                                        '<div class="col-1">' +
                                        '开放' +
                                        '</div>' +
                                        '<div class="col-0 w100p">' +
                                        '' +
                                        '</div>' +
                                        '</li>' +
                                        '<li class="grid methodLi ' + methodTwo + '" data-id="2">' +
                                        '<div class="col-1">' +
                                        '控镜' +
                                        '</div>' +
                                        '<div class="col-0 w100p">' +
                                        '' +
                                        '</div>' +
                                        '</li>' +
                                        '<li class="grid methodLi ' + methodThree + '" data-id="3">' +
                                        '<div class="col-1">' +
                                        '机器人' +
                                        '</div>' +
                                        '<div class="col-0 w100p">' +
                                        '' +
                                        '</div>' +
                                        '</li>' +
                                        '</ul>' +
                                        '<div id="selectOver" class="pad10">' +
                                        '选择完毕' +
                                        '</div>' +
                                        '</div>';
                                J.popup({
                                    html: innerHtml,
                                    pos: 'center'
                                });
                                methodChange(operationArray);
                            });
                        } else {
                            $(this).removeClass('active');
                            var numData = $(this).attr('data-id');
                            for (var i = 0; i < operationArray[operationId - 1].method.length; i++) {
                                if (operationArray[operationId - 1].method[i] == numData) {
                                    operationArray[operationId - 1].method.splice(i, 1);
                                    operationArray[operationId - 1].num.splice(i, 1);
                                }
                            }
                        }
                    });
                    //选择完毕
                    $('#selectOver').click(function () {
                        if (operationArray[operationId - 1].method.length == 0) {
                            operationArray.splice(operationId - 1, 1);
                            operationNameArray.splice(operationId - 1, 1);
                        }
                        J.hideMask();
                        //根据选择情况，重新绘制列表框
                        $('#four li').each(function () {
                            $(this).removeClass('color-green');
                            $(this).removeClass('color-gray');
                            $(this).removeAttr('data-active');
                            $(this).find('.w100p').html('');
                        });
                        for (var i = 0; i < operationArray.length; i++) {
                            var dataActive = operationArray[i].id;
                            $('#four li').each(function () {
                                if ($(this).attr('data-num') == dataActive) {
                                    $(this).attr('data-active', 1);
                                    $(this).attr('data-id', i + 1);
                                    if (i < 3) {
                                        $(this).find('.w100p').html(i + 1);
                                        $(this).addClass('color-green');
                                    } else {
                                        $(this).find('.w100p').html('中');
                                        $(this).addClass('color-gray');
                                    }
                                }
                            });
                        }
                        if (operationNameArray.length > 0) {
                            var innerSpan = '';
                            operationNameArray.reverse();
                            for (var i = 0; i < operationNameArray.length; i++) {
                                innerSpan += '<span class="selectedIcon">' + operationNameArray[i] + '</span>';
                            }
                            $('#operationList').html(innerSpan);
                            $('#confirmOperation').html('确定(' + operationNameArray.length + ')');
                            $('#operation_footer').removeClass('hide');
                            operationNameArray.reverse();
                        } else {
                            $('#operationList').html('');
                            $('#confirmOperation').html('');
                            $('#operation_footer').addClass('hide');
                        }
                    });
                }
            });
        }

        //完成疾病选择
        $('#confirmOperation').click(function () {
            var operationShow = '';
            if (operationNameArray.length > 0) {
                for (var i = 0; i < operationNameArray.length; i++) {
                    operationShow += '<div>' + (i + 1) + '.' + operationNameArray[i] + '</div>';
                }
            }
            $('#operationShow').html(operationShow);
            $('#four').addClass('hide');
            $('#one').removeClass('hide');
        });

        //填写完成
        $('#complete').click(function () {
            $.ajax({
                type: 'post',
                url: '<?php echo $urlAjaxMajor; ?>',
                data: {'MajorForm[id]': 6, 'MajorForm[diseaseList]': dataArray, 'MajorForm[surgeryList]': structure_data(operationArray)},
                success: function (data) {
                    if (data.status = 'ok') {
                        location.href = '<?php echo $urlSuccess; ?>';
                    }
                }
            });
        });

        function structure_data(operationArray) {
            var dataArray = new Array();
            if (operationArray.length > 0) {
                for (var i = 0; i < operationArray.length; i++) {
                    for (var j = 0; j < operationArray[i].method.length; j++) {
                        var dataObj = new Object();
                        dataObj.id = operationArray[i].id;
                        dataObj.type = operationArray[i].method[j];
                        dataObj.min = operationArray[i].num[j].split(',')[0];
                        dataObj.max = operationArray[i].num[j].split(',')[1];
                        dataArray.push(dataObj);
                    }
                }
            }
            return dataArray;
        }

        //检验是否选中
        function activeInspect(dataArray, id) {
            var activeBoolen = false;
            if (dataArray.length > 0) {
                for (var i = 0; i < dataArray.length; i++) {
                    if (dataArray[i] == id) {
                        activeBoolen = true;
                        return true;
                    }
                }
            }
            return activeBoolen;
        }
    });
</script>