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
$urlchoosePatientList = $this->createUrl('patient/chooseList',array('addBackBtn'=>1));


$this->show_footer = false;
// var_dump($noBookingList);die;
?>

<style>
   .bg-g1{background-color: #00A378;} 
   #addPatient_article  .doctorInfo{background:url('http://static.mingyizhudao.com/147062699107071') no-repeat;background-size: 100% 100%;}
   #addPatient_article .imgDiv{width:74px;height:74px;border-radius: 50%;overflow: hidden;} 
   .bbh{border-bottom: 1px solid #f1f1f1;}
   .bgimg1{background: url('http://static.mingyizhudao.com/147383811199511') no-repeat;background-size: 19px 19px;}
   .bgimg2{background: url('http://static.mingyizhudao.com/147383865276045') no-repeat;background-size: 19px 19px;}
   .bgimg3{background: url('http://static.mingyizhudao.com/147383865671528') no-repeat;background-size: 19px 17px;}
   #addPatient_article .intention{border:1px solid #e7e7e7;padding:5px;text-align: center;border-radius: 5px;}
   #addPatient_article .intention.active{border:1px solid #19aea5;padding:5px;text-align: center;border-radius: 5px;background:url('http://static.mingyizhudao.com/146967365510273') no-repeat;background-size: 15px;background-position-x:100%;background-position-y:100%;  }
   footer{border-top: 1px solid #fff;}
</style>

<header id="addPatient_header" class="bg-g1">
    <nav class="left">
        <a href="" data-target="back">
            <div class="pl5">
                <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
            </div>
        </a>
    </nav>
    <h1 class="title">填写预约单</h1>
    
    <nav id="btnSubmit" class="right">
        确定
    </nav>  
</header>
<footer class='bg-white '>
        <button id='add_Patient' class="btn btn-block bg-g1">提交</button>
    </footer>
<article id="addPatient_article" class="active" data-scroll="true">
    <div class="grid pt20 pb20 doctorInfo">
            <div class="col-0">
                <div class="imgDiv ml20">
                    <!-- <img class="imgDoc" src="<?php echo $doctor->imageUrl; ?>"> -->

                    <img src="http://static.mingyizhudao.com/147383264259665" class="imgDoc">
                </div>
            </div>
            <div class="col-1 ml20  color-white">
                <div>
                   <?php echo '<span class="font-s16">'.$doctor->name.'</span>'.'<span class="ml10">'.$doctor->aTitle.'</span>';?>
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
              <span class="bgimg1 pl20">&nbsp选择就诊意向:</span> 
           </div>
           <div class="grid pad20">
              <div class="col-1 text-center intention  w50"data-travel="1">邀请专家过来</div>
              <div class="col-0"style="width:10px;"></div>
              <div class="col-1 text-center intention w50"data-travel="2">希望转诊治疗</div>
           </div>
       </div>
       <div class="mt10 bg-white">
           <div class="pad10 bbh  ">
               <span class="bgimg2 pl20">&nbsp请选择您的患者:</span>
           </div>
           <div class="text-center pad20 ">
               <span class="text-center pr50 pl50 pt10 pb10 " id="choosep" style="border:1px solid #f1f1f1;border-radius:5px ;">+选择患者</span>
           </div>
       </div>
       <div class="mt10 bg-white">
          <div class="pad10 bbh">
              <span class="bgimg3 pl20">&nbsp诊疗意见:</span>
          </div> 
          <div class="pad10">
              <textarea name="booking[detail]" id="booking_detail"  placeholder="如您有其他诊疗意见，请填写&#10;如没有请填写“无”"cols="10"rows="2"></textarea>
          </div>
       </div>

    <div class="pl10 pr10">
         

         <div class="mt10 pt15 pb30 pl10 doctorInf">
            <div>
                您已选择:<?php echo $doctor->name; ?> <?php echo $doctor->mTitle; ?> <?php echo $doctor->aTitle; ?>
            </div>
            <div class="pt10">
                <?php echo $doctor->hospitalName; ?>-<?php echo $doctor->hpDeptName; ?>
            </div>
        </div>
        <div class="mt20 grid">
            <div class="col-1 pl10">
                请您选择患者:
            </div>
            <div class="col-0">
                还没患者?
                <a href="<?php echo $urlPatientCreate; ?>?returnUrl=<?php echo $returnUrl; ?>" class="color-green mr10">立即创建</a>
            </div>
        </div>
        <?php
        if (count($noBookingList) > 0) {
            for ($i = 0; $i < count($noBookingList); $i++) {
                $patientInfo = $noBookingList[$i];
                if ($patientInfo->age > 5) {

                    
                    $age = $patientInfo->age . '岁';
                } else {
                    $age = $patientInfo->age . '岁' . $patientInfo->ageMonth . '月';
                }
                ?>
                <div class="grid patientList mt10">
                    <div class="col-0 w50p grid middle">
                        <img src="http://static.mingyizhudao.com/14696845618638" class="selectPatient w20p" data-id="<?php echo $patientInfo->id; ?>">
                    </div>
                    <div class="col-1">
                        <div class="mt10"><?php echo $patientInfo->name; ?></div>
                        <div class="grid">
                            <div class="col-1"><?php echo $patientInfo->gender; ?> <?php echo $age; ?> <?php echo $patientInfo->cityName; ?></div>
                            <div class="col-0">
                                <a href="<?php echo $this->createUrl('patient/view', array('id' => $patientInfo->id, 'addBooking' => '0', 'addBackBtn' => 1)); ?>" class="color-green mr10 a-underline">
                                    查看详情
                                </a>
                            </div>
                        </div>
                        <div class="mb10"><?php echo $patientInfo->diseaseName; ?></div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        <div class="pt20"></div> 
    </div>
</article>
<script>
    $(document).ready(function () {
      $('.intention').click(function(){
       // alert('a');
       $(this).addClass('active');
       $(this).css('color','#19aea6');
       $(this).siblings().not('.col-0').attr('class','col-1 text-center intention w50');
       $(this).siblings().not('.col-0').css('color','#333');

      })
          
        $('.selectPatient').click(function () {
            $('.selectPatient').each(function () {
                $(this).removeClass('select');
                $(this).attr('src', 'http://static.mingyizhudao.com/14696845618638');
            });
            $(this).addClass('select');
            $(this).attr('src', 'http://static.mingyizhudao.com/146968462384937');
        });

        $('#choosep').click(function(){
           location.href="<?php echo $urlchoosePatientList;?>";
        })
        $('#btnSubmit').click(function () {
           var patientId = '';
            $(".selectPatient").each(function () {
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
        $('#add_Patient').click(function(){
          // location.href="https://www.baidu.com";
        })
    });
</script>