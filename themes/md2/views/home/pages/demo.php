<?php
Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.1.css');
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/custom.min.1.0.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/idCardUpload.js?ts=' . time(), CClientScript::POS_END);
?>
<?php
$urlPatientAjaxDrTask = $this->createUrl('patient/ajaxDrTask');
$urlQiniuAjaxToken = $this->createUrl('qiniu/ajaxDrToken');
$ajaxDoctorRealAuth = $this->createUrl('qiniu/ajaxDoctorRealAuth');
$this->show_footer = false;
?>
<style>
    .w70p{
        width: 70px;
    }
    nav.right {
        width: auto!important;
        background-color: inherit!important;
        margin-top: 12px!important;
    }
    .uploadTab{
        margin-bottom: 0px;
    }
    .uploadTab a {
        width: inherit;
        min-width: inherit;
        line-height: normal;
        display: inline-block!important;
        background-color: #fff;
    }
</style>
<header class="bg-green">
    <h1 class="title">实名认证</h1>
    <nav class="right">
        稍后补充
    </nav>
</header>
<article class="active" data-scroll="true">
    <div>
        <div class="grid text-center">
            <div class="col-1 w25">
                注册成功
            </div>
            <div class="col-1 w25">
                基本信息
            </div>
            <div class="col-1 w25">
                实名认证
            </div>
            <div class="col-1 w25">
                医师认证
            </div>
        </div>

        <div class="container">
            <div class="text-left wrapper">
                <form id="idCard-form" data-url-uploadfile="<?php echo $ajaxDoctorRealAuth; ?>" data-url-return="<?php //echo $urlReturn;         ?>" data-ajaxDrTask="<?php //echo $urlPatientAjaxDrTask; ?>" data-patientbookingid="<?php //echo $bookingId;         ?>">
                    <input id="patientId" type="hidden" name="Booking[patient_id]" value="<?php //echo $patientId;         ?>" />
                    <input id="reportType" type="hidden" name="Booking[report_type]" value="da" />
                    <input type="hidden" id="domain" value="http://7xp8ky.com1.z0.glb.clouddn.com/">
                    <input type="hidden" id="uptoken_url" value="<?php echo $urlQiniuAjaxToken; ?>">
                </form>
            </div>
        </div>
        <div class="pl10">
            <div class="grid pt10 pr10 pb10 bb-gray">
                <div class="col-1">
                    <p>一张用户手持身份证正面的照片</p>
                    <p class="font-s12">*需看到完整头部，建议手拿身份证正面放于胸前</p>
                </div>
                <div class="col-0">
                    <div class="body">
                        <div class="">
                            <div id="container1" class="uploadTab">
                                <a class="btn btn-lg " id="pickfiles1" href="#">
                                    <span>
                                        <img src="http://static.mingyizhudao.com/147634523695755" class="w70p">
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12 mt10 hide">
                            <table class="table table-striped table-hover text-left" style="display:none">
                                <tbody id="fsUploadProgress1">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid pt10 pr10 pb10 bb-gray">
                <div class="col-1">
                    <p>一张清晰完整的身份证正面照</p>
                    <p class="font-s12">*只需拍身份证的正面即可</p>
                </div>
                <div class="col-0">
                    <div class="body">
                        <div class="">
                            <div id="container2" class="uploadTab">
                                <a class="btn btn-lg " id="pickfiles2" href="#">
                                    <span>
                                        <img src="http://static.mingyizhudao.com/147634523727390" class="w70p">
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12 mt10 hide">
                            <table class="table table-striped table-hover text-left" style="display:none">
                                <tbody id="fsUploadProgress2">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid pt10 pr10 pb10 bb-gray">
                <div class="col-1">
                    <p>一张用户手持身份证反面的照片</p>
                    <p class="font-s12">*只需拍身份证的反面即可</p>
                </div>
                <div class="col-0">
                    <div class="body">
                        <div class="">
                            <div id="container3" class="uploadTab">
                                <a class="btn btn-lg " id="pickfiles3" href="#">
                                    <span>
                                        <img src="http://static.mingyizhudao.com/147634523577826" class="w70p">
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12 mt10 hide">
                            <table class="table table-striped table-hover text-left" style="display:none">
                                <tbody id="fsUploadProgress3">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pt30">
            <a id="submitBtn" class="btn btn-block bg-green color-fff">下一步</a>
        </div>
    </div>
</article>
<div id="jingle_loading" style="display: block;" class="loading initLoading"><i class="icon spinner"></i><p>加载中...</p><div id="tag_close_popup" data-target="closePopup" class="icon cancel-circle"></div></div>
<div id="jingle_loading_mask" style="opacity: 0; display: block;"></div>
<div id="jingle_toast" class="toast" style="display: none;"><a href="#">请完善图片</a></div>