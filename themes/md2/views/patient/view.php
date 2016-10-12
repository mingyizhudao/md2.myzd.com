<?php
/*
 * $model DoctorForm.
 */
$this->setPageID('pPatientInfo');
$this->setPageTitle('患者详情');
$user = $this->loadUser();
$patientInfo = $data->results->patientInfo;
/*
 * source
 * 0:正常途径查看患者详情
 * 1:签约专家途径（患者含有疾病信息，仅供查看）
 * 2:签约专家途径（患者无疾病信息，完善疾病信息）
 */
$source = Yii::app()->request->getQuery('source', 0);
$id = Yii::app()->request->getQuery('id', '');
$urlSubmit = $this->createUrl('doctor/addDisease');
$doctorId = Yii::app()->request->getQuery('doctorId', '');
$urlChooseList = $this->createUrl('patient/chooseList', array('id' => $doctorId, 'patientId' => $id, 'addBackBtn' => 1));
$urlUpdatePatientMR = $this->createUrl('patient/updatePatientMR', array('id' => $patientInfo->id, 'addBackBtn' => 1));
$urlUploadMRFile = $this->createUrl('patient/uploadMRFile', array('id' => $patientInfo->id, 'type' => 'update', 'addBackBtn' => 1));
$urlPatientFiles = 'http://file.mingyizhudao.com/api/loadpatientmr?userId=' . $user->id . '&patientId=' . $patientInfo->id . '&reportType=mr'; //$this->createUrl('patient/patientMRFiles', array('id' => $patientInfo->id ? $patientInfo->id : 0));
$urlPatientBookingCreate = $this->createUrl('patientbooking/create', array('pid' => $patientInfo->id, 'addBackBtn' => 1));
$this->show_footer = false;
?>
<style>
    #jingle_popup{top:0px!important;}
</style>
<?php
if ($source != 1) {
    ?>
    <footer class='bg-white'>
        <button id='patientBookingCreate' class="btn btn-block bg-green">
            <?php echo $source == 0 ? '继续创建' : '补充疾病信息'; ?>
        </button>
    </footer>
    <?php
}
?>
<article id="patientView_article" class="active" data-scroll="true">
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
                <?php if ($patientInfo->diseaseDetail == '') { ?>
                    <div class="col-1 w70 pr10 text-right font-hs1">未选择</div>
                <?php } else { ?>
                    <div class="col-1 w70 pr10 text-right "><?php echo $patientInfo->diseaseName; ?></div>
                <?php } ?>
            </div>
            <div class="pad10 bb-gray">
                <?php if ($patientInfo->diseaseDetail == '') { ?>
                    <div class="text-center  font-hs1 ">暂没填写疾病描述</div>
                <?php } else { ?>
                    <div class="mt5 pl10 font-hs1 word-wrap"><?php echo $patientInfo->diseaseDetail; ?></div>
                <?php } ?>
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
    Zepto(function($) {
        id = "<?php echo $patientInfo->id; ?>";
        if (id) {
            ajaxPatientFiles();
        }
        $(".confirmPage").tap(function() {
            $(this).hide();
        });
        $('#patientBookingCreate').tap(function() {
            sessionStorage.removeItem('intention');
            sessionStorage.removeItem('detail');
            var diseaseName = '<?php echo $patientInfo->diseaseName ?>';
            var diseaseDetail = '<?php echo $patientInfo->diseaseDetail ?>';
            if ('<?php echo $source == 0; ?>') {
                if (diseaseDetail == '' && diseaseName == '') {
                    location.href = '<?php echo $urlSubmit . '?id=' . $id; ?>' + '&returnUrl';
                } else if (_imgfiles.length == 0) {
                    location.href = '<?php echo $urlUploadMRFile; ?>';
                } else {
                    location.href = '<?php echo $urlPatientBookingCreate; ?>';
                }
            } else if ('<?php echo $source == 2; ?>') {
                location.href = '<?php echo $urlSubmit . '?source=1&id=' . $id; ?>' + '&returnUrl=<?php echo $urlChooseList; ?>';
            }
        });
    });
    function ajaxPatientFiles() {
        urlPatientFiles = "<?php echo $urlPatientFiles; ?>";

        $.ajax({
            url: urlPatientFiles,
            success: function(data) {

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
            innerHtml += '<div class="grid">' +
                    '<div class=" col-0 bgzhaox "style="width:72px;height:72px;">' +
                    '</div>' +
                    '<div class="col-1 font-hs1 vertical-center">' +
                    '<div > ' +
                    '<div >暂无影像资料</div>' +
                    '<div>您可以在提交后在订单详情里补充</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

        }
        $(".imglist").html(innerHtml);
        $('.btn_alert').tap(function() {
            var imgUrl = $(this).find("img").attr("data-src");
            J.popup({
                html: '<div class="imgpopup"><img src="' + imgUrl + '"></div>',
                pos: 'top-second',
                showCloseBtn: true
            });
            $('.imgpopup').tap(function() {
                J.closePopup();
            });
        });
    }
</script>
