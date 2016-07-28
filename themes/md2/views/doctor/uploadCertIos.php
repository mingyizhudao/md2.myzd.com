<?php
Yii::app()->clientScript->registerCssFile('http://myzd.oss-cn-hangzhou.aliyuncs.com/static/mobile/js/webuploader/css/webuploader.css');
Yii::app()->clientScript->registerCssFile('http://static.mingyizhudao.com/webuploader.custom.1.0.css');
Yii::app()->clientScript->registerScriptFile('http://myzd.oss-cn-hangzhou.aliyuncs.com/static/mobile/js/webuploader/js/webuploader.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/doctorprofile.min.1.1.js', CClientScript::POS_END);
?>
<?php
/*
 * $model UserDoctorProfileForm.
 */
$this->setPageID('pUserDoctorProfile');
$this->setPageTitle('实名认证');
$urlLogin = $this->createUrl('doctor/login');
$urlTermsPage = $this->createUrl('home/page', array('view' => 'terms'));
$urlLoadCity = $this->createUrl('/region/loadCities', array('state' => ''));
$urlSubmitProfile = $this->createUrl("doctor/ajaxProfile");
$urlUploadFile = 'http://121.40.127.64:8089/api/uploaddoctorcert'; //$this->createUrl("doctor/ajaxUploadCert");
$urlSendEmailForCert = $this->createUrl('doctor/sendEmailForCert');
$urlReturn = $this->createUrl('doctor/view');

if (isset($output['id'])) {
    $urlDoctorCerts = 'http://121.40.127.64:8089/api/loaddrcert?userId=' . $output['id']; //$this->createUrl('doctor/doctorCerts', array('id' => $output['id']));
    $urlDelectDoctorCert = 'http://121.40.127.64:8089/api/deletedrcert?userId=' . $output['id'] . '&id='; //$this->createUrl('doctor/delectDoctorCert');
} else {
    $urlDoctorCerts = "";
    $urlDelectDoctorCert = "";
}

$urlResImage = Yii::app()->theme->baseUrl . "/images/";
?>
<article class="active pad1" data-scroll="true">
    <div class="form-wrapper">
        <form id="doctor-form" data-url-uploadfile="<?php echo $urlUploadFile; ?>" data-url-return="<?php echo $urlReturn; ?>" data-url-sendEmail="<?php echo $urlSendEmailForCert; ?>" >
            <input id="doctorId" type="hidden" name="doctor[id]" value="<?php echo $output['id']; ?>" />                
        </form>
        <div class="">
            <h4 id="tip" class="hide">请完成实名认证,认证后开通名医主刀账户</h4>
            <div class="">
                <label>上传医生职业证书或者手持工牌照</label>
            </div>
            <div>&nbsp;&nbsp;请确保图片内容清晰可见</div>
            <?php
            if ($output['isVerified']) {
                echo '<p class="color-red mt10">您已通过实名认证,信息不可以再修改。</p>';
            }
            ?>
            <div id="uploader" class="mt20">
                <div class="imglist">
                    <ul class="filelist"></ul>
                </div>
                <div class="queueList">
                    <div id="dndArea" class="placeholder">
                        <!-- btn 选择图片 -->
                        <div id="filePicker"></div>
                    <!-- <p>或将照片拖到这里，单次最多可选10张</p>-->
                    </div>
                </div>
                <div class="statusBar clearfix" style="display:none;">
                    <div class="progress" style="display: none;">
                        <span class="text">0%</span>
                        <span class="percentage" style="width: 0%;"></span>
                    </div>
                    <div class="info">共0张（0B），已上传0张</div>
                    <div class="">
                        <!-- btn 继续添加 -->
                        <div id="filePicker2" class="pull-right"></div>                          

                    </div>
                    <div class="ui-field-contain mt20">
<!--                                <input id="btnSubmit" class="statusBar uploadBtn btn btn-yes btn-block" type="button" name="yt0" value="提交">-->
                        <a id="btnSubmit" class="statusBar uploadBtn btn btn-yes btn-full ml0">提交</a>
                        <!--                <button id="btnSubmit" type="button" class="statusBar state-pedding">提交</button>-->
                    </div>
                </div>
                <!--一开始就显示提交按钮就注释上面的提交 取消下面的注释 -->
                <div class="divider mt20"></div>
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
</article>
<script type="text/javascript">
    $(document).ready(function () {
        var isVerified = '<?php echo $output['isVerified']; ?>';
        if (isVerified) {
            $(".queueList").hide();
        }
        urlDoctorCerts = "<?php echo $urlDoctorCerts; ?>";
        $.ajax({
            url: urlDoctorCerts,
            success: function (data) {
                setImgHtml(data.results.files, isVerified);
            }
        });
    });
    function setImgHtml(imgfiles, isVerified) {
        var innerHtml = '';
        if (imgfiles && imgfiles.length > 0) {
            for (i = 0; i < imgfiles.length; i++) {
                imgfile = imgfiles[i];
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
            if (!'<?php echo $output['isVerified']; ?>') {
                $('#tip').html('您已提交实名认证照片，名医助手正在审核中，请您耐心等待！');
                $('#tip').removeClass('hide');
            }
        } else {
            innerHtml += '';
        }
        $(".imglist .filelist").html(innerHtml);
        initDelete();
        $('.imgWrap img').tap(function () {
            var imgUrl = $(this).attr("data-src");
            J.popup({
                html: '<div class="imgpopup"><img src="' + imgUrl + '"></div>',
                pos: 'top-second',
                showCloseBtn: true
            });
            $('.imgpopup').tap(function () {
                J.closePopup();
            });
        });
    }
    function initDelete() {
        $('.imglist .delete').tap(function () {
            domLi = $(this).parents("li");
            id = domLi.attr("id");
            J.confirm('提示', '确定删除这张图片?', function () {
                deleteImg(id, domLi);
            }, function () {
                J.showToast('取消', '', 1000);
            });
        });
    }
    function deleteImg(id, domLi) {
        J.showMask();
        urlDelectDoctorCert = '<?php echo $urlDelectDoctorCert ?>' + id;
        $.ajax({
            url: urlDelectDoctorCert,
            beforeSend: function () {

            },
            success: function (data) {
                if (data.status == 'ok') {
                    domLi.remove();
                    J.showToast('删除成功!', '', 1000);
                } else {
                    //console.log(data.error);
                    J.showToast(data.error, '', 3000);
                }
            },
            complete: function () {
                J.hideMask();
            }
        });
    }
</script>
