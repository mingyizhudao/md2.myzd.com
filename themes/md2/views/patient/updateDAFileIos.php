<?php
Yii::app()->clientScript->registerCssFile('http://myzd.oss-cn-hangzhou.aliyuncs.com/static/mobile/js/webuploader/css/webuploader.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/js/webuploader/css/webuploader.custom.css');
Yii::app()->clientScript->registerScriptFile('http://myzd.oss-cn-hangzhou.aliyuncs.com/static/mobile/js/webuploader/js/webuploader.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/uploadDAFile.js?ts=' . time(), CClientScript::POS_END);
?>	
<?php
/*
 * $model PatientMRForm.
 */
$this->setPageID('pCreatePatientMR');
$this->setPageTitle('上传出院小结');
$patientId = $output['id'];
$user = $this->loadUser();
$urlUploadFile = 'http://file.mingyizhudao.com/api/uploadparientmr'; //$this->createUrl("patient/ajaxUploadMRFile");
$urlPatientAjaxDrTask = $this->createUrl('patient/ajaxDrTask', array('id' => ''));
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
<article id="updateDAFileIos_article" class="active" data-scroll="true">
    <div class="form-wrapper mt10">
        <form id="patient-form" data-url-uploadfile="<?php echo $urlUploadFile; ?>" data-url-return="<?php echo $urlReturn; ?>" data-ajaxDrTask="<?php echo $urlPatientAjaxDrTask; ?>" data-patientbookingid="<?php echo $bookingId; ?>">
            <input id="patientId" type="hidden" name="patient[id]" value="<?php echo $patientId; ?>" />
            <input id="reportType" type="hidden" name="patient[report_type]" value="da" />
        </form>
        <div class="pl10 pr10">
            <div>
                请您上传与该订单对应的出院小结
            </div>
            <div>
                （最多3张）
            </div>
            <div class="mt20">
                <!--图片上传区域 -->
                <div id="uploader" class="wu-example">
                    <div class="statusBar" style="display:none;">
                        <div class="progress">
                            <span class="text">0%</span>
                            <span class="percentage"></span>
                        </div>
                        <div class="info hide"></div>
                        <div class="">
                            <!-- btn 继续添加 -->
                            <div id="filePicker2" class=""></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="mt20">
                        </div>
                    </div>
                    <div class="queueList">
                        <div id="dndArea" class="placeholder">
                            <div id="filePicker"></div>
                            <!-- <p>或将照片拖到这里，单次最多可选10张</p>-->
                        </div>
                    </div>
                </div>
                <div class="pt20 pb20 bt-gray">
                    <button id="btnSubmit" class="statusBar uploadBtn state-pedding btn btn-yes btn-block" disabled="true">提交</button>
                </div>
            </div>
        </div>
    </div>
</article>