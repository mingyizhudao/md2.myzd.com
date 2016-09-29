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
$urlCreatePatient = $this->createUrl('patient/create');
$urlAddPatient = $this->createUrl('doctor/addPatient', array('id' => $doctorId, 'patientId' => ''));
?>
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
        <a href="<?php echo $urlCreatePatient . '?status=1&returnUrl=' . $createReturnUrl; ?>">
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
            echo '<div class="pad20 text-center">暂无未处理患者</div>';
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
    });
</script>
