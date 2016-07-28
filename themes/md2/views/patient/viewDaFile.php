<?php
/*
 * $model PatientMRForm.
 */
$this->setPageTitle('出院小结');
$id = Yii::app()->request->getQuery('id', '');
$patientId = Yii::app()->request->getQuery('id', '');
$uploadDAFile = $this->createUrl('patient/uploadDAFile', array('id' => $id, 'bookingid' => $patientId));
$user = $this->loadUser();
if ($patientId != '') {
    $urlPatientMRFiles = 'http://121.40.127.64:8089/api/loadpatientmr?userId=' . $user->id . '&patientId=' . $patientId . '&reportType=da';
    $urldelectPatientMRFile = 'http://121.40.127.64:8089/api/deletepatientmr?userId=' . $user->id . '&id=';
} else {
    $urlPatientMRFiles = "";
    $urldelectPatientMRFile = "";
}
?>
<style>
    #jingle_popup{
        top: 0px!important;
    }
</style>
<article id="" class="active" data-scroll="true">
    <div class="pad10">
        <div>
            感谢您已成功提交出院小结！
        </div>
        <div class="imglist mt10">
            <ul class="filelist"></ul>
        </div>
        <div class="clearfix"></div>
        <div class="font-s12 color-gray pb30">
            名医助手会对出院小结进行审核，如与订单不符，将会联系您，或者欢迎您致电名医热线400-6277-120
        </div>
        <div class="pt20 pb50 bt-gray">
            <a href="<?php echo $uploadDAFile; ?>" class="btn btn-block bg-green color-white">重新上传</a>
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
        $.ajax({
            url: urlPatientMRFiles,
            success: function (data) {
                setImgHtml(data.results.files);
            }
        });
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
        $('.imgWrap').tap(function () {
            var imgUrl = $(this).find("img").attr("data-src");
            J.popup({
                html: '<div class="imgpopup"><img src="' + imgUrl + '"></div>',
                pos: 'top-second',
                showCloseBtn: true
            });
            $('.imgpopup').tap(function () {
                J.closePopup();
            });
        });
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
        var urlDelectDoctorCert = '<?php echo $urldelectPatientMRFile ?>' + id;
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
