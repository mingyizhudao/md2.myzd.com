<?php
$this->setPageTitle('未处理患者');
$hasBookingList = $data->results->hasBookingList;
$noBookingList = $data->results->noBookingList;
$currentUrl = $this->getCurrentRequestUrl();
$urlDoctorTerms = $this->createAbsoluteUrl('doctor/doctorTerms');
$urlSearchView = $this->createUrl('patient/searchView', array('addBackBtn' => 1));
$urlDoctorTerms.='?returnUrl=' . $currentUrl;
$urlDoctorView = $this->createUrl('doctor/view');
$ajaxDeleteDoctorPatient = $this->createUrl('patient/ajaxDeleteDoctorPatient');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$checkTeamDoctor = $teamDoctor;
$this->show_footer = false;
$doctorId = Yii::app()->request->getQuery('id', '');
$patientId = Yii::app()->request->getQuery('patientId', '');
$createReturnUrl = $this->createUrl('patient/chooseList', array('id' => $doctorId, 'addBackBtn' => 1));
$urlCreatePatient = $this->createUrl('patient/create', array('addBackBtn' => 1, 'status' => 0, 'returnUrl' => ''));
$urlAddPatient = $this->createUrl('doctor/addPatient', array('id' => $doctorId, 'patientId' => ''));
?>
<style>
    #chooseList_footer.hide~article{
        bottom: 0px;
    }
    #chooseList_footer{
        background-color: #32c9c0;
        color: #fff;
    }
</style>
<header class="bg-green" id="patientList_header">
    <nav class="left pr10">
        <a href="javascript:;" data-target="back">
            <div class="pl5">
                <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
            </div>
        </a>
    </nav>
    <h1 class="title">未处理患者</h1>
    <nav class="right">
        <a href="<?php echo $urlCreatePatient; ?>">
            <img src="http://static.mingyizhudao.com/14743390650457" class="w20p">
        </a>
    </nav>
</header>
<footer class="grid middle <?php echo $patientId == '' ? 'hide' : ''; ?>" id="chooseList_footer">
    确定
