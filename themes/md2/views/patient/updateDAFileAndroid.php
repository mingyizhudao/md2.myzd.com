<?php
//Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/js/qiniu/css/bootstrap.min.css');
//Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/js/qiniu/css/main.css');
//Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/js/qiniu/css/highlight.css');
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/bootstrap.min.js?ts=' . time(), CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/plupload.full.min.js?ts=' . time(), CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/zh_CN.js?ts=' . time(), CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/ui.js?ts=' . time(), CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/qiniu.min.js?ts=' . time(), CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/highlight.js?ts=' . time(), CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/patientUpload.js?ts=' . time(), CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/jquery-1.9.1.min.js?ts=' . time(), CClientScript::POS_END);

Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.1.css');
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/custom.min.1.0.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/summaryUpload.min.1.0.js', CClientScript::POS_END);
?>

<?php
/*
 * $model PatientMRForm.
 */
$this->setPageID('pCreatePatientMR');
$this->setPageTitle('上传出院小结');
$patientId = $output['id'];
$user = $this->loadUser();
//$urlUploadFile = 'http://file.mingyizhudao.com/api/uploadparientmr'; //$this->createUrl("patient/ajaxUploadMRFile");
$urlUploadFile = $this->createUrl('qiniu/ajaxPatienMr');
$urlQiniuAjaxToken = $this->createUrl('qiniu/ajaxPatientToken');
$urlPatientAjaxDrTask = $this->createUrl('patient/ajaxDrTask');
$id = Yii::app()->request->getQuery('id', '');
$bookingId = Yii::app()->request->getQuery('bookingid', '');
$urlReturn = $this->createUrl('patient/viewDaFile', array('id' => $id, 'bookingid' => $bookingId));
if (isset($output['id'])) {
    $urlPatientMRFiles = 'http://file.mingyizhudao.com/api/loadpatientmr?userId=' . $user->id . '&patientId=' . $patientId . '&reportType=da'; //$this->createUrl('patient/patientMRFiles', array('id' => $patientId));
    $urldelectPatientMRFile = 'http://file.mingyizhudao.com/api/deletepatientmr?userId=' . $user->id . '&id='; //$this->createUrl('patient/delectPatientMRFile');
} else {
    $urlPatientMRFiles = "";
    $urldelectPatientMRFile = "";
}
$orderList = $this->createUrl('patientbooking/list', array('status' => 0));
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
?>
<article id="updateDAFileAndroid_article" class="active android_article" data-scroll="true">
    <div class="mt20 pl10 pr10">
        <div>
            请您上传与该订单对应的出院小结
        </div>
        <div>
            （最多3张）
        </div>
        <div class="clearfix"></div>
        <div class="form-wrapper mt20">
            <div class="">
                <div class="container">
                    <div class="text-left wrapper">
                        <form id="booking-form" data-url-uploadfile="<?php echo $urlUploadFile; ?>" data-url-return="<?php echo $urlReturn; ?>" data-ajaxDrTask="<?php echo $urlPatientAjaxDrTask; ?>" data-patientbookingid="<?php echo $bookingId; ?>">
                            <input id="patientId" type="hidden" name="Booking[patient_id]" value="<?php echo $patientId; ?>" />
                            <input id="reportType" type="hidden" name="Booking[report_type]" value="da" />
                            <input type="hidden" id="domain" value="http://mr.file.mingyizhudao.com">
                            <input type="hidden" id="uptoken_url" value="<?php echo $urlQiniuAjaxToken; ?>">
                        </form>
                    </div>
                    <div class="body">
                        <div class="">
                            <div id="container">
                                <a class="btn btn-default btn-lg " id="pickfiles" href="#">
                                    <span>
                                        <img src="http://static.mingyizhudao.com/146770314701592" class="w90p">
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <table class="table table-striped table-hover text-left" style="display:none">
                                <tbody id="fsUploadProgress">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="submitBtnSummary" class="pt20 pb20 bt-gray">
                        <button class="btn btn-full bg-green color-white" disabled="true">提交</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="deleteConfirm" class="confirm" style="top: 50%; left: 5%; right: 5%; border-radius: 3px; margin-top: -64.5px;">
            <div class="popup-title">提示</div>
            <div class="popup-content text-center">确定删除这张图片?</div>
            <div id="popup_btn_container">
                <a class="cancel">取消</a>
                <a class="delete">确定</a>
            </div>
        </div>
        <div id="jingle_toast" class="toast"><a href="#">取消!</a></div>
    </div>
</article>