<?php
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/doctor/jquery.formvalidate.min.1.1.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/addPatient.js?ts=' . time(), CClientScript::POS_END);
?>
<?php
$this->setPageTitle('添加患者');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlBookingDoctor = $this->createAbsoluteUrl('booking/create', array('did' => ''));
$url = $this->createUrl('home/page', array('view' => 'bookingDoctor', 'addBackBtn' => '1'));
$urlPatientCreate = $this->createUrl('patient/create', array('addBackBtn' => 1));
$doctor = $doctorInfo->results->doctor;
$id = Yii::app()->request->getQuery('id', '');
$returnUrl = $this->createUrl('doctor/addPatient', array('id' => $id, 'addBackBtn' => 1));
$urlchoosePatientList = $this->createUrl('patient/chooseList', array('id' => $doctor->id, 'addBackBtn' => 1));
$urlSubmit = $this->createUrl('patientbooking/ajaxCreate');
$urlReturn = $this->createUrl('order/view');
$this->show_footer = false;
?>
<style>
    .bg-g1{
        background-color: #00A378;
    } 
    #addPatient_article .doctorInfo{
        background:url('http://static.mingyizhudao.com/147062699107071') no-repeat;
        background-size: 100% 100%;
    }
    #addPatient_article .imgDiv{
        width:74px;
        height:74px;
        border-radius: 50%;
        overflow: hidden;
    } 
    .bbh{
        border-bottom: 1px solid #f1f1f1;
    }
    .bgimg1{
        background: url('http://static.mingyizhudao.com/147383811199511') no-repeat;
        background-size: 19px 19px;
    }
    .bgimg2{
        background: url('http://static.mingyizhudao.com/147383865276045') no-repeat;
        background-size: 19px 19px;
    }
    .bgimg3{
        background: url('http://static.mingyizhudao.com/147383865671528') no-repeat;
        background-size: 19px 17px;
    }
    #addPatient_article .intention{
        border:1px solid #e7e7e7;
        padding:5px;
        text-align: center;
        border-radius: 5px;
    }
    #addPatient_article .intention.active{
        border:1px solid #19aea5;
        background:url('http://static.mingyizhudao.com/146967365510273') no-repeat;
        background-size: 15px;
        background-position-x:100%;
        background-position-y:100%;
        color: #19aea5;
    }
    footer{
        border-top: 1px solid #fff;
    }
    textarea{
        padding: 0;
        margin-bottom: 0;
        border: none!important;
        -webkit-box-shadow: none!important;
        box-shadow: none!important;
        height: 60px;
    }
    .icon-clear {
        background: url('http://static.mingyizhudao.com/146717942005220') no-repeat;
        background-size: 15px 15px;
        background-position: 10px 5px;
        width: 30px;
        height: 25px;
    }
</style>
<footer class="bg-white">
    <button id="btnSubmit" class="btn btn-block bg-g1">提交</button>
</footer>
<article id="addPatient_article" class="active" data-scroll="true">
    <div>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'patient-form',
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
        <?php echo $form->hiddenField($model, 'patient_id', array('name' => 'booking[patient_id]', 'value' => is_null($patientInfo) ? '' : $patientInfo->results->patientInfo->id)); ?>
        <?php echo $form->hiddenField($model, 'expected_doctor', array('name' => 'booking[expected_doctor]', 'value' => $doctor->name)); ?>
        <?php echo $form->hiddenField($model, 'expected_hospital', array('name' => 'booking[expected_hospital]', 'value' => $doctor->hospitalName)); ?>
        <?php echo $form->hiddenField($model, 'expected_dept', array('name' => 'booking[expected_dept]', 'value' => $doctor->hpDeptName)); ?>
        <?php echo $form->hiddenField($model, 'travel_type', array('name' => 'booking[travel_type]')); ?>
        <div class="grid pt20 pb20 doctorInfo">
            <div class="col-0">
                <div class="imgDiv ml20">
                    <img src="<?php echo $doctor->imageUrl; ?>" class="imgDoc">
                </div>
            </div>
            <div class="col-1 ml20  color-white">
                <div>
                    <?php echo '<span class="font-s16">' . $doctor->name . '</span>' . '<span class="ml10">' . $doctor->aTitle . '</span>'; ?>
                </div>
                <div>
                    <?php
                    if ($doctor->hpDeptName == '') {
                        echo $doctor->mTitle;
                    } else {
                        echo $doctor->hpDeptName . '<span class="ml10">' . $doctor->mTitle . '</span>';
                    }
                    ?>
                </div>
                <div class="grid">
                    <div class="col-0">
                        <?php echo $doctor->hospitalName; ?>
                    </div> 
                    <div class="col-1"></div>
                </div>
            </div>
        </div>
        <div class="bg-white pt10  ">
            <div class="bbh pl10 pb10 ">
                <span class="bgimg1 pl25">选择就诊意向:</span> 
            </div>
            <div class="grid pad20">
                <div class="col-1 intention w50 mr10" data-travel="1">邀请专家过来</div>
                <div class="col-1 intention w50 ml10" data-travel="2">希望转诊治疗</div>
            </div>
        </div>
        <div class="mt10 bg-white">
            <div class="pad10 bbh">
                <span class="bgimg2 pl25">请选择您的患者:</span>
            </div>
            <?php
            if (is_null($patientInfo)) {
                echo '<div class="text-center pad20"><span class="text-center pr50 pl50 pt10 pb10" id="choosep" style="border:1px solid #f1f1f1;border-radius:5px ;">+选择患者</span></div>';
            } else {
                ?>
                <div id="choosep" class="pad10 grid">
                    <div class="col-1">
                        <?php echo $patientInfo->results->patientInfo->name . '-' . $patientInfo->results->patientInfo->diseaseName; ?>
                    </div>
                    <div class="col-0 icon-clear"></div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="mt10 bg-white">
            <div class="pad10 bbh">
                <span class="bgimg3 pl25">诊疗意见:</span>
            </div> 
            <div class="pad10">
                <textarea name="booking[detail]" id="booking_detail"  placeholder="如您有其他诊疗意见，请填写&#10;如没有请填写“无”" maxlength="1000" cols="10" rows="2"></textarea>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</article>
<script>
    $(document).ready(function() {
        //选择就诊意向
        $('.intention').click(function() {
            var travelType = $(this).attr('data-travel');
            $('input[name = "booking[travel_type]"]').attr('value', travelType);
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
        });
        var session_travelType = sessionStorage.getItem('travelType');
        var session_detail = sessionStorage.getItem('detail');
        if (session_travelType != null) {
            $('.intention').each(function() {
                if ($(this).attr('data-travel') == session_travelType) {
                    $(this).trigger('click');
                }
            });
        }
        $('textarea[name="booking[detail]"]').val(session_detail);
        //选择患者
        $('#choosep').click(function() {
            var travelType = $('input[name="booking[travel_type]"]').val();
            var detail = $('textarea[name="booking[detail]"]').val();
            sessionStorage.setItem('travelType', travelType);
            sessionStorage.setItem('detail', detail);
            location.href = "<?php echo $urlchoosePatientList; ?>";
        });
    });
</script>