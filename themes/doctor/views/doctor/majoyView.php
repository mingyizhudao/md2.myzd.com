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
        <?php
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.0.css');
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/custom.min.1.1.css');
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/zepto.min.1.0.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/doctor/common.min.1.0.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/main.min.1.0.js', CClientScript::POS_END);
        $urlId = $model->id;
        $urlAjaxSubCat = $this->createUrl('doctor/ajaxSubCat', array('id' => $urlId));
        $urlAjaxSurgery = $this->createUrl('doctor/ajaxSurgery', array('id' => $urlId));
        $urlAjaxMajor = $this->createUrl('doctor/ajaxMajor', array('id' => $model->id));
        $urlSuccess = $this->createUrl('doctor/success');
        $urlDoctorView = $this->createUrl('doctor/doctorView', array('id' => $model->id));
        $urlAjaxSearchSub = $this->createUrl('doctor/ajaxSearchSub');
        $urlAjaxSearchSurgery = $this->createUrl('doctor/ajaxSearchSurgery');
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
        .addIcon.ban{
            color: #fff;
            border: 1px solid #fff;
            background-color: #A0A0A0;
        }
        .aSearch{
            margin: 7px 0px;
            background: #fff url('http://static.mingyizhudao.com/146243645256928') no-repeat;
            background-size: 15px 15px;
            background-position: 7px 7px;
            padding-left: 30px;
            height: 30px;
            line-height: 1em;
            -webkit-box-align: center;
            display: -webkit-box;
            text-align: center;
            border-radius: 5px;
            color: #A9A9A9;
        }
        .bg-silvery{
            background-color: #F9F8F8;
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
        .btn-back{
            height: 40px;
            z-index: 1;
            background: url('http://static.mingyizhudao.com/147029023304790') center no-repeat;
            border: 0px;
            box-shadow: none;
            background-size: 30px;
        }
        #operationPopup li{
            background: url('http://static.mingyizhudao.com/147081064615491') no-repeat;
            background-size: 25px 25px;
            background-position-x: 95%;
            background-position-y: 50%;
        }
        #operationPopup li.active{
            background: url('http://static.mingyizhudao.com/147081085820057') no-repeat;
            background-color: #fff!important;
            background-size: 25px 25px;
            background-position-x: 95%;
            background-position-y: 50%;
        }

        .nav-crumbs {
            display: flex;
            align-items: center;
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
        .selectLi .selectBtn{
            width: 26px;
            height: 26px;
            border: 1px solid #969696;
            margin-right: 10px;
            border-radius: 50%;
            text-align: center;
        }
        .selectLi.foreFront .selectBtn{
            border: 1px solid #fff;
            background-color: #F5595A;
            color: #fff;
        }
        .selectLi.behind .selectBtn{
            border: 1px solid #fff;
            background-color: #19aea6;
        }
        .operationLi .selectBtn{
            width: 26px;
            height: 26px;
            border: 1px solid #969696;
            margin-right: 10px;
            border-radius: 50%;
            text-align: center;
        }
        .operationLi.foreFront .selectBtn{
            border: 1px solid #fff;
            background-color: #F5595A;
            color: #fff;
        }
        .operationLi.behind .selectBtn{
            border: 1px solid #fff;
            background-color: #19aea6;
        }
        #selectOver{
            background-color: #32c9c0;
            color: #fff;
            border-radius: 5px;
        }
    </style>
    <body>
        <div id="section_container">
            <section id="main_section" class="active" data-init="true">
                <div id="one" class="">
                    <footer class="bg-white">
                        <div id="complete" class="w100 bg-green color-white text-center">
                            <div>填写完成</div>
                            <img src="http://static.mingyizhudao.com/147029008156318" class="w24p">
                        </div>
                    </footer>
                    <article class="active" data-scroll="true">
                        <div class="pad10">
                            <div>
                                <a href="<?php echo $urlDoctorView; ?>">
                                    <div class="btn-back w100"></div>
                                </a>
                            </div>
                            <div class="nav-crumbs">
                                <i></i><span>3</span>
                                手术专业信息
                            </div>
                            <div class="pt20">
                                擅长疾病（最多可选10项）
                            </div>
                            <div id="diseaseShow">

                            </div>
                            <div class="pt5">
                                <span id="addDisease" class="addIcon">
                                    添加擅长疾病
                                </span>
                            </div>
                            <div class="pt20">
                                擅长手术（最多可选10项）
                            </div>
                            <div id="operationShow">

                            </div>
                            <div class="pt5">
                                <span id="addOperation" class="addIcon">
                                    添加擅长手术
                                </span>
                            </div>
                        </div>
                    </article>
                </div>
                <div id="two" class="hide">
                    <header id="disease_header" class="bg-green">
                        <div class="w100 pl10 pr10 grid">
                            <div class="col-0 pl5 pr10">
                                <a id="diseasePage" href="javascript:;">
                                    <div class="pl5">
                                        <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                                    </div>
                                </a>
                            </div>
                            <div id="searchDisease" class="col-1 aSearch">
                                搜索疾病名称
                            </div>
                        </div>
                    </header>
                    <footer id="span_footer" class="bg-white hide">
                        <div class="grid w100">
                            <div id="spanList" class="col-1" style="overflow: auto;padding-top: 13px;height: 50px;">

                            </div>
                            <div id="confirmDisease" class="col-0" style="border-left: 1px solid #C8C8C8;width: 70px;text-align: center;padding-top: 12px;color: #137EFF;">

                            </div>
                        </div>
                    </footer>
                    <article class="active" data-scroll="true">
                        <div>
                            <div class="bg-silvery">
                                <div id="subCurrentSelection" class="pad10 text-center bb-gray">
                                    <span></span>
                                </div>
                                <ul class="majorList">

                                </ul>
                            </div>
                            <div id="diseaseUl" class="">

                            </div>
                        </div>
                    </article>
                </div>
                <div id="three" class="hide">
                    <header class="bg-silvery">
                        <div class="w100 pl10 pr10 grid">
                            <div class="col-1">
                                <i class="icon_search"></i>
                                <input class="icon_input" name="searchName" type="text" placeholder="搜索疾病名称"/>
                                <a class="icon_clear hide"></a>
                            </div>
                            <div id="backDisease" class="col-0 pl10 color-black">
                                取消
                            </div>
                        </div>
                    </header>
                    <article class="active" data-scroll="true">
                        <div>
                            <div class="">
                                <ul id="searchDiseaseList" class="list">

                                </ul>
                            </div>
                        </div>
                    </article>
                </div>
                <div id="four" class="hide">
                    <header class="bg-green">
                        <div class="w100 pl10 pr10 grid">
                            <div class="col-0 pl5 pr10">
                                <a id="operationPage" href="javascript:;">
                                    <div class="pl5">
                                        <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                                    </div>
                                </a>
                            </div>
                            <div id="searchOperation" class="col-1 aSearch">
                                搜索术式名称
                            </div>
                        </div>
                    </header>
                    <footer id="operation_footer" class="bg-white hide">
                        <div class="grid w100">
                            <div id="operationList" class="col-1" style="overflow: auto;padding-top: 13px;height:50px;">

                            </div>
                            <div id="confirmOperation" class="col-0" style="border-left: 1px solid #C8C8C8;width: 70px;text-align: center;padding-top: 12px;color: #137EFF;">

                            </div>
                        </div>
                    </footer>
                    <article class="active" data-scroll="true">
                        <div>
                            <div class="bg-silvery">
                                <div id="operationSubSpecialty" class="pad10 text-center bb-gray">
                                    <span></span>
                                </div>
                                <ul class="operationMajorList">

                                </ul>
                            </div>
                            <div id="operationListSelected" class="">

                            </div>
                        </div>
                    </article>
                </div>
                <div id="five" class="hide">
                    <header class="bg-silvery">
                        <div class="w100 pl10 pr10 grid">
                            <div class="col-1">
                                <i class="icon_search"></i>
                                <input class="icon_input" name="operationName" type="text" placeholder="搜索术式名称"/>
                                <a class="icon_clear hide"></a>
                            </div>
                            <div id="backOperation" class="col-0 pl10 color-black">
                                取消
                            </div>
                        </div>
                    </header>
                    <article class="active" data-scroll="true">
                        <div>
                            <div class="">
                                <ul id="searchOperationList" class="list">

                                </ul>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        </div>
        <div id="jingle_toast" class="toast" style="display: none;"><a href="#">疾病或手术未选择</a></div>
        <div id="test"></div>
    </body>
