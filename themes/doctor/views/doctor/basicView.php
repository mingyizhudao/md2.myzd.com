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
        Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.1.css');
        Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/custom.min.1.0.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery.form.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery.validate.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/basicView.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/doctorCenter.js', CClientScript::POS_END);
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
            width: 24px;
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
    </style>
    <body>
        <div id = "section_container">
            <section id = "main_section" class = "active" data-init = "true">
                <header class = "bg-green">
                    <h1 class = "title">医生</h1>
                </header>
                <footer id="btnSubmit" class="bg-green color-white">
                    <div class="text-center">
                        继续职业信息
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
                        <input type="hidden" name="BasicInfoForm[gender]" value="<?php echo $model->gender; ?>"/>
                        <input type="hidden" name="BasicInfoForm[remote_domain]" value="<?php echo $model->remote_domain; ?>"/>
                        <input type="hidden" name="BasicInfoForm[remote_file_key]" value="<?php echo $model->remote_file_key; ?>"/>
                        <div>
                            个人信息
                        </div>
                        <div id="uploadHeadImg" class="">
                            <div class="body">
                                <div class="text-center">
                                    <div id="container">
                                        <a class="btn btn-default btn-lg" id="pickfiles" href="#">
                                            <span>
                                                <?php
                                                if (isset($model->id)) {
                                                    echo '<img src="' . $model->remote_domain . $model->remote_file_key . '" class="w100p h100 br50"/>';
                                                } else {
                                                    echo '<img src="http://7xp8ky.com1.z0.glb.clouddn.com/147012383129870.jpg" class="w100p h100 br50"/>';
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
                        <div id="showHeadImg" class="text-center mb20 hide">
                            <img src="http://7xp8ky.com1.z0.glb.clouddn.com/147012383129870.jpg" class="w100p h100 br50">
                        </div>
                        <div class="inputBorder">
                            <div class="grid">
                                <div class="col-0 w80p pt7">
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
                                <div class="col-0 w80p pt7">
                                    出生日期
                                </div>
                                <div class="col-1">
                                    <input type="date" class="noPaddingInput" placeholder="选择您的出生年月日" value="<?php echo $model->birthday; ?>" name="BasicInfoForm[birthday]" id="">
                                </div>
                            </div>
                        </div>
                        <div class="inputBorder">
                            <div class="grid">
                                <div class="col-0 w80p pt7">
                                    手机
                                </div>
                                <div class="col-1">
                                    <?php echo $form->telField($model, 'mobile', array('placeholder' => '输入您的手机号码', 'class' => 'noPaddingInput')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="inputBorder">
                            <div class="grid">
                                <div class="col-0 w80p pt7">
                                    电子邮箱
                                </div>
                                <div class="col-1">
                                    <?php echo $form->textField($model, 'email', array('placeholder' => '输入您的电子邮箱（选填）', 'class' => 'noPaddingInput')); ?>
                                </div>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </article>
                <script>
                    $(document).ready(function () {
                        $('.genderIcon').click(function () {
                            $('.genderIcon').each(function () {
                                $(this).removeClass('active');
                            });
                            $(this).addClass('active');
                            $('input[name="BasicInfoForm[gender]"]').val($(this).attr('data-gender'));
                        });
<?php
$time = strtotime($model->birthday);
$data = date('Y/m/d', $time);
?>
                        $('#BasicInfoForm_birthday').attr('value', '<?php echo $data; ?>');
                    });
                </script>
            </section>
        </div>
    </body>
</html>