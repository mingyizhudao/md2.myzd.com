<?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery.form.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery.validate.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/profile.js?ts=' . time(), CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/jquery.formvalidate.min.1.0.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/md2/profile.min.1.1.js', CClientScript::POS_END);
?>
<?php
/*
 * $model UserDoctorProfileForm.
 */
$this->setPageID('pUserDoctorProfile');
$this->setPageTitle('完善个人信息');
$urlLogin = $this->createUrl('doctor/login');
$urlTermsPage = $this->createUrl('home/page', array('view' => 'terms'));
$urlLoadCity = $this->createUrl('/region/loadCities', array('state' => ''));
$urlSubmitProfile = $this->createUrl("doctor/ajaxProfile");
$urlReturn = $returnUrl;
$this->show_footer = false;
?>
<?php
if ($register == 1) {
    ?>
    <header class="bg-green">
        <nav class="left">
            <a id="no-back" href="javascript:;">
                <div class="pl5">
                    <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                </div>
            </a>
        </nav>
        <h1 class="title">完善个人信息</h1>
    </header>
    <?php
}
?>
<article id="doctorProfile_article" class="active bg-gray" data-scroll="true" data-register="<?php echo $register; ?>">
    <div class="pb20">
        <?php
        if ($register == 1) {
            ?>
            <div>
                <img src="http://static.mingyizhudao.com/147667522197052" class="w100">
            </div>
            <div class="grid text-center pb5 bg-fff">
                <div class="col-1 w25 c-red">
                    注册成功
                </div>
                <div class="col-1 w25 c-red">
                    基本信息
                </div>
                <div class="col-1 w25">
                    实名认证
                </div>
                <div class="col-1 w25">
                    医师认证
                </div>
            </div>
            <p class="text-center font-s12 pt20">为了更好的使用名医主刀账户，请您完善以下信息:</p>
            <?php
        }
        ?>
        <div class="form-wrapper">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'doctor-form',
                'htmlOptions' => array('data-url-action' => $urlSubmitProfile, 'data-url-return' => $urlReturn),
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
            <div class="pad10">
                <div class="input-tab">
                    <div class="grid bg-fff pad5 br5">
                        <div class="col-0 w80p br-gray pt8">
                            姓名
                        </div>
                        <div class="col-1">
                            <?php echo $form->textField($model, 'name', array('name' => 'doctor[name]', 'placeholder' => '请输入真实姓名', 'maxlength' => 45, 'class' => 'noPaddingInput')); ?>
                        </div>
                    </div>
                </div>
                <div class="input-tab">
                    <div class="grid bg-fff mt10 pad5 br5">
                        <div class="col-0 w80p br-gray pt8">
                            省份
                        </div>
                        <div class="col-1">
                            <?php
                            echo $form->dropDownList($model, 'state_id', $model->loadOptionsState(), array(
                                'name' => 'doctor[state_id]',
                                'prompt' => '选择省份',
                                'class' => 'noPaddingInput',
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="input-tab">
                    <div class="grid bg-fff mt10 pad5 br5">
                        <div class="col-0 w80p br-gray pt8">
                            城市
                        </div>
                        <div class="col-1">
                            <?php
                            echo $form->dropDownList($model, 'city_id', $model->loadOptionsCity(), array(
                                'name' => 'doctor[city_id]',
                                'prompt' => '选择城市',
                                'class' => 'noPaddingInput',
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="input-tab">
                    <div class="grid bg-fff mt10 pad5 br5">
                        <div class="col-0 w80p br-gray pt8">
                            医院
                        </div>
                        <div class="col-1">                                     
                            <?php echo $form->textField($model, 'hospital_name', array('name' => 'doctor[hospital_name]', 'placeholder' => '您所在的医院全称', 'maxlength' => 45, 'class' => 'noPaddingInput')); ?>
                        </div>
                    </div>
                </div>
                <div class="input-tab">
                    <div class="grid bg-fff mt10 pad5 br5">
                        <div class="col-0 w80p br-gray pt8">
                            科室
                        </div>
                        <div class="col-1">
                            <?php echo $form->textField($model, 'hp_dept_name', array('name' => 'doctor[hp_dept_name]', 'placeholder' => '您所在的科室', 'maxlength' => 45, 'class' => 'noPaddingInput')); ?>
                        </div>
                    </div>
                </div>
                <div class="input-tab">
                    <div class="grid bg-fff mt10 pad5 br5">
                        <div class="col-0 w80p br-gray pt8">
                            医学职称
                        </div>
                        <div class="col-1">
                            <?php
                            echo $form->dropDownList($model, 'clinical_title', $model->loadOptionsClinicalTitle(), array(
                                'name' => 'doctor[clinical_title]',
                                'prompt' => '临床职称',
                                'class' => 'noPaddingInput',
                            ));
                            ?>
                        </div> 
                    </div>
                </div>
                <div class="input-tab">
                    <div class="grid bg-fff mt10 pad5 br5">
                        <div class="col-0 w80p br-gray pt8">
                            学术职称
                        </div>
                        <div class="col-1">
                            <?php
                            echo $form->dropDownList($model, 'academic_title', $model->loadOptionsAcademicTitle(), array(
                                'name' => 'doctor[academic_title]',
                                'prompt' => '学术职称',
                                'class' => 'noPaddingInput',
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="input-tab">
                    <div class="bg-fff mt10 pad5 br5">
                        <?php //$form->hiddenField($model, 'terms', array('name' => 'doctor[terms]'));    ?>                    
                        <?php echo $form->checkBox($model, 'terms', array('name' => 'doctor[terms]')); ?>
                        <label for="doctor_terms" class="ui-btn ui-corner-all ui-btn-inherit ui-btn-icon-left ui-checkbox-on">同意名医主刀</label>
                        <a id="termslink" class="ui-link">《在线服务条款》</a>
                        <?php echo $form->error($model, 'terms'); ?>  
                    </div>
                </div>
                <div class="pt20">
                    <a id="btnSubmit" class="btn btn-yes btn-block">提交</a>
                </div>
            </div>
            <?php
            $this->endWidget();
            ?>
        </div>
    </div>
    <div id="termsShow" class="terms">
        <div>
            <div>
                <?php $this->renderPartial("//home/terms"); ?>
            </div>
            <div class="">
                <a href="javascript:;" class="hideTerms btn btn-yes btn-block">确 认</a>
            </div>
        </div>
    </div>
</article>
<script type="text/javascript">
    $(document).ready(function () {
        //注册时无返回
        $('#no-back').click(function () {
            J.customConfirm('',
                    '<div class="mt10 mb10">未完成基本信息填写，将不能进行手术预约。请您先填写吧！</div>',
                    '',
                    '<a id="close-popup" class="w100">确定</a>',
                    function () {
                    },
                    function () {
                    });
            $('#close-popup').click(function () {
                J.closePopup();
            });
        });
        $("#termslink").click(function () {
            $("#termsShow").show();
            $('#a1').scrollTop(0);
        });
        $(".hideTerms").click(function () {
            $("#termsShow").hide();
        });
        $("select#doctor_state_id").change(function () {
            $("select#doctor_city_id").attr("disabled", true);
            var stateId = $(this).val();
            var actionUrl = "<?php echo $urlLoadCity; ?>";// + stateId + "&prompt=选择城市";
            $.ajax({
                type: 'get',
                url: actionUrl,
                data: {'state': this.value, 'prompt': '选择城市'},
                cache: false,
                // dataType: "html",
                'success': function (data) {
                    $("select#doctor_city_id").html(data);
                    // jquery mobile fix.
                    captionText = $("select#doctor_city_id>option:first-child").text();
                    $("#doctor_city_id-button>span:first-child").text(captionText);
                },
                'error': function (data) {
                },
                complete: function () {
                    $("select#doctor_city_id").attr("disabled", false);
                    $("select#doctor_city_id").removeAttr("disabled");
                }
            });
            return false;
        });
    });
</script>