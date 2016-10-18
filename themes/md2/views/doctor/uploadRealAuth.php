<?php
Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.1.css');
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/custom.min.1.0.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/idCardUpload.js?ts=' . time(), CClientScript::POS_END);
?>
<?php
$this->setPageTitle('实名认证');
$urlPatientAjaxDrTask = $this->createUrl('patient/ajaxDrTask');
$urlQiniuAjaxToken = $this->createUrl('qiniu/ajaxDrToken');
$ajaxDoctorRealAuth = $this->createUrl('qiniu/ajaxDoctorRealAuth');
$urlUploadCert = $this->createUrl('doctor/uploadCert', array('addBackBtn' => 1, 'register' => 1));
$register = Yii::app()->request->getQuery('register', 0);
if ($register == 1) {
    $urlReturn = $urlUploadCert;
} else {
    $urlReturn = $this->createUrl('doctor/view');
}
if (isset($output['id'])) {
    $urlDoctorRealAuth = 'http://121.40.127.64:8089/api/loadrealauth?userId=' . $output['id'];
} else {
    $urlDoctorRealAuth = "";
}
$isVerified = $output['isVerified'];
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

    #jingle_loading_mask {
        background-color: #222;
    }
    #jingle_loading_mask {
        display: none;
        position: absolute;
        z-index: 90;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        opacity: 0;
    }
    #jingle_loading.loading {
        background-color: #2C3E50;
    }
    #jingle_loading.loading {
        top: 50%;
        left: 50%;
        margin: -75px 0 0 -75px;
        opacity: .9;
        text-align: center;
        width: 150px;
        height: 150px;
        border-radius: 10px;
    }
    #jingle_loading {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        z-index: 98;
        min-height: 50px;
    }
    #jingle_loading.loading i.icon {
        color: #fff;
        font-size: 4em;
        line-height: 110px;
        margin: 0;
    }
    #jingle_loading p{
        color: #fff;
    }
    .c-red{
        color: #FF857E;
    }
    .line-tab{
        height: 10px;
        background: #F1F1F1;
    }
</style>
<header class="bg-green">
    <h1 class="title">实名认证</h1>
    <?php
    if ($register == 1) {
        ?>
        <nav class="right">
            <a href="<?php echo $urlUploadCert; ?>" data-target="link">
                稍后补充
            </a>
        </nav>
        <?php
    }
    ?>
</header>
<article class="active" data-scroll="true" data-realAuth="<?php echo $urlDoctorRealAuth; ?>">
    <div>
        <?php
        if ($register == 1) {
            ?>
            <div>
                <img src="http://static.mingyizhudao.com/147642938044480" class="w100">
            </div>
            <div class="grid text-center pb5">
                <div class="col-1 w25 c-red">
                    注册成功
                </div>
                <div class="col-1 w25 c-red">
                    基本信息
                </div>
                <div class="col-1 w25 c-red">
                    实名认证
                </div>
                <div class="col-1 w25">
                    医师认证
                </div>
            </div>
            <div class="line-tab"></div>
            <?php
        } else {
            ?>
            <div class="pad10">
                <p class="text-justify">为了确保您能正常使用名医主刀账户，请您配合完成以下认证:</p>
            </div>
            <?php
        }
        ?>
        <div class="container">
            <div class="text-left wrapper">
                <form id="idCard-form" data-url-uploadfile="<?php echo $ajaxDoctorRealAuth; ?>" data-url-return="<?php echo $urlReturn; ?>">
                    <input type="hidden" id="domain" value="http://7xp8ky.com1.z0.glb.clouddn.com">
                    <input type="hidden" id="uptoken_url" value="<?php echo $urlQiniuAjaxToken; ?>">
                </form>
            </div>
        </div>
        <div class="pl10">
            <div class="grid pt10 pr10 pb10 bb-gray">
                <div class="col-1">
                    <p>一张用户手持身份证正面的照片</p>
                    <p class="font-s12 c-red">*需看到完整头部，建议手拿身份证正面放于胸前</p>
                </div>
                <div class="col-0">
                    <div class="body">
                        <div class="">
                            <div id="container1" class="uploadTab">
                                <a class="btn btn-lg " id="pickfiles1" href="#">
                                    <span>
                                        <img src="" class="w70p h70">
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
                    <p class="font-s12 c-red">*只需拍身份证的正面即可</p>
                </div>
                <div class="col-0">
                    <div class="body">
                        <div class="">
                            <div id="container2" class="uploadTab">
                                <a class="btn btn-lg " id="pickfiles2" href="#">
                                    <span>
                                        <img src="" class="w70p h70">
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
                    <p class="font-s12 c-red">*只需拍身份证的反面即可</p>
                </div>
                <div class="col-0">
                    <div class="body">
                        <div class="">
                            <div id="container3" class="uploadTab">
                                <a class="btn btn-lg " id="pickfiles3" href="#">
                                    <span>
                                        <img src="" class="w70p h70">
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
            <?php
            if ($isVerified == 0) {
                echo '<a id="submitBtn" class="btn btn-block bg-green color-fff">下一步</a>';
            } else if ($isVerified == 1) {
                echo '<a id="submitBtn" class="btn btn-block bg-green color-fff">修改</a>';
            } else {
                echo '<a href="javascript:;" class="btn btn-block bg-gray">已审核</a>';
            }
            ?>
        </div>
    </div>
</article>
<div id="jingle_loading" style="display: none;" class="loading initLoading"><i class="icon spinner"></i><p>加载中...</p><div id="tag_close_popup" data-target="closePopup" class="icon cancel-circle"></div></div>
<div id="jingle_loading_mask" style="opacity: 0; display: none;"></div>
<div id="jingle_toast" class="toast" style="display: none;"><a href="#">请完善图片</a></div>