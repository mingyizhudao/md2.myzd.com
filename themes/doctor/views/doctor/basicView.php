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
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.1.css');
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/doctor/custom.min.1.0.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/doctor/jquery.formvalidate.min.1.1.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/basicView.js?ts=' . time(), CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/doctorCenter.js?ts=' . time(), CClientScript::POS_END);
        $urlQiniuAjaxDrToken = $this->createUrl('doctor/ajaxDrToken');
        $urlUploadfile = $this->createUrl('doctor/ajaxAvAtar');
        $urlAjaxBasic = $this->createUrl('doctor/ajaxBasic');
        $urlReturn = $this->createUrl('doctor/doctorView', array('id' => ''));
        ?>
    </head>
    <style>
        h1{
            padding-bottom: 0px;
        }
        .noPaddingInput{
            margin-bottom: 0;
            border: none!important;
            border-bottom: 1px solid #d4d4d4!important;
            border-radius: 0px!important;
            -webkit-box-shadow: none!important;
            box-shadow: none!important;
            padding: 10px 0px!important;
        }
        .genderIcon{
            width: 20px;
            text-align: center;
        }
        .genderIcon.active{
            background-color: #32c9c0;
            color: #ffffff;
            border-radius: 50%;
        }
        .btn-default {
            border: inherit!important;
            color: #fff;
            background-color: #fff!important;
            box-shadow: none!important;
        }
        .btn{
            padding:0
        }
        #container a{
            width:inherit;
            min-width:inherit;
            line-height: normal;
            display: inline-block!important;
        }
        .btn-file{
            background-color:#06c1ae;
            width:100%;
            display:block;
            margin:5px auto;
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
        #datePicker{
            background-color: #fff;
        }
        input[type="date"]::-webkit-clear-button {
            -webkit-appearance: none;
            display: none;
        }
        input[type="date"]::-webkit-inner-spin-button { 
            -webkit-appearance: none;
            display: none;
        }
        input[type="date"]::-ms-clear {
            display: none;
        }
        ::-webkit-search-cancel-button {
            -webkit-appearance: none;
        }
    </style>
    <body>
        <div id="jingle_loading" style="display: block;" class="loading initLoading"><i class="icon spinner"></i><p>加载中...</p><div id="tag_close_popup" data-target="closePopup" class="icon cancel-circle"></div></div>
        <div id="jingle_loading_mask" style="opacity: 0; display: block;"></div>
        <div id="jingle_toast" class="toast" style="display: none;"><a href="#">请上传头像</a></div>
        <div id = "section_container">
            <section id = "main_section" class = "active" data-init = "true">
                <footer id="btnSubmit" class="bg-green color-white">
                    <div class="text-center">
                        继续填写职业信息
                    </div>
                    <div class="text-center">
                        <img src="http://static.mingyizhudao.com/147029008156318" class="w24p">
                    </div>
                </footer>
                <article class = "active" data-scroll = "true">
                    <div class = "pad10">
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'basicInfo-form',
                            'htmlOptions' => array('data-url-action' => $urlAjaxBasic, 'data-url-return' => $urlReturn, 'data-url-uploadfile' => $urlUploadfile),
                            'enableClientValidation' => false,
                            'clientOptions' => array(
                                'validateOnSubmit' => true,
                                'validateOnType' => true,
                                'validateOnDelay' => 500,
                                'errorCssClass' => 'error',
                            ),
                            'enableAjaxValidation' => false,
                        ));
                        ?>
                        <input type="hidden" id="domain" value="http://7xp8ky.com1.z0.glb.clouddn.com/"/>
                        <input type="hidden" id="uptoken_url" value="<?php echo $urlQiniuAjaxDrToken; ?>"/>
                        <input type="hidden" name="BasicInfoForm[id]" value="<?php echo $model->id; ?>"/>
                        <input type="hidden" name="BasicInfoForm[gender]" value="<?php echo $model->gender == null ? '0' : $model->gender; ?>"/>
                        <input type="hidden" name="BasicInfoForm[remote_domain]" value="<?php echo $model->remote_domain; ?>"/>
                        <input type="hidden" name="BasicInfoForm[remote_file_key]" value="<?php echo $model->remote_file_key; ?>"/>
                        <input type="hidden" name="BasicInfoForm[email]" id="formEmail" value=""/>
                        <div class="nav-crumbs">
                            <span>1</span>
                            个人信息
                            <i></i><i></i><span>2</span>
                            <i></i><i></i><span>3</span>
                        </div>
                        <div id="uploadHeadImg" class="pt10">
                            <div class="body">
                                <div class="text-center">
                                    <div id="container">
                                        <a class="btn btn-default btn-lg" id="pickfiles" href="#">
                                            <span>
                                                <?php
                                                if (isset($model->id)) {
                                                    echo '<img src="' . $model->remote_domain . $model->remote_file_key . '" class="w100p h100 br50"/>';
                                                } else {
                                                    echo '<img src="http://static.mingyizhudao.com/147159541679791" class="w100p h100 br50"/>';
                                                }
                                                ?>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12 mt10 hide">
                                    <table class="table table-striped table-hover text-left" style="display:none">
                                        <tbody id="fsUploadProgress">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="inputBorder">
                            <div class="grid">
                                <div class="col-0 w80p pt10">
                                    姓名
                                </div>
                                <div class="col-1">
                                    <?php echo $form->textField($model, 'name', array('placeholder' => '输入您的真实姓名', 'class' => 'noPaddingInput')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="inputBorder">
                            <div class="grid pb10">
                                <div class="col-0 w80p">
                                    性别
                                </div>
                                <div class="col-1 grid">
                                    <?php
                                    if ($model->gender == 0):
                                        ?>
                                        <div class="col-0 genderIcon active mr30" data-gender="0">
                                            男
                                        </div>
                                        <div class="col-0 genderIcon" data-gender="1">
                                            女
                                        </div>
                                        <?php
                                    endif;
                                    if ($model->gender == 1):
                                        ?>
                                        <div class="col-0 genderIcon mr30" data-gender="0">
                                            男
                                        </div>
                                        <div class="col-0 genderIcon active" data-gender="1">
                                            女
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="inputBorder">
                            <div class="grid">
                                <div class="col-0 w80p pt10">
                                    出生日期
                                </div>
                                <div class="col-1">
                                    <input id="datePicker" type="date" class="noPaddingInput" placeholder="选择您的出生年月日" value="" name="BasicInfoForm[birthday]" id=""  required="required"> 
                                </div>
                            </div>
                        </div>
                        <div class="inputBorder">
                            <div class="grid">
                                <div class="col-0 w80p pt10">
                                    手机
                                </div>
                                <div class="col-1">
                                    <?php echo $form->telField($model, 'mobile', array('placeholder' => '输入您的手机号码', 'class' => 'noPaddingInput')); ?>
                                </div>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                        <div class="inputBorder">
                            <div class="grid">
                                <div class="col-0 w80p pt7">
                                    电子邮箱
                                </div>
                                <div class="col-1">
                                    <?php echo $form->emailField($model, 'email', array('placeholder' => '输入您的电子邮箱（选填）', 'class' => 'noPaddingInput')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <script>
                    $(document).ready(function () {
                        $('#jingle_loading.initLoading').hide();
                        $('#jingle_loading_mask').hide();
                        $('.genderIcon').click(function () {
                            $('.genderIcon').each(function () {
                                $(this).removeClass('active');
                            });
                            $(this).addClass('active');
                            $('input[name="BasicInfoForm[gender]"]').val($(this).attr('data-gender'));
                        });
                        //设置日期选择默认日期
                        $birthday = '<?php echo $model->birthday ?>'
                        if ($birthday) {
                            $('#datePicker').val($birthday);
                        } else {
                            $('#datePicker').val('1965-01-01');
                        }
                    });
                </script>
            </section>
        </div>
    </body>
</html>