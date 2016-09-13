<?php
/*
 * $model DoctorForm.
 */
$this->setPageID('pPatientInfo');
$this->setPageTitle('患者详情');
$user = $this->loadUser();
$patientInfo = $data->results->patientInfo;
$addBooking = Yii::app()->request->getQuery('addBooking', '1');
$id = Yii::app()->request->getQuery('id', '');
$urlSubmit = $this->createUrl('doctor/addDisease');
$urlUpdatePatientMR = $this->createUrl('patient/updatePatientMR', array('id' => $patientInfo->id, 'addBackBtn' => 1));
$urlUploadMRFile = $this->createUrl('patient/uploadMRFile', array('id' => $patientInfo->id, 'type' => 'update', 'addBackBtn' => 1));
$urlPatientFiles = 'http://file.mingyizhudao.com/api/loadpatientmr?userId=' . $user->id . '&patientId=' . $patientInfo->id . '&reportType=mr'; //$this->createUrl('patient/patientMRFiles', array('id' => $patientInfo->id ? $patientInfo->id : 0));
$urlPatientBookingCreate = $this->createUrl('patientbooking/create', array('pid' => $patientInfo->id, 'addBackBtn' => 1));
$this->show_footer = false;
?>
<style>
     .bb1-gray{border-bottom: 10px solid #dfdfdf;}
     .bgzhao{background: url("http://static.mingyizhudao.com/147365088158978") no-repeat;background-size: 37px 27px ;width: 40px;
             position: relative;background-position: 11px 20px;}
    .font-hs1{color: #B8B8B8;}
    #jingle_popup{top:0px!important;}
</style>
<?php
if ($addBooking == 1) {
    ?>
    <footer class='bg-white'>
        <button id='patientBookingCreate' class="btn btn-block bg-green">创建就诊信息</button>
    </footer>
<?php }
?>
<article  class="active" data-scroll="true">
    <div class="pt10 pb10">
        <div class=" bg-white  mb20">
            
            <div class="grid pb10 pl20 pr20 bb-gray">
                <div class="col-1 w30">患者姓名</div>
                <div class="col-1 w70 text-right"><?php echo $patientInfo->name; ?></div>
            </div>
            <div class="grid pad10 bb-gray">
                <div class="col-1 pl10 w30">联系方式</div>
                <div class="col-1 pr10 w70 text-right"><?php echo $patientInfo->mobile; ?></div>
            </div>
            <?php
            $yearly = $patientInfo->age;
            $yearlyText = '';
            $monthly = "";
            if ($yearly == 0 && $patientInfo->ageMonth >= 0) {
                $yearlyText = '';
                $monthly = $patientInfo->ageMonth . '个月';
            } else if ($yearly <= 5 && $patientInfo->ageMonth > 0) {
                $yearlyText = $yearly . '岁';
                $monthly = $patientInfo->ageMonth . '个月';
            } else if ($yearly > 5 && $patientInfo->ageMonth > 0) {
                $yearly++;
                $yearlyText = $yearly . '岁';
            } else {
                $yearlyText = $yearly . '岁';
            }
            ?>
            <div class="grid pad10 bb-gray">
                <div class="col-1 pl10 w30">患者年龄</div>
                <div class="col-1 pr10 w70 text-right"><?php echo $yearlyText . $monthly; ?></div>
            </div>
            <div class="grid pad10 bb1-gray">
                <div class="col-1 pl10 w30">所在城市</div>
                <div class="col-1 w70 pr10 text-right"><?php echo $patientInfo->cityName; ?></div>
            </div>
            <div class="grid pad10 bb-gray">
                <div class="col-1 w30 pl10">疾病名称</div>
                <!-- <div class="col-1 w70 text-right"><?php echo $patientInfo->diseaseName; ?></div> -->
                 <?php if($patientInfo->diseaseDetail==''){?>
                <div class="col-1 w70 pr10 text-right font-hs1">未选择</div>
                <?php }else{?>
                <div class="col-1 w70 pr10 text-right "><?php echo $patientInfo->diseaseName; ?></div>
                <?php }?>
            </div>
            <div class="pad10 bb-gray">
                
                <?php if($patientInfo->diseaseDetail==''){?>
                <div class="text-center  font-hs1">暂没填写疾病描述</div>
               <?php }else{?>
               <div class="mt5 pl10 font-hs1 pr10"><?php echo $patientInfo->diseaseDetail; ?></div>
               <?php }?>
            </div>
            <div class="pad10">
              <div class=" pl10 pr10 imglist ">
             </div>
            </div>
            
            <!-- <div>
                <a href="<?php echo $urlUploadMRFile; ?>" data-target="link">
                    <div class="color-green pad5 text-center">点击编辑</div>
                </a>
            </div> -->
        </div>
    </div>
</div>
</article>
<script type="text/javascript">
    Zepto(function ($) {
        id = "<?php echo $patientInfo->id; ?>";
        if (id) {
            ajaxPatientFiles();
        }
        $(".confirmPage").tap(function () {
            $(this).hide();
        });
        $('#patientBookingCreate').tap(function () {
            sessionStorage.removeItem('intention');
            var diseaseName='<?php echo $patientInfo->diseaseName?>';
            var diseaseDetail='<?php echo $patientInfo->diseaseDetail?>';
            if(diseaseDetail==''&&diseaseName==''){
              location.href = '<?php echo $urlSubmit.'?id='.$id; ?>'+'&returnUrl';
           }
            else if(_imgfiles==''){
                location.href = '<?php echo $urlUploadMRFile ; ?>';
              }
            else {
               location.href = '<?php echo $urlPatientBookingCreate; ?>';
            }
        });
    });
    function ajaxPatientFiles() {
        urlPatientFiles = "<?php echo $urlPatientFiles; ?>";
        
        $.ajax({
            url: urlPatientFiles,
            success: function (data) {
              
                setImgHtml(data.results.files);
            }
        });
    }
    var _imgfiles = '';
    function setImgHtml(imgfiles) {
        _imgfiles = imgfiles;
        var innerHtml = '';
        var uiBlock = '';
        if (imgfiles && imgfiles.length > 0) {
            for (i = 0; i < imgfiles.length; i++) {
                if (i % 2 == 0) {
                    innerHtml += '<div class="grid mt10">';
                }
                innerHtml +=
                        '<div class="col-0 pl10 pr10 w50 text-center"><a class="btn_alert"><img class="" data-src="' + imgfiles[i].absFileUrl + '" src="' +
                        imgfiles[i].thumbnailUrl + '" /></div>';
                if (i % 2 == 1) {
                    innerHtml += '</div>';
                }
                //'<div class="' + uiBlock + '"><img data-src="' + imgfiles[i].absFileUrl + '" src="' + imgfiles[i].absThumbnailUrl + '"></div>';
            }
        } else {
            var url = $('.imgUrl').attr('href');
            innerHtml +='<div class="grid">'+
                        '<div class="col-0 bgzhao "style="border:1px dashed #bbb;width:20%;">'+
                        '</div>'+
                        '<div class="col-1 font-hs1">'+
                            '<div>暂无影像资料</div>'+
                            '<div>您可以在提交后在订单详情里补充</div>'+
                        '</div>'+
                    '</div>';
                    
        }
        $(".imglist").html(innerHtml);
        $('.btn_alert').tap(function () {
            var imgUrl = $(this).find("img").attr("data-src");
            J.popup({
                html: '<div class="imgpopup"><img src="' + imgUrl + '"></div>',
                pos: 'top-second',
                showCloseBtn: true
            });
            $('.imgpopup').tap(function () {
                J.closePopup();
            });
        });
    }
</script>
