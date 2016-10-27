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
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/patientUpload.js?ts=' . time(), CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/md2/patientUpload.min.1.4.js', CClientScript::POS_END);
?>

<?php
/*
 * $model PatientMRForm.
 */
$this->setPageID('pCreatePatientMR');
$this->setPageTitle('上传病历');
$urlLogin = $this->createUrl('doctor/login');
$patientId = $output['id'];
$user = $this->loadUser();
$urlSubmitMR = $this->createUrl("patient/ajaxCreatePatientMR");
//$urlUploadFile = 'http://file.mingyizhudao.com/api/uploadparientmr'; //$this->createUrl("patient/ajaxUploadMRFile");
$urlUploadFile = $this->createUrl('qiniu/ajaxPatienMr');
$urlQiniuAjaxToken = $this->createUrl('qiniu/ajaxPatientToken');
$urlReturn = $this->createUrl('patient/view', array('id' => $patientId));

$patientBookingId = Yii::app()->request->getQuery('patientBookingId', '');
$patientAjaxTask = $this->createUrl('patient/ajaxTask', array('id' => ''));

$type = Yii::app()->request->getQuery('type', 'create');
if ($type == 'update') {
    $urlReturn = $this->createUrl('patient/view', array('id' => $patientId, 'addBackBtn' => 1));
} else if ($type == 'create') {
    if ($output['returnUrl'] == '') {
        $urlReturn = $this->createUrl('patientbooking/create', array('pid' => $patientId, 'addBackBtn' => 1));
    } else {
        $urlReturn = $output['returnUrl'];
    }
}
if (isset($output['id'])) {
    $urlPatientMRFiles = 'http://file.mingyizhudao.com/api/loadpatientmr?userId=' . $user->id . '&patientId=' . $patientId . '&reportType=mr'; //$this->createUrl('patient/patientMRFiles', array('id' => $patientId));
    $urldelectPatientMRFile = 'http://file.mingyizhudao.com/api/deletepatientmr?userId=' . $user->id . '&id='; //$this->createUrl('patient/delectPatientMRFile');
} else {
    $urlPatientMRFiles = "";
    $urldelectPatientMRFile = "";
}
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$this->show_footer = false;
?>
<article id="updateMRFileAndroid_article" class="active android_article" data-scroll="true">
    <div class="pad10">
        <div class="mt20">
            <div class="font-w800">
                请上传支持目前诊断的辅助检查报告（至少1张）以便医疗助手协助您与专家详细沟通
            </div>
            <div class="font-s12 color-gray">
                (最多9张，提交后可以在订单详情里补充)
            </div>
        </div>
        <div class="imglist mt10">
            <ul class="filelist"></ul>
        </div>
        <div class="clearfix"></div>
        <div class="container">
            <div class="text-left wrapper">
                <form id="booking-form" data-url-uploadfile="<?php echo $urlUploadFile; ?>" data-url-return="<?php echo $urlReturn; ?>" data-patientBookingId="<?php echo $patientBookingId; ?>" data-patientAjaxTask="<?php echo $patientAjaxTask; ?>">
                    <input id="patientId" type="hidden" name="Booking[patient_id]" value="<?php echo $patientId; ?>" />
                    <input id="reportType" type="hidden" name="Booking[report_type]" value="mr" />
                    <input type="hidden" id="domain" value="http://mr.file.mingyizhudao.com">
                    <input type="hidden" id="uptoken_url" value="<?php echo $urlQiniuAjaxToken; ?>">
                </form>
            </div>
            <div class="body mt10">
                <div class="col-md-12 mt10">
                    <table class="table table-striped table-hover text-left" style="display:none">
                        <tbody id="fsUploadProgress">
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    <div id="container">
                        <a id="pickfiles" class="" href="#" >
                            <span>点击添加图片</span>
                        </a>
                    </div>
                </div>
            </div>
            <div id="submitBtn" class="hide pt20">
                <button class="btn btn-full bg-green color-white">确认</button>
            </div>
        </div>
        <div class="exampleSection">
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
        <div id="deleteConfirm" class="confirm" style="top: 50%; left: 5%; right: 5%; border-radius: 3px; margin-top: -64.5px;">
            <div class="popup-title">提示</div>
            <div class="popup-content text-center">确定删除这张图片?</div>
            <div id="popup_btn_container">
                <a class="cancel">取消</a>
                <a class="delete">确定</a>
            </div>
        </div>
        <div id="jingle_toast" class="toast"><a href="#" class="font-s18">取消!</a></div>
        <div id="loading_popup" style="" class="loading">
            <i class="icon spinner"></i>
            <p>上传中...</p>
        </div>
    </div>
</article>
<script type="text/javascript">
    $(document).ready(function () {
        $("#deleteConfirm .cancel").click(function () {
            $("#deleteConfirm").hide();
            $("#jingle_toast").show();
            setTimeout(function () {
                $("#jingle_toast").hide();
            }, 1000);
        });
        $("#deleteConfirm .delete").click(function () {
            $("#deleteConfirm").hide();
            id = $(this).attr("data-id");
            domId = "#" + id;
            domLi = $(domId);
            deleteImg(id, domLi);
            setTimeout(function () {
                $("#jingle_toast").hide();
            }, 2000);
        });
        //加载病人病历图片
        var urlPatientMRFiles = "<?php echo $urlPatientMRFiles; ?>";
//        $.ajax({
//            url: urlPatientMRFiles,
//            success: function(data) {
//                setImgHtml(data.results.files);
//            }
//        });
    });
    function setImgHtml(imgfiles) {
        var innerHtml = '';
        if (imgfiles && imgfiles.length > 0) {
            for (i = 0; i < imgfiles.length; i++) {
                imgfile = imgfiles[i];
                innerHtml +=
                        '<li id="' +
                        imgfile.id + '"><p class="imgWrap"><img src="' +
                        imgfile.thumbnailUrl + '" data-src="' +
                        imgfile.absFileUrl + '"></p><div class="file-panel delete"><span class="">删除</span></div></li>';
            }
        } else {
            innerHtml += '';
        }
        $(".imglist .filelist").html(innerHtml);
        initDelete();
    }
    function initDelete() {
        $('.imglist .delete').click(function () {
            domLi = $(this).parents("li");
            id = domLi.attr("id");
            $("#deleteConfirm .delete").attr("data-id", id);
            $("#deleteConfirm").show();
        });
    }
    function deleteImg(id, domLi) {
        $(".ui-loader").show();
        urlDelectDoctorCert = '<?php echo $urldelectPatientMRFile ?>' + id;
        $.ajax({
            url: urlDelectDoctorCert,
            beforeSend: function () {

            },
            success: function (data) {
                if (data.status == 'ok') {
                    domLi.remove();
                    $("#jingle_toast a").text('删除成功!');
                    $("#jingle_toast").show();
                }
            },
            complete: function () {
                $(".ui-loader").hide();
            }
        });
    }
</script>
