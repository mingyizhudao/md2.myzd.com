<?php
/*
 * $model DoctorForm.
 */
$this->setPageID('pMyBooking');
$this->setPageTitle('未处理患者');
$hasBookingList = $data->results->hasBookingList;
$noBookingList = $data->results->noBookingList;
$urlCreatePatient = $this->createUrl('patient/create', array('addBackBtn' => 1, 'status' => 0));
$currentUrl = $this->getCurrentRequestUrl();
$urlDoctorTerms = $this->createAbsoluteUrl('doctor/doctorTerms');
$urlSearchView = $this->createUrl('patient/searchView', array('addBackBtn' => 1));
$urlDoctorTerms.='?returnUrl=' . $currentUrl;
$urlDoctorView = $this->createUrl('doctor/view');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$checkTeamDoctor = $teamDoctor;
$this->show_footer = false;
?>
<div>
    <nav id="patientList_nav" class="header-secondary top0p">
        <div class="w100 pl10 pr10">
            <a href="<?php echo $urlSearchView; ?>">
                <div class="searchDiv grid">
                    <div class="col-0 searchIcon">

                    </div>
                    <div class="col-1 text-left">
                        搜索
                    </div>
                </div>
            </a>
        </div>
    </nav>
</div>
<article id="patientList_article" class="active" data-scroll="true"style="background-color: #e1e1e1;">
    <div class="noBookingList  mt50 mb50">
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
                <div class="">
                    <a href="<?php echo $this->createUrl('patient/view', array('id' => $patientInfo->id, 'addBackBtn' => 1)); ?>" class="color-000" data-target="link">
                        <div class="mt10 ml10 mr10 bg-white b-white">
                            <div class="pt10 pl10  pb10 bb-g1">
                                <span class="mr5">创建时间:</span><?php echo $patientInfo->dateUpdated; ?>
                            </div>
                            <div class="pl10 mt5">
                                <?php echo $patientInfo->name; ?>
                            </div>
                            <div class="p10">
                                <?php echo $patientInfo->gender; ?>
                                <span class="ml5"><?php echo $yearlyText . $monthly; ?></span>
                                <span class="ml5"><?php echo $patientInfo->cityName; ?></span>
                            </div>
                            <div class="pl10 pb10">
                                <?php echo $patientInfo->diseaseName; ?>
                            </div>
                        </div>
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
    $(document).ready(function () {
        if ('<?php echo $checkTeamDoctor; ?>' == 1) {
            J.customConfirm('您已实名认证',
                    '<div class="mt10 mb10">尚未签署《医生顾问协议》</div>',
                    '<a data="cancel" class="w50">暂不</a>',
                    '<a data="ok" class="color-green w50">签署协议</a>',
                    function () {
                        location.href = "<?php echo $urlDoctorTerms; ?>";
                    },
                    function () {
                        location.href = "<?php echo $urlDoctorView; ?>";
                    });
        }
        $(".bookingMenu").tap(function () {
            var dataBooking = $(this).attr('data-booking');
            if (dataBooking == 'yes') {
                $('.noBookingList').addClass('hide');
                $('.hasBookingList').removeClass('hide');
                $('#patientList_article').scrollTop(0);
            } else {
                $('.hasBookingList').addClass('hide');
                $('.noBookingList').removeClass('hide');
                $('#patientList_article').scrollTop(0);
            }
        });
        $('#patientCreate').tap(function () {
            location.href = '<?php echo $urlCreatePatient; ?>';
        });
    });
</script>