</html>
<script>
    $(document).ready(function () {
        //添加擅长疾病
        var diseaseData = true;
        $('#addDisease').click(function () {
            if ($(this).hasClass('ban')) {
                return;
            }
            $('#one').addClass('hide');
            $('#two').removeClass('hide');
            if (diseaseData) {
                //加载亚专业
                J.showMask();
                $.ajax({
                    url: '<?php echo $urlAjaxSubCat; ?>',
                    success: function (data) {
                        readySubSpecialty(data);
                    }
                });
            }
        });
        function readySubSpecialty(data) {
            var diseaseHtml = '';
            var commonDisease = '';
            var allDisease = '';
            var commonBool = false;
            var subCatName = '';
            var subcatList = data.results.subcatList;
            if (subcatList.length > 0) {
                for (var i = 0; i < subcatList.length; i++) {
                    var diseaseList = subcatList[i].diseaseList;
                    subCatName = subcatList[i].subCatName;
                    commonDisease += '<div class="pad10 bg-gray3">常见疾病</div><ul class="list">';
                    allDisease += '<div class="pad10 bg-gray3">全部疾病</div><ul class="list subSpecialtyList" data-subCatId="' + subcatList[i].subCatId + '">';
                    if (diseaseList.length > 0) {
                        for (var j = 0; j < diseaseList.length; j++) {
                            if (diseaseList[j].isCommon == 1) {
                                commonBool = true;
                                commonDisease += '<li class="selectLi grid" data-num="' + diseaseList[j].diseaseId + '">' +
                                        '<div class="col-0 grid middle">' +
                                        '<div class="selectBtn"></div>' +
                                        '</div>' +
                                        '<div class="col-1">' + diseaseList[j].diseaseName +
                                        '</div>' +
                                        '</li>';
                                allDisease += '<li class="selectLi grid" data-repeat="1" data-num="' + diseaseList[j].diseaseId + '">' +
                                        '<div class="col-0 grid middle">' +
                                        '<div class="selectBtn"></div>' +
                                        '</div>' +
                                        '<div class="col-1">' + diseaseList[j].diseaseName +
                                        '</div>' +
                                        '</li>';
                            } else {
                                allDisease += '<li class="selectLi grid" data-num="' + diseaseList[j].diseaseId + '">' +
                                        '<div class="col-0 grid middle">' +
                                        '<div class="selectBtn"></div>' +
                                        '</div>' +
                                        '<div class="col-1">' + diseaseList[j].diseaseName +
                                        '</div>' +
                                        '</li>';
                            }
                        }
                    }
                    commonDisease += '</ul>';
                    allDisease += '</ul>';
                    if (commonBool) {
                        diseaseHtml += commonDisease;
                    }
                    diseaseHtml += allDisease;
                }
            }
            $('#subCurrentSelection').find('span').text(subCatName);
            $('#diseaseUl').html(diseaseHtml);
            diseaseSelected();
            diseaseData = false;
            J.hideMask();

            J.customConfirm('',
                    '<div class="mt10 mb10">请根据您对疾病的擅长程度按顺序选择</div>',
                    '<a id="closeLogout" class="w100">我知道了</a>',
                    '',
                    function () {
                    },
                    function () {
                    });
            $('#closeLogout').click(function () {
                J.closePopup();
            });
        }

        //选择疾病
        //var nnn = 1;
        var dataArray = new Array();
        var nameArray = new Array();
        function diseaseSelected() {
            $('.selectLi').click(function () {
                //隐藏专业列表
                $('.majorList').addClass('hide');
                //添加数组
                if ($(this).attr('data-active') != 1) {
                    //判断是否超出10
                    if (dataArray.length >= 10) {
                        J.customConfirm('',
                                '<div class="mt10 mb10">最多只能选择10中主治疾病</div>',
                                '<a id="closeLogout" class="w100">我知道了</a>',
                                '',
                                function () {
                                },
                                function () {
                                });
                        $('#closeLogout').click(function () {
                            J.closePopup();
                        });
                        return;
                    }
                    //添加选择
                    dataArray.push($(this).attr('data-num'));
                    nameArray.push($(this).find('.col-1').html());
                    rePainting();
                } else {
                    var numData = $(this).attr('data-id');
                    dataArray.splice(numData - 1, 1);
                    nameArray.splice(numData - 1, 1);
                    rePainting();
                }
                if (nameArray.length > 0) {
                    var innerSpan = '';
                    nameArray.reverse();
                    for (var i = 0; i < nameArray.length; i++) {
                        var dataNum = nameArray.length - i;
                        innerSpan += '<span class="selectedIcon" data-num="' + dataNum + '">' + nameArray[i] + '</span>';
                    }
                    $('#spanList').html(innerSpan);
                    //删除选中
                    $('.selectedIcon').click(function () {
                        var dataNum = $(this).attr('data-num');
                        var dataTerm = dataArray[dataNum - 1];
                        $('.selectLi').each(function () {
                            var liNum = $(this).attr('data-num');
                            var repeat = $(this).attr('data-repeat');
                            if (repeat != 1) {
                                if (dataTerm == liNum) {
                                    $(this).trigger('click');
                                }
                            }
                        });
                    });
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
            readySelectDisease();
            $('#two').addClass('hide');
            $('#one').removeClass('hide');
        });

        function readySelectDisease() {
            var diseaseShow = '';
            if (nameArray.length > 0) {
                for (var i = 0; i < nameArray.length; i++) {
                    diseaseShow += '<div class="deleteDisease" data-num="' + i + '">' + (i + 1) + '.' + nameArray[i] + '</div>';
                }
            }
            $('#diseaseShow').html(diseaseShow);
            //当疾病达到上限，添加按钮不可点击
            if (nameArray.length >= 10) {
                $('#addDisease').addClass('ban');
            } else {
                $('#addDisease').removeClass('ban');
            }

            //通过点击删除已选疾病
            $('.deleteDisease').click(function () {
                var dataNum = $(this).attr('data-num');
                var dataTerm = dataArray[dataNum];
                $('.selectLi').each(function () {
                    var liNum = $(this).attr('data-num');
                    var repeat = $(this).attr('data-repeat');
                    if (repeat != 1) {
                        if (dataTerm == liNum) {
                            $(this).trigger('click');
                            readySelectDisease();
                        }
                    }
                });
            });
        }

        //返回
        $('#diseasePage').click(function () {
            $('#two').addClass('hide');
            $('#one').removeClass('hide');
            $('#confirmDisease').trigger('click');
        });

        //重绘选项
        function rePainting() {
            $('#diseaseUl li').each(function () {
                $(this).removeClass('foreFront');
                $(this).removeClass('behind');
                $(this).removeAttr('data-active');
                $(this).find('.selectBtn').html('');
            });
            for (var i = 0; i < dataArray.length; i++) {
                var dataActive = dataArray[i];
                $('#diseaseUl li').each(function () {
                    if ($(this).attr('data-num') == dataActive) {
                        $(this).attr('data-active', 1);
                        $(this).attr('data-id', i + 1);
                        if (i < 3) {
                            $(this).find('.selectBtn').html(i + 1);
                            $(this).addClass('foreFront');
                        } else {
                            $(this).find('.selectBtn').html('');
                            $(this).addClass('behind');
                        }
                    }
                });
            }
            //专业切换添加标记
            $('.changeSubSpecialty').removeClass('color-green');
            $('#diseaseUl li').each(function () {
                if ($(this).attr('data-active') == 1) {
                    var selectId = $(this).parent('ul').attr('data-subcatid');
                    $('.changeSubSpecialty').each(function () {
                        if ($(this).attr('data-id') == 'all') {
                            $(this).addClass('color-green');
                        }
                        if ($(this).attr('data-id') == selectId) {
                            $(this).addClass('color-green');
                        }
                    });
                }
            });
        }
        //搜索疾病
        $('#searchDisease').click(function () {
            $('#two').addClass('hide');
            $('#three').removeClass('hide');
            $('input[name="searchName"]').focus();
        });
        //返回
        $('#backDisease').click(function () {
            $('#three').addClass('hide');
            $('#two').removeClass('hide');
            $('#three').find('.icon_clear').trigger('click');
        });
        //搜索
        $('input[name="searchName"]').on('input', function (e) {
            //$('input[name="searchName"]').change(function (e) {
            e.preventDefault();
            var diseaseName = $('input[name="searchName"]').val();
            diseaseName = Trim(diseaseName);
            if (diseaseName == '') {
                $('#three').find('.icon_clear').addClass('hide');
                $('#searchDiseaseList').html('');
                return;
            } else if (diseaseName.match(/[a-zA-Z]/g) != null) {
                $('#three').find('.icon_clear').removeClass('hide');
            } else {
                $('#three').find('.icon_clear').removeClass('hide');
                ajaxSearchDisease(diseaseName);
            }
        });
        $('#three').find('.icon_clear').click(function () {
            $(this).addClass('hide');
            $('input[name="searchName"]').val('');
            $('#searchDiseaseList').html('');
        });

        //疾病搜索列表
        function ajaxSearchDisease(diseaseName) {
            $.ajax({
                url: '<?php echo $urlAjaxSearchSub; ?>/id/' + '<?php echo $urlId; ?>/name/' + diseaseName,
                success: function (data) {
                    var diseaseList = data.results.diseaseList;
                    var innerList = '';
                    if (diseaseList.length > 0) {
                        for (var i = 0; i < diseaseList.length; i++) {
                            innerList += '<li class="selectLi grid" data-num="' + diseaseList[i].diseaseId + '">' +
                                    '<div class="col-0 grid middle">' +
                                    '<div class="selectBtn"></div>' +
                                    '</div>' +
                                    '<div class="col-1">' + diseaseList[i].diseaseName +
                                    '</div>' +
                                    '</li>';
                        }
                    } else {
                        innerList += '<li>未找到该疾病</li>';
                    }
                    $('#searchDiseaseList').html(innerList);
                    $('.selectLi').unbind('click');
                    diseaseSelected();
                    $('#three').find('.selectLi').click(function () {
                        if ($('#one').hasClass('hide')) {
                            $('#three').addClass('hide');
                            $('#two').removeClass('hide');
                        }
                    });
                }
            });
        }


        //添加擅长手术
        var operationData = true;
        $('#addOperation').click(function () {
            if ($(this).hasClass('ban')) {
                return;
            }
            $('#one').addClass('hide');
            $('#four').removeClass('hide');
            //加载术式
            if (operationData) {
                J.showMask();
                $.ajax({
                    url: '<?php echo $urlAjaxSurgery; ?>',
                    success: function (data) {
                        readyOperation(data);
                    }
                });
            }
        });
        //返回页面
        $('#operationPage').click(function () {
            $('#four').addClass('hide');
            $('#three').addClass('hide');
            $('#one').removeClass('hide');
            $('#confirmOperation').trigger('click');
        });

        function readyOperation(data) {
            var operationSurgeryList = '';
            var commonOperation = '';
            var commonBool = '';
            var allOperation = '';
            var subcatList = data.results.subcatList;
            var subCatName = '';
            if (subcatList.length > 0) {
                for (var i = 0; i < subcatList.length; i++) {
                    subCatName = subcatList[i].subCatName;
                    var surgeryList = subcatList[i].surgeryList;
                    if (surgeryList.length > 0) {
                        commonOperation += '<div class="pad10 bg-gray3">常见术式</div><ul class="list">';
                        allOperation += '<div class="pad10 bg-gray3">全部术式</div><ul class="list operationUl" data-subCatiD="' + subcatList[i].subCatId + '">';
                        for (var j = 0; j < surgeryList.length; j++) {
                            if (surgeryList[j].isCommon == 1) {
                                commonBool = true;
                                commonOperation += '<li class="operationLi grid" data-num="' + surgeryList[j].surgeryId + '">' +
                                        '<div class="col-0 grid middle">' +
                                        '<div class="selectBtn"></div>' +
                                        '</div>' +
                                        '<div class="col-1">' + surgeryList[j].surgeryName +
                                        '</div>' +
                                        '</li>';
                            }
                            allOperation += '<li class="operationLi grid" data-num="' + surgeryList[j].surgeryId + '">' +
                                    '<div class="col-0 grid middle">' +
                                    '<div class="selectBtn"></div>' +
                                    '</div>' +
                                    '<div class="col-1">' + surgeryList[j].surgeryName +
                                    '</div>' +
                                    '</li>';
                        }
                        commonOperation += '</ul>';
                        allOperation += '</ul>';
                    }
                    if (commonBool) {
                        operationSurgeryList += commonOperation;
                    }
                    operationSurgeryList += allOperation;
                }
            }
            $('#operationSubSpecialty').find('span').text(subCatName);
            $('#operationListSelected').html(operationSurgeryList);
            operationSelected(1);
            operationData = false;
            J.hideMask();
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
        }

        //选择手术、当前id
        var operationId = 0;
        var operationArray = new Array();
        var operationNameArray = new Array();
        function operationSelected(type) {
            $('.operationLi').click(function () {
                //隐藏专业列表
                $('.operationMajorList').addClass('hide');

                var operationObject = new Object();
                operationObject.id = '';
                operationObject.method = new Array();
                operationObject.num = new Array();

                //添加数组
                if ($(this).attr('data-active') != 1) {
                    //判断是否超出10
                    if (operationArray.length >= 10) {
                        J.customConfirm('',
                                '<div class="mt10 mb10">最多只能选择10中擅长手术</div>',
                                '<a id="closeLogout" class="w100">我知道了</a>',
                                '',
                                function () {
                                },
                                function () {
                                });
                        $('#closeLogout').click(function () {
                            J.closePopup();
                        });
                        return;
                    }
                    //添加术式
                    $(this).attr('data-active', 1);
                    operationObject.id = $(this).attr('data-num');
                    var num = operationArray.push(operationObject);
                    operationNameArray.push($(this).find('.col-1').html());
                    $(this).attr('data-num', $(this).attr('data-num'));
                    $(this).attr('data-id', num);
                    operationId = num;
                    if (num < 4) {
                        $(this).addClass('foreFront');
                        $(this).find('.selectBtn').html(num);
                    } else {
                        $(this).addClass('behind');
                        $(this).find('.selectBtn').html('');
                    }
                } else {
                    operationId = $(this).attr('data-id');
                }

                //展示弹窗
                var operationOrder = $(this).attr('data-id');
                var operationOrderText = '';
                var operationNameText = $(this).find('.col-1').html();
                if (operationOrder == 1) {
                    operationOrderText = '第一';
                } else if (operationOrder == 2) {
                    operationOrderText = '第二';
                } else if (operationOrder == 3) {
                    operationOrderText = '第三';
                }
                var innerHtml = '<div id="operationPopup" class="text-left">' +
                        '<div class="pad10 bg-green color-white">' + operationOrderText + '擅长术式</div>' +
                        '<div class="pad10 bg-white">' + operationNameText + '</div>' +
                        '<div class="pad10 bg-green color-white">该术式擅长的方法</div>' +
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
                        '开放' +
                        '</li>' +
                        '<li class="grid methodLi ' + methodActiveTwo + '" data-id="2">' +
                        '腔镜' +
                        '</li>' +
                        '<li class="grid methodLi ' + methodActiveThree + '" data-id="3">' +
                        '机器人' +
                        '</li>' +
                        '</ul>' +
                        '<div class="pt5 pb5 pl20 pr20 bg-white">' +
                        '<div id="selectOver" class="pad10 text-center">' +
                        '选择完毕' +
                        '</div>' +
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
                                    '<div class="pad10 bg-green color-white">完成手术例数</div>' +
                                    '<ul class="list">' +
                                    '<li class="grid numLi" data-num="0,100">' +
                                    '0-100' +
                                    '</li>' +
                                    '<li class="grid numLi" data-num="100,500">' +
                                    '100-500' +
                                    '</li>' +
                                    '<li class="grid numLi" data-num="500,1000">' +
                                    '500-1000' +
                                    '</li>' +
                                    '<li class="grid numLi" data-num="1000,2000">' +
                                    '1000-2000' +
                                    '</li>' +
                                    '<li class="grid numLi" data-num="2000,∞">' +
                                    '>2000' +
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
                                var innerHtml = '<div id="operationPopup" class="text-left">' +
                                        '<div class="pad10 bg-green color-white">' + operationOrderText + '擅长术式</div>' +
                                        '<div class="pad10 bg-white">' + operationNameText + '</div>' +
                                        '<div class="pad10 bg-green color-white">该术式擅长的方法</div>' +
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
                                        '开放' +
                                        '</li>' +
                                        '<li class="grid methodLi ' + methodTwo + '" data-id="2">' +
                                        '腔镜' +
                                        '</li>' +
                                        '<li class="grid methodLi ' + methodThree + '" data-id="3">' +
                                        '机器人' +
                                        '</li>' +
                                        '</ul>' +
                                        '<div class="pt5 pb5 pl20 pr20 bg-white">' +
                                        '<div id="selectOver" class="pad10 text-center">' +
                                        '选择完毕' +
                                        '</div>' +
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
                        selectOver(type);
                    });
                }
            });
        }

        function selectOver(pageType) {
            if (operationArray[operationId - 1].method.length == 0) {
                operationArray.splice(operationId - 1, 1);
                operationNameArray.splice(operationId - 1, 1);
            }
            J.hideMask();
            //根据选择情况，重新绘制列表框
            $('#four li').each(function () {
                $(this).removeClass('foreFront');
                $(this).removeClass('behind');
                $(this).removeAttr('data-active');
                $(this).find('.selectBtn').html('');
            });
            $('#five li').each(function () {
                $(this).removeClass('foreFront');
                $(this).removeClass('behind');
                $(this).removeAttr('data-active');
                $(this).find('.selectBtn').html('');
            });
            for (var i = 0; i < operationArray.length; i++) {
                var dataActive = operationArray[i].id;
                $('#four li').each(function () {
                    if ($(this).attr('data-num') == dataActive) {
                        $(this).attr('data-active', 1);
                        $(this).attr('data-id', i + 1);
                        if (i < 3) {
                            $(this).find('.selectBtn').html(i + 1);
                            $(this).addClass('foreFront');
                        } else {
                            $(this).find('.selectBtn').html('');
                            $(this).addClass('behind');
                        }
                    }
                });
                $('#five li').each(function () {
                    if ($(this).attr('data-num') == dataActive) {
                        $(this).attr('data-active', 1);
                        $(this).attr('data-id', i + 1);
                        if (i < 3) {
                            $(this).find('.selectBtn').html(i + 1);
                            $(this).addClass('foreFront');
                        } else {
                            $(this).find('.selectBtn').html('');
                            $(this).addClass('behind');
                        }
                    }
                });
            }

            //专业切换添加标记
            $('.changeOperationSub').removeClass('color-green');
            $('#operationListSelected li').each(function () {
                if ($(this).attr('data-active') == 1) {
                    var selectId = $(this).parent('ul').attr('data-subcatid');
                    $('.changeOperationSub').each(function () {
                        if ($(this).attr('data-id') == 'all') {
                            $(this).addClass('color-green');
                        }
                        if ($(this).attr('data-id') == selectId) {
                            $(this).addClass('color-green');
                        }
                    });
                }
            });

            if (pageType == 2) {
                $('#five').addClass('hide');
                $('#three').removeClass('hide');
            }
            if (operationNameArray.length > 0) {
                var innerSpan = '';
                operationNameArray.reverse();
                for (var i = 0; i < operationNameArray.length; i++) {
                    var dataNum = operationNameArray.length - i;
                    innerSpan += '<span class="selectedIcon" data-num="' + dataNum + '">' + operationNameArray[i] + '</span>';
                }
                $('#operationList').html(innerSpan);
                $('#confirmOperation').html('确定(' + operationNameArray.length + ')');
                //删除选中
                $('.selectedIcon').click(function () {
                    var dataNum = $(this).attr('data-num');
                    //清空method
                    var method = operationArray[dataNum - 1].method;
                    if (method.length > 0) {
                        for (var i = 0; i < method.length; i++) {
                            operationArray[dataNum - 1].method.splice(0);
                        }
                    }
                    operationId = dataNum;
                    selectOver(pageType);
                });
                $('#operation_footer').removeClass('hide');
                operationNameArray.reverse();
            } else {
                $('#operationList').html('');
                $('#confirmOperation').html('');
                $('#operation_footer').addClass('hide');
            }
        }

        //完成疾病选择
        $('#confirmOperation').click(function () {
            readySelectedOperation();
            $('#four').addClass('hide');
            $('#three').addClass('hide');
            $('#one').removeClass('hide');
        });

        function readySelectedOperation() {
            var operationShow = '';
            if (operationNameArray.length > 0) {
                for (var i = 0; i < operationNameArray.length; i++) {
                    operationShow += '<div class="deleteOperation" data-num="' + i + '">' + (i + 1) + '.' + operationNameArray[i] + '</div>';
                }
            }
            $('#operationShow').html(operationShow);
            //点击取消选中
            $('.deleteOperation').click(function () {
                var dataNum = Number($(this).attr('data-num'));
                //清空method
                var method = operationArray[dataNum].method;
                if (method.length > 0) {
                    for (var i = 0; i < method.length; i++) {
                        operationArray[dataNum].method.splice(0);
                    }
                }
                operationId = dataNum + 1;
                selectOver(1);
                readySelectedOperation();
            });

            //当手术达到上限，添加按钮不可点击
            if (operationNameArray.length >= 10) {
                $('#addOperation').addClass('ban');
            } else {
                $('#addOperation').removeClass('ban');
            }
        }

        //搜索疾病
        $('#searchOperation').click(function () {
            $('#three').addClass('hide');
            $('#operation_footer').addClass('hide');
            $('#five').removeClass('hide');
            $('input[name="operationName"]').focus();
        });
        //返回
        $('#backOperation').click(function () {
            $('#five').addClass('hide');
            $('#four').removeClass('hide');
            $('#five').find('.icon_clear').trigger('click');
        });

        //搜索
        //document.addEventListener('input', function (e) {
        $('input[name="operationName"]').on('input', function (e) {
            //$('input[name="operationName"]').change(function (e) {
            e.preventDefault();
            var operationName = $('input[name="operationName"]').val();
            operationName = Trim(operationName);
            if (operationName == '') {
                $('#five').find('.icon_clear').addClass('hide');
                $('#searchOperationList').html('');
                return;
            } else if (operationName.match(/[a-zA-Z]/g) != null) {
                $('#five').find('.icon_clear').removeClass('hide');
            } else {
                $('#five').find('.icon_clear').removeClass('hide');
                ajaxSearchOperation(operationName);
            }
        });
        $('#five').find('.icon_clear').click(function () {
            $(this).addClass('hide');
            $('input[name="operationName"]').val('');
            $('#searchOperationList').html('');
        });

        //疾病搜索列表
        function ajaxSearchOperation(operationName) {
            $.ajax({
                url: '<?php echo $urlAjaxSearchSurgery; ?>/id/' + '<?php echo $urlId; ?>/name/' + operationName,
                success: function (data) {
                    var surgeryList = data.results.surgeryList;
                    var innerList = '';
                    if (surgeryList.length > 0) {
                        for (var i = 0; i < surgeryList.length; i++) {
                            innerList += '<li class="operationLi grid" data-num="' + surgeryList[i].surgeryId + '">' +
                                    '<div class="col-0 grid middle">' +
                                    '<div class="selectBtn"></div>' +
                                    '</div>' +
                                    '<div class="col-1">' + surgeryList[i].surgeryName +
                                    '</div>' +
                                    '</li>';
                        }
                    } else {
                        innerList += '<li>未找到该疾病</li>';
                    }
                    $('#searchOperationList').html(innerList);
                    operationSelected(2);
                }
            });
        }

        //填写完成
        $('#complete').click(function () {
            if ((dataArray.length == 0) || (operationArray.length == 0)) {
                $('#jingle_toast').show();
                setTimeout(function () {
                    $('#jingle_toast').hide();
                }, 1500);
                return;
            }
            J.showMask();
            $.ajax({
                type: 'post',
                url: '<?php echo $urlAjaxMajor; ?>',
                data: {'MajorForm[id]': '<?php echo $urlId; ?>', 'MajorForm[diseaseList]': dataArray, 'MajorForm[surgeryList]': structure_data(operationArray)},
                success: function (data) {
                    if (data.status = 'ok') {
                        location.href = '<?php echo $urlSuccess; ?>';
                    } else {
                        J.hideMask();
                    }
                },
                error: function () {
                    J.hideMask();
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
        function Trim(str) {
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
    });
</script>