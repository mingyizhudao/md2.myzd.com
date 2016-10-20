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
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/userUpload.js?ts=' . time(), CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/jquery-1.9.1.min.js?ts=' . time(), CClientScript::POS_END);

Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/common.min.1.1.css');
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/custom.min.1.0.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/md2/userUpload.min.1.1.js', CClientScript::POS_END);
?>
<?php
/*
 * $model UserDoctorProfileForm.
 */
$this->setPageID('pUserDoctorProfile');
$this->setPageTitle('医师执业证书');
$urlLogin = $this->createUrl('doctor/login');
$urlTermsPage = $this->createUrl('home/page', array('view' => 'terms'));
$urlLoadCity = $this->createUrl('/region/loadCities', array('state' => ''));
$urlSubmitProfile = $this->createUrl("doctor/ajaxProfile");
//$urlUploadFile = 'http://file.mingyizhudao.com/api/uploaddoctorcert'; //$this->createUrl("doctor/ajaxUploadCert");

$urlSendEmailForCert = $this->createUrl('doctor/sendEmailForCert');
$urlUploadFile = $this->createUrl('qiniu/ajaxDrCert');
$urlQiniuAjaxDrToken = $this->createUrl('qiniu/ajaxDrToken');


$urlSendEmailForCert = $this->createUrl('doctor/sendEmailForCert');
$register = Yii::app()->request->getQuery('register', 0);
if ($register == 0) {
    $urlReturn = $this->createUrl('doctor/account');
} else {
    $urlReturn = $this->createUrl('doctor/view');
}
if (isset($output['id'])) {
    $urlDoctorCerts = 'http://121.40.127.64:8089/api/loaddrcert?userId=' . $output['id']; //$this->createUrl('doctor/doctorCerts', array('id' => $output['id']));
    $urlDelectDoctorCert = 'http://file.mingyizhudao.com/api/deletedrcert?userId=' . $output['id'] . '&id='; //$this->createUrl('doctor/delectDoctorCert');
} else {
    $urlDoctorCerts = "";
    $urlDelectDoctorCert = "";
}
$register = Yii::app()->request->getQuery('register', 0);
$isVerified = $output['isVerified'];
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$this->show_footer = false;
?>
<?php
if ($register == 1) {
    ?>
    <header id="uploadCertAndroid_header" class="bg-green">
        <nav class="left">
            <a href="javascript:;" data-target="back">
                <div class="pl5">
                    <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                </div>
            </a>
        </nav>
        <h1 class="title">医师执业证书</h1>
        <?php
        if ($register == 1) {
            ?>
            <nav class="right">
                <a id="skip-btn" href="javascript:;">
                    稍后补充
                </a>
            </nav>
            <?php
        }
        ?>
    </header>
    <?php
}
?>
<article id="uploadCertAndroid_article" class="active android_article" data-scroll="true">
    <div class="form-wrapper">
        <?php
        if ($register == 1) {
            ?>
            <div>
                <img src="http://static.mingyizhudao.com/147643110651649" class="w100">
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
                <div class="col-1 w25 c-red">
                    医师认证
                </div>
            </div>
            <div class="line-tab"></div>
            <?php
        }
        ?>
        <div class="pad10">
            <h4 id="tip" class="hide">请完成实名认证,认证后开通名医主刀账户</h4>
            <p class="text-justify">请您上传医生执业证书的含有本人姓名，性别，身份证号，医师资格证书编码，执业类别，执业范围的那页</p>
            <div class="imglist mt10">
                <ul class="filelist"></ul>
            </div>
            <div class="clearfix"></div>
            <div class="form-wrapper mt20">
                <div class="uploadFileAndroid">
                    <div class="container">
                        <div class="text-left wrapper">
                            <form id="booking-form" data-url-uploadfile="<?php echo $urlUploadFile; ?>" data-url-return="<?php echo $urlReturn; ?>" data-url-sendEmail="<?php echo $urlSendEmailForCert; ?>">
                                <input id="userId" type="hidden" name="cert[user_id]" value="<?php echo $output['id']; ?>" />
                                <input type="hidden" id="domain" value="http://7xp8ky.com1.z0.glb.clouddn.com">
                                <input type="hidden" id="uptoken_url" value="<?php echo $urlQiniuAjaxDrToken; ?>">
                            </form>
                        </div>
                        <div class="body mt10">
                            <div class="text-center">
                                <div id="container">
                                    <?php
                                    if ($isVerified == 0) {
                                        ?>
                                        <a class="btn btn-default btn-lg" id="pickfiles" href="#">
                                            <span>选择图片</span>
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <a class="btn btn-default btn-lg showImg" id="pickfiles" href="#">
                                            <img src="">
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-12 mt10 hide">
                                <table class="table table-striped table-hover text-left" style="display:none">
                                    <tbody id="fsUploadProgress">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                        if ($isVerified == 0) {
                            echo '<div id="submitBtn" class="hide pt20">' .
                            '<a class="btn btn-full bg-green color-white">上传</a>' .
                            '</div>';
                        } else {
                            echo '<div id="submitBtn" class="pt20">' .
                            '<a class="btn btn-full bg-green color-white">修改</a>' .
                            '</div>';
                        }
                        ?>
                    </div>
                </div>
                <p class="text-center font-s12 pt30">示例:(各省卫生厅颁发的证书可能不同)</p>
                <div class="">
                    <div class="example">
                        <label class="color-red">示例:</label>
                        <div class="ui-grid-b">
                            <div class="ui-block-a">
                                <img src="http://static.mingyizhudao.com/146968477003421"/>
                            </div>
                            <div class="ui-block-b">
                                <span>或</span>
                            </div>
                            <div class="ui-block-c">
                                <img src="http://static.mingyizhudao.com/146968481261970"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
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
    <div id="successConfirm" class="confirm" style="top: 50%; left: 5%; right: 5%; border-radius: 3px; margin-top: -77.5px;">
        <div><div class="popup-title">提示</div>
            <div class="popup-content">
                <h4 class="text-center">提交成功，请耐心等待审核</h4>
                <div class="mt20">
                    <a data-target="link" href="<?php echo $urlReturn; ?>" class="btn btn-yes btn-block">确定</a>
                </div>
            </div>
        </div>
        <div id="tag_close_popup" data-target="closePopup" class="icon cancel-circle"></div>
    </div>
    <div id="errorConfirm" class="confirm" style="top: 50%; left: 5%; right: 5%; border-radius: 3px; margin-top: -77.5px;">
        <div><div class="popup-title">提示</div>
            <div class="popup-content">
                <h4 class="text-center">提交失败！</h4>
                <div class="confirmcontent"></div>
                <div class="mt20">
                    <a data-target="link" href="" class="btn btn-yes btn-block">确定</a>
                </div>
            </div>
        </div>
        <div id="tag_close_popup" data-target="closePopup" class="icon cancel-circle"></div>
    </div>
    <div id="jingle_toast" class="toast"><a href="#" class="font-s18">取消!</a></div>
    <div id="jingle_popup_mask" style="opacity: 0.3;"></div>
</article>
<div id="imgConfirm" class="confirm" style="left: 0px; right: 0px;">
    <div class="imgpopup">
        <img src="">
    </div>
    <div id="tag_close_popup" class="icon cancel-circle"></div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#skip-btn').click(function () {
            $('#jingle_toast').find('a').text('感谢注册!请记得上传照片以完成认证');
            $('#jingle_toast').show();
            setTimeout(function () {
                location.href = '<?php echo $urlReturn; ?>';
            }, 3000);
        });

        $("#imgConfirm #tag_close_popup").click(function () {
            $(this).parents(".confirm").hide();
        });
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
        urlDoctorCerts = "<?php echo $urlDoctorCerts; ?>";
        $.ajax({
            url: urlDoctorCerts,
            success: function (data) {
                setImg(data);
//                setImgHtml(data.results.files, isVerified);上一个版本
            }
        });
    });
    function setImg(data) {
        var files = data.results.files;
        var imgHtml = '<span>选择图片</span>';
        if (files && files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                imgHtml = '<img src="' + files[i].thumbnailUrl + '">';
            }
        }
        $('#pickfiles').html(imgHtml);
    }
    //上一个版本
    function setImgHtml(imgfiles, isVerified) {
        var innerHtml = '';
        if (imgfiles && imgfiles.length > 0) {
            for (var i = 0; i < imgfiles.length; i++) {
                var imgfile = imgfiles[i];
                var deleteHtml = '<div class="file-panel delete">删除</div>';
                if (isVerified) {
                    deleteHtml = '';
                }
                innerHtml +=
                        '<li id="' +
                        imgfile.id + '"><p class="imgWrap"><img src="' +
                        imgfile.thumbnailUrl + '" data-src="' +
                        imgfile.absFileUrl + '"></p>' + deleteHtml + '</li>';
            }
        } else {
            innerHtml += '';
        }
        $(".imglist .filelist").html(innerHtml);
        initDelete();
        $('.imgWrap img').click(function () {
            var imgUrl = $(this).attr("data-src");
            $("#imgConfirm img").attr('src', imgUrl);
            $("#imgConfirm").show();
        });
        $('.imgpopup').click(function () {
            $("#imgConfirm").hide();
        });
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
        J.showMask();
        urlDelectDoctorCert = '<?php echo $urlDelectDoctorCert ?>' + id;
        $.ajax({
            url: urlDelectDoctorCert,
            success: function (data) {
                if (data.status == 'ok') {
                    domLi.remove();
                    $("#jingle_toast a").text('删除成功!');
                    $("#jingle_toast").show();
                } else {
                    $("#errorConfirm .confirmcontent").text(data.error);
                    $("#errorConfirm").show();
                    $("#jingle_popup_mask").addClass("active");
                }
            },
            complete: function () {
                J.hideMask();
            }
        });
    }
    //发送邮件
    function sendEmailForCert() {
        $.ajax({
            url: '<?php echo $urlSendEmailForCert; ?>',
            type: 'post',
            success: function () {
                console.log("发送邮件成功");
            }
        });
    }
</script>