</footer>
<article id="chooseList_article" class="active bg-gray" data-scroll="true">
    <div class="noBookingList pb20">
        <?php
        if ($noBookingList) {
            for ($i = 0; $i < count($noBookingList); $i++) {
                $patientInfo = $noBookingList[$i];
                $yearly = $patientInfo->age;
                $yearlyText = '';
                $monthly = "";
                if ($yearly == 0 && $patientInfo->ageMonth > 0) {
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
                <div class="mt10 ml10 mr10 bg-white b-white br5">
                    <div class="pt5 pl10 pb10 bb-g1 grid" id="dv">
                        <div class="col-1 pt2">
                            创建时间:<?php echo $patientInfo->dateUpdated; ?>
                        </div>
                        <?php
                        if ($patientInfo->diseaseName != '') {
                            if ($patientInfo->id == $patientId) {
                                ?>
                                <div class="col-0 pr10 selectBtn" data-id="<?php echo $patientInfo->id; ?>" data-active="1">
                                    <img src="http://static.mingyizhudao.com/146968462384937" class="w20p">
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="col-0 pr10 selectBtn" data-id="<?php echo $patientInfo->id; ?>">
                                    <img src="http://static.mingyizhudao.com/14696845618638" class="w20p">
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <a href="<?php echo $this->createUrl('patient/view', array('id' => $patientInfo->id, 'doctorId' => $doctorId, 'source' => $patientInfo->diseaseName == '' ? 2 : 1)); ?>" class="color-000" data-target="link">
                        <div class="pl10 mt5">
                            <?php echo $patientInfo->name; ?>
                        </div>
                        <div class="p10">
                            <?php echo $patientInfo->gender; ?>
                            <span class="ml5"><?php echo $yearlyText . $monthly; ?></span>
                            <span class="ml5"><?php echo $patientInfo->cityName; ?></span>
                        </div>
                        <?php if ($patientInfo->diseaseName != '') { ?>
                            <div class="pl10 pb10"><?php echo $patientInfo->diseaseName; ?></div>
                        <?php } else { ?>
                            <div class="pl10 pb10 pr10 color-green font-s12">您还没有选择疾病名称，点击补充后才可选择</div>
                        <?php } ?>
                    </a>
                </div>
                <?php
            }
        } else {
            echo '<p class="text-center">暂无病人信息</p>';
        }
        ?>
    </div>
</article>
<script>
    $(document).ready(function() {
        //选中患者id
        var patientId = '<?php echo $patientId; ?>';
        //选择取消对象
        $('.selectBtn').click(function() {
            if ($(this).attr('data-active') == 1) {
                $('.selectBtn').each(function() {
                    $(this).removeAttr('data-active');
                    $(this).find('img').attr('src', 'http://static.mingyizhudao.com/14696845618638');
                });
                $(this).find('img').attr('src', 'http://static.mingyizhudao.com/14696845618638');
                $(this).removeAttr('data-active');
            } else {
                patientId = $(this).attr('data-id');
                $('.selectBtn').each(function() {
                    $(this).removeAttr('data-active');
                    $(this).find('img').attr('src', 'http://static.mingyizhudao.com/14696845618638');
                });
                $(this).find('img').attr('src', 'http://static.mingyizhudao.com/146968462384937');
                $(this).attr('data-active', 1);
            }
            var hasBool = false;
            $('.selectBtn').each(function() {
                if ($(this).attr('data-active') == 1) {
                    hasBool = true;
                }
            });
            if (hasBool) {
                $('footer').removeClass('hide');
            } else {
                $('footer').addClass('hide');
            }
        });
        //确定
        $('#chooseList_footer').click(function() {
            location.href = '<?php echo $urlAddPatient; ?>/' + patientId;
        });










        var innerHtml = '<div class="col-0 pr10 selectBtn hide" data-id="<?php echo $patientInfo->id; ?>">' +
                '<img src="http://static.mingyizhudao.com/14696845618638" class="w20p">' +
                '</div>';
        // if('<?php echo $patientInfo->diseaseName; ?>'!=''){
        //      $('selectBtn').removeAttr('hide');
        // }
    });
//     $(document).ready(function () {
//         if ('<?php echo $checkTeamDoctor; ?>' == 1) {
//             J.customConfirm('您已实名认证',
//                     '<div class="mt10 mb10">尚未签署《医生顾问协议》</div>',
//                     '<a data="cancel" class="w50">暂不</a>',
//                     '<a data="ok" class="color-green w50">签署协议</a>',
//                     function () {
//                         location.href = "<?php echo $urlDoctorTerms; ?>";
//                     },
//                     function () {
//                         location.href = "<?php echo $urlDoctorView; ?>";
//                     });
//         }
//         //编辑
//         // $('#editPatient').click(function () {
//         //     $(this).addClass('hide');
//         //     $('#cancelEdit').removeClass('hide');
//         //     $('.selectBtn').removeClass('hide');
//         //     $('.selectBtn').each(function(){
//         //         if($(this).attr('data-active')==1){
//         //           $('footer').removeClass('hide');   
//         //      }
//         //     })

//         // });
//         //取消编辑
//         // $('#cancelEdit').click(function () {
//         //     $(this).addClass('hide');
//         //     $('#editPatient').removeClass('hide');
//         //     $('.selectBtn').addClass('hide');
//         //     $('footer').addClass('hide');
    //         // });

//         //取消
//         $('footer').click(function () {
//             var patientList = new Array();
//             var selectBool = false;
//             $('.selectBtn').each(function () {
//                 if ($(this).attr('data-active') == 1) {
//                     selectBool = true;
//                     var id = $(this).attr('data-id');
//                     patientList.push(id);
//                 }
//             });
//             if (selectBool) {
//                 J.customConfirm('提示',
//                         '<div class="mt10 mb10">您确认删除选中的患者吗?(不可恢复)</div>',
//                         '<a id="cancel" class="w50">取消</a>',
//                         '<a id="ok" class="color-green w50">删除</a>',
//                         function () {
//                         },
//                         function () {
//                         });
//                 $('#cancel').click(function () {
//                     J.closePopup();
//                 });
//                 $('#ok').click(function () {
// //                    patientList = patientList.substr(0, patientList.length - 1);
//                     console.log(patientList);
//                     $.ajax({
//                         type: 'post',
//                         url: '<?php echo $ajaxDeleteDoctorPatient; ?>',
//                         data: {patient_ids: patientList},
//                         success: function (data) {
//                             if (data.status == 'ok') {
//                                 location.reload();
//                             }
//                         },
//                         error: function (XmlHttpRequest, textStatus, errorThrown) {
//                             console.log(XmlHttpRequest);
//                             console.log(textStatus);
//                             console.log(errorThrown);
//                         }
//                     });
//                 });
//             } else {
//                 J.showToast('请选择删除患者', '', '1500');
//             }
//         });

//         $(".bookingMenu").tap(function () {
//             var dataBooking = $(this).attr('data-booking');
//             if (dataBooking == 'yes') {
//                 $('.noBookingList').addClass('hide');
//                 $('.hasBookingList').removeClass('hide');
//                 $('#patientList_article').scrollTop(0);
//             } else {
//                 $('.hasBookingList').addClass('hide');
//                 $('.noBookingList').removeClass('hide');
//                 $('#patientList_article').scrollTop(0);
//             }
//         });
//         $('#patientCreate').tap(function () {
//             location.href = '<?php echo $urlCreatePatient; ?>';
//         });
//     });
</script>
