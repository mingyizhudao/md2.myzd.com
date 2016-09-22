<?php
Yii::app()->clientScript->registerCssFile('http://myzd.oss-cn-hangzhou.aliyuncs.com/static/mobile/js/webuploader/css/webuploader.css');
Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/webuploader.custom.1.0.css');
Yii::app()->clientScript->registerScriptFile('http://myzd.oss-cn-hangzhou.aliyuncs.com/static/mobile/js/webuploader/js/webuploader.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/uploadMRFile.js?ts=' . time(), CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/uploadMRFile.min.1.0.js', CClientScript::POS_END);
?>
<?php
/*
 * $model PatientMRForm.
 */
$this->setPageID('pCreatePatientMR');
$this->setPageTitle('上传患者病历');
$urlLogin = $this->createUrl('doctor/login');
$patientId = $output['id'];
$user = $this->loadUser();
$urlSubmitMR = $this->createUrl("patient/ajaxCreatePatientMR");
$urlUploadFile = 'http://121.40.127.64:8089/api/uploadparientmr'; //$this->createUrl("patient/ajaxUploadMRFile");
$urlReturn = $this->createUrl('patient/view', array('id' => $patientId));

$patientBookingId = Yii::app()->request->getQuery('patientBookingId', '');
$patientAjaxTask = $this->createUrl('patient/ajaxTask', array('id' => ''));

$reportType = Yii::app()->request->getQuery('report_type', 'mr');
$bookingId = Yii::app()->request->getQuery('bookingid', '');

$type = Yii::app()->request->getQuery('type', 'create');
if ($type == 'update') {
    $urlReturn = $this->createUrl('patient/view', array('id' => $patientId, 'addBackBtn' => 1));
} else if ($type == 'create') {
    if ($output['returnUrl'] == '') {
        $urlReturn = $this->createUrl('patientbooking/create', array('pid' => $patientId, 'addBackBtn' => 1));
    } else if ($reportType == 'da') {
        $urlReturn = $this->createUrl('order/orderView', array('bookingid' => $bookingId, 'addBackBtn' => 1));
    } else {
        $urlReturn = $output['returnUrl'];
    }
}
if (isset($output['id'])) {
    $urlPatientMRFiles = 'http://file.mingyizhudao.com/api/loadpatientmr?userId=' . $user->id . '&patientId=' . $patientId . '&reportType=' . $reportType; //$this->createUrl('patient/patientMRFiles', array('id' => $patientId));
    $urldelectPatientMRFile = 'http://file.mingyizhudao.com/api/deletepatientmr?userId=' . $user->id . '&id='; //$this->createUrl('patient/delectPatientMRFile');
} else {
    $urlPatientMRFiles = "";
    $urldelectPatientMRFile = "";
}
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$this->show_footer = false;
?>
<style>
    .exampleSection{
        margin-top: 20px;
        border: 1px dashed #A9A9A9;
        padding: 10px 20px 20px;
    }
    .skipBtn{
        color: #19aea5;
        border: 1px solid #19aea5!important;
        background-color: #fff;
        font-size: 16px;
    }
    #uploader .webuploader-pick:after{
        display: none;
    }
    .webuploader-pick{
        display: block;
        background-color: #19aea5;
        width: 100%;
        padding: 10px!important;
        border-radius: 5px;
        line-height: inherit!important;
    }
    #uploader .queueList{
        margin: 0px;
    }
    .uploadBtn{
        margin-left: 0px;
        font-size: 16px;
    }
    #uploader .filelist li{
        height: 90px;
        width: 33.333%;
    }
    #uploader .filelist li p.imgWrap{
        text-align: center;
        width: 90px;
        height: 90px;
        line-height: 90px;
    }
    #filePicker3 .webuploader-pick{
        width: 90px;
        border: 1px dashed #aaa;
        font-size: 4em;
        height: 90px;
        background-color: #fff;
        color: #aaa;
        line-height: 60px!important;
    }
