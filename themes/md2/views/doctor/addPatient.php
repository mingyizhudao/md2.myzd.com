<?php
$this->setPageTitle('添加患者');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlBookingDoctor = $this->createAbsoluteUrl('booking/create', array('did' => ''));
$url = $this->createUrl('home/page', array('view' => 'bookingDoctor', 'addBackBtn' => '1'));
$urlPatientCreate = $this->createUrl('patient/create', array('addBackBtn' => 1));
$doctor = $doctorInfo->results->doctor;
$noBookingList = $patientList->results->noBookingList;
$id = Yii::app()->request->getQuery('id', '');
$returnUrl = $this->createUrl('doctor/addPatient', array('id' => $id, 'addBackBtn' => 1));
$urlchoosePatientList = $this->createUrl('patient/chooseList', array('id' => $doctor->id, 'addBackBtn' => 1));
$this->show_footer = false;
// var_dump($noBookingList);die;
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
</style>
<footer class='bg-white '>
    <button id='add_Patient' class="btn btn-block bg-g1">提交</button>
</footer>
<article id="addPatient_article" class="active" data-scroll="true">
    <div>
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
            <div class="pad10 bbh  ">
                <span class="bgimg2 pl25">请选择您的患者:</span>
            </div>
            <div class="text-center pad20 ">
                <span class="text-center pr50 pl50 pt10 pb10 " id="choosep" style="border:1px solid #f1f1f1;border-radius:5px ;">+选择患者</span>
            </div>
        </div>
        <div class="mt10 bg-white">
            <div class="pad10 bbh">
                <span class="bgimg3 pl25">诊疗意见:</span>
            </div> 
            <div class="pad10">
                <textarea name="booking[detail]" id="booking_detail"  placeholder="如您有其他诊疗意见，请填写&#10;如没有请填写“无”"cols="10"rows="2"></textarea>
            </div>
        </div>
    </div>
</article>
<script>
    $(document).ready(function() {
        //选择就诊意向
        $('.intention').click(function() {
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
        });
        //选择患者
        $('#choosep').click(function() {
            location.href = "<?php echo $urlchoosePatientList; ?>";
        });


        $('#add_Patient').click(function() {
            // location.href="https://www.baidu.com";
        });

        $('#btnSubmit').click(function() {
            var patientId = '';
            $(".selectPatient").each(function() {
                if ($(this).hasClass('select')) {
                    patientId = $(this).attr('data-id');
                }
            });
            //console.log(patientId);
            if (patientId == '') {
                J.showToast('请先选择患者', '', 1000);
                return;
            }
            location.href = '<?php echo $this->createUrl("doctor/createPatientBooking", array("doctorId" => $doctor->id, "patientId" => "")) ?>/' + patientId + '/addBackBtn/1';
        });
    });
</script>