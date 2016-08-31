<?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery.validate.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/patientBooking.js?ts=' . time(), CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/jquery.formvalidate.min.1.0.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/md2/patientBooking.min.1.1.js', CClientScript::POS_END);
?>
<?php
/*
 * $model PatientBookingForm.
 */
$this->setPageID('pCreateBooking');
$this->setPageTitle('就诊信息');
$urlSubmit = $this->createUrl('patientbooking/ajaxCreate');
$urlProfile = $this->createUrl('doctor/profile', array('addBackBtn' => 1));
$urlReturn = $this->createUrl('order/orderView');
$urlRealName = $this->createUrl('doctor/profile');
$urlDoctorUploadCert = $this->createUrl('doctor/uploadCert');
$pid = Yii::app()->request->getQuery('pid', '');
$urlViewContractDoctors = $this->createUrl('doctor/viewContractDoctors', array('source' => 1, 'pid' => $pid));
$expectHospital = Yii::app()->request->getQuery('expectHospital', '');
$expectDept = Yii::app()->request->getQuery('expectDept', '');
$expectDoctor = Yii::app()->request->getQuery('expectDoctor', '');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$real = $userDoctorProfile;
$userDoctorCerts = $doctorCerts;
?>
<style>
    .selectExpect{
        border: 1px solid #e7e7e7;
        padding: 3px;
        margin: 5px;
        border-radius: 5px;
        color: #999999;
    }
</style>
<article id="patientBookingCreate_article" class="active" data-scroll="true">
    <div class="pad10">
        <div class="form-wrapper">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'booking-form',
                'action' => $urlSubmit,
                'htmlOptions' => array('data-url-return' => $urlReturn),
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
            <?php echo $form->hiddenField($model, 'patient_id', array('name' => 'booking[patient_id]')); ?>
            <?php echo $form->hiddenField($model, 'user_agent', array('name' => 'booking[user_agent]')); ?>
            <?php echo $form->hiddenField($model, 'expected_doctor', array('name' => 'booking[expected_doctor]', 'value' => $expectDoctor)); ?>
            <?php echo $form->hiddenField($model, 'expected_dept', array('name' => 'booking[expected_dept]', 'value' => $expectDept)); ?>
            <?php echo $form->hiddenField($model, 'expected_hospital', array('name' => 'booking[expected_hospital]', 'value' => $expectHospital)); ?>
            <div class="pl10 pr10 pb10 bg-white br5">
                <div id="travel_type" class="triangleGreen">
                    <div class="font-s16 pt10 pb5 bb-gray3 color-green">
                        请您选择就诊方式
                    </div>
                    <div class="grid pt20 pb20">
                        <?php
                        $travelTrype = $model->travel_type;
                        echo '' . $travelTrype;
                        $optionsTravelType = $model->loadOptionsTravelType();
                        $i = 1;
                        foreach ($optionsTravelType as $key => $caption) {
                            if ($travelTrype == $key) {
                                echo '<div data-travel="' . $key . '" class="col-1 w50 intention">' . $caption . '</div>';
                            } else {
                                if ($i == 1) {
                                    echo '<div data-travel="' . $key . '" class="col-1 w50 intention mr10">' . $caption . '</div>';
                                } else {
                                    echo '<div data-travel="' . $key . '" class="col-1 w50 intention">' . $caption . '</div>';
                                }
                            }
                            $i++;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php echo $form->hiddenField($model, 'travel_type', array('name' => 'booking[travel_type]')); ?>
            <div class="mt10 pl10 pr10 pb10 bg-white br5">
                <div id="expectedInfo">
                    <div class="font-s16 pt10 pb5 bb-gray3 color-green">
                        请填写您想要预约的主刀医生
                    </div>
                    <div class="grid">
                        <div class="col-0 pt8">所在医院：</div>
                        <div class="col-1">
                            <div class="selectExpect">
                                <?php
                                if ($expectHospital == '') {
                                    echo '请选择医生所在医院';
                                } else {
                                    echo $expectHospital;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="grid">
                        <div class="col-0 pt8">所在科室：</div>
                        <div class="col-1">
                            <div class="selectExpect">
                                <?php
                                if ($expectDept == '') {
                                    echo '请选择医生所在科室';
                                } else {
                                    echo $expectDept;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="grid">
                        <div class="col-0 pt8">医生姓名：</div>
                        <div class="col-1">
                            <div class="selectExpect">
                                <?php
                                if ($expectDoctor == '') {
                                    echo '请输入医生姓名';
                                } else {
                                    echo $expectDoctor;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt10 pl10 pr10 pb10 bg-white br5">
                <div class="font-s16 grid pt10 pb5 bb-gray3 color-green">
                    请填写诊疗目的
                </div>
                <div>
                    <?php
                    echo $form->textArea($model, 'detail', array(
                        'name' => 'booking[detail]',
                        'maxlength' => 1000,
                        'rows' => '6',
                        'placeholder' => '如果有其他说明，请在此补充',
                        'class' => 'noPadding'
                    ));
                    ?>                   
                </div>
                <?php echo $form->error($model, 'detail'); ?>                
            </div>
            <?php $this->endWidget(); ?>
            <div class="pt20 pb20">
                <button id="btnSubmit" class="btn btn-yes btn-block" disabled="disabled">提交</button>
            </div>
        </div>
    </div>
</article>
<script>
    Zepto(function ($) {

        document.addEventListener('input', function (e) {
            checkInput();
        });

        $('.selectExpect').click(function () {
            location.href = '<?php echo $urlViewContractDoctors; ?>';
        });
        document.addEventListener('input', function (e) {
            e.preventDefault();
            $('#expectedError.error').remove();
        });
        //是否实名认证
        $realName = '<?php echo $real; ?>';
        $urlRealName = '<?php echo $urlRealName; ?>';
        $userDoctorCerts = '<?php echo $userDoctorCerts; ?>'
        $userDoctorUploadCert = '<?php echo $urlDoctorUploadCert; ?>';
        $('.intention').tap(function (e) {
            e.preventDefault();
            $('.noTravelType').remove();
            var travelType = $(this).attr('data-travel');
            $('input[name = "booking[travel_type]"]').attr('value', travelType);
            $('.intention').each(function () {
                $(this).removeClass('active');
            });
            $(this).addClass('active');
            checkInput();
        });

        function checkInput() {
            var bool = true;
            $('input').each(function () {
                if ($(this).val() == '') {
                    bool = false;
                    return false;
                }
            });
            console.log(bool);
            $('textarea').each(function () {
                if ($(this).val() == '') {
                    bool = false;
                    return false;
                }
            });
            console.log(bool);
            if (bool) {
                $('#btnSubmit').removeAttr('disabled');
            } else {
                $('#btnSubmit').attr('disabled', 'disabled');
            }
        }
    });
</script>