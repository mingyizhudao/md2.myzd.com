<?php
$doctor = $data->results->doctor;
// var_dump($doctor);die;
$honour = $doctor->honour;
$this->setPageTitle($doctor->name);
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlAddPatient = $this->createAbsoluteUrl('doctor/addPatient', array('id' => ''));
$this->show_footer = false;
?>
<style>
 footer{border-top: 1px solid #fff;}  
</style>
<footer class="bg-white">
    <button id="bookingDoc" class="btn btn-block bg-yellow">预约医师</button>
</footer>
<article id="viewDoctor_article" class="active " data-scroll="true"style="background:#fff;">
    <div >
       <div class="bg-green">
           <div class="grid pt20">
               <div class="col-0">
                   <div class="imgDiv ml20">
                        <img class="imgDoc" src="http://static.mingyizhudao.com/147305557964568">
                    </div>
               </div>
               <div class="col-1 ml20 mb20">
                  <div class=" font-s16">
                    <?php 
                    echo $doctor->name;
                    if($doctor->aTitle=='无'){
                        echo '';
                    }else{
                        echo $doctor->aTitle;
                    }
                    ?>
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
                      <div class="col-0 hosBorder">
                         <?php echo $doctor->hospitalName; ?>
                      </div> 
                      <div class="col-1"></div>
                </div>
                </div>
           </div>
       </div>
       <div class="bg-white">
           <?php
          if (isset($doctor->description) && (trim($doctor->description) != '')) {
          ?>
           <div class="ml10 pt10 pr10 pb10 bb-gray3">
                 <div class="font-s16 color-black color-orange">擅长手术:</div>
                 <div class="color-black6"><?php echo $doctor->description; ?></div>
           </div>
            <?php
            }
           ?>
             <?php if (count($doctor->reasons) != 0) { ?>
                        <div class="ml10 pt10 pr10 pb10  bb-gray3">
                            <div class="font-s16 color-orange">
                                推荐理由:
                            </div>
                            <?php
                            for ($i = 0; $i < count($doctor->reasons); $i++) {
                                ?>
                                <div class=" color-black6">
                                    <?php echo $doctor->reasons[$i]; ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
            <?php }?>
            <?php if (isset($honour) && !is_null($honour)) { ?>
            <div class="ml10 pt10 pr10 pb10  bb-gray3">
                <div class="font-s16 color-orange">
                    获得荣誉:
                </div>
                <?php
                for ($i = 0; $i < count($honour); $i++) {
                    ?>
                <div class=" color-black6">
                    <?php echo $honour[$i]; ?>
                 </div>
                  <?php
                }
                ?>
           </div>
         <?php } ?>
            <?php if (isset($doctor->careerExp) && !is_null($doctor->careerExp)) { ?>
            <div class="ml10 pt10 pr10 pb10 ">
                <div class="font-s16 color-black color-orange">执业经历:</div>
                <div class="color-black6"><?php echo $doctor->careerExp; ?></div>
            </div>
           <?php } ?>
                  

        </div>
        <div class="mb10"></div>
    </div>
</article>
<script>
    $(document).ready(function () {
        $('.cardSelect').click(function () {
            var dataCard = $(this).attr('data-card');
            $('.cardSelect').each(function () {
                if (dataCard == $(this).attr('data-card')) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
            $('.pageCard').each(function () {
                if (dataCard == $(this).attr('data-card')) {
                    $(this).removeClass('hide');
                } else {
                    $(this).addClass('hide');
                }
            });
            $('#viewDoctor_article').scrollTop(0);
        });
        $('#bookingDoc').click(function () {
            location.href = '<?php echo $urlAddPatient; ?>/<?php echo $doctor->id; ?>/addBackBtn/1';
        });
    });
</script>