</style>
<article id="a1" class="active" data-scroll="true">
    <div class="form-wrapper">
        <form id="patient-form" data-url-uploadfile="<?php echo $urlUploadFile; ?>" data-url-return="<?php echo $urlReturn; ?>" data-patientBookingId="<?php echo $patientBookingId; ?>" data-patientAjaxTask="<?php echo $patientAjaxTask; ?>">
            <input id="patientId" type="hidden" name="patient[id]" value="<?php echo $output['id']; ?>" />
            <input id="reportType" type="hidden" name="patient[report_type]" value="mr" />
        </form>
        <div class="pad10">
            <div class="text-center mt20">
                <div class="font-w800">
                    请您上传患者的相关病历资料
                </div>
                <div class="font-s12 color-gray">
                    (图片需清晰可见，最多9张)
                </div>
            </div>
            <div class="mt20">
                <!--图片上传区域 -->
                <div id="uploader" class="wu-example">
                    <!--<div class="imglist"><ul class="filelist"></ul></div>-->
                    <div class="queueList">
                        <div id="dndArea" class="placeholder">
                            <div class="exampleSection mb20">
                                <div class="text-center font-s14">
                                    示例(如CT、磁共振、病理报告等)
                                </div>
                                <div class="grid mt20">
                                    <div class="col-1 w40">
                                        <img src="http://static.mingyizhudao.com/147433914906338"/>
                                    </div>
                                    <div class="col-1 w20">

                                    </div>
                                    <div class="col-1 w40">
                                        <img src="http://static.mingyizhudao.com/146968742128587"/>
                                    </div>
                                </div>
                            </div>
                            <div id="filePicker"></div>
                            <div class="mt10">
                                <?php
                                if ($type == 'create') {
                                    echo '<a href="' . $urlReturn . '" class="btn btn-full skipBtn">稍后补充</a>' .
                                    '<div class="text-center color-gray font-s12">(提交订单后可在订单详情里补充)</div>';
                                }
                                ?>
                            </div>
                            <!-- <p>或将照片拖到这里，单次最多可选10张</p>-->
                        </div>
                    </div>
                    <div class="statusBar" style="display:none; padding-bottom: 40px;">
                        <div class="progress">
                            <span class="text">0%</span>
                            <span class="percentage"></span>
                        </div>
                        <div class="info hide"></div>
                        <div class="">
                            <!-- btn 继续添加 -->
                            <!--<div id="filePicker2" class="pull-right"></div>-->
                            <ul class="filelist">
                                <li id="filePicker3" class="btn-add-img">+</li>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                        <div class="pt20 pb20">
                            <a id="btnSubmit" class="statusBar uploadBtn state-pedding btn btn-yes btn-full">确认</a>
                        </div>
                    </div>
                    <!--一开始就显示提交按钮就注释上面的提交 取消下面的注释 -->
                    <!--<div class="statusBar uploadBtn">提交</div>-->
                </div>
            </div>
        </div>
    </div>
</article>
<script type="text/javascript">
    $(document).ready(function() {
        var urlPatientMRFiles = "<?php echo $urlPatientMRFiles; ?>";
//        $.ajax({
//            url: urlPatientMRFiles,
//            success: function(data) {
//                setImgHtml(data.results);
//            }
//        });
    });
    function setImgHtml(files) {
        var innerHtml = '';
        var imgfiles = files.files;
        if (imgfiles && imgfiles.length > 0) {
            for (i = 0; i < imgfiles.length; i++) {
                var imgfile = imgfiles[i];
                innerHtml +=
                        '<li id="' +
                        imgfile.id + '"><p class="imgWrap"><img src="' +
                        imgfile.thumbnailUrl + '" data-src="' +
                        imgfile.absFileUrl + '"></p><div class="file-panel delete">删除</div></li>';
            }
        } else {
            innerHtml += '';
        }
        $(".imglist .filelist").html(innerHtml);
        initDelete();
    }
    function initDelete() {
        $('.imglist .delete').tap(function() {
            domLi = $(this).parents("li");
            id = domLi.attr("id");
            J.confirm('提示', '确定删除这张图片?', function() {
                deleteImg(id, domLi);
            }, function() {
                J.showToast('取消', '', 1000);
            });
        });
    }
    function deleteImg(id, domLi) {
        J.showMask();
        var urldelectPatientMRFile = '<?php echo $urldelectPatientMRFile ?>' + id;
        $.ajax({
            url: urldelectPatientMRFile,
            beforeSend: function() {

            },
            success: function(data) {
                if (data.status == 'ok') {
                    domLi.remove();
                    J.showToast('删除成功!', '', 1000);
                } else {
                    //console.log(data.error);
                    J.showToast(data.error, '', 3000);
                }
            },
            complete: function() {
                J.hideMask();
            }
        });
    }
</script>

