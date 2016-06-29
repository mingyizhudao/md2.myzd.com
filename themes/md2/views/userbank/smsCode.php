<?php
$this->setPageTitle('设置密码');
$urlAjaxSetKey = $this->createUrl('userbank/ajaxSetKey');
$urlCreate = $this->createUrl('userbank/create');
$urlSmsCode = $this->createUrl('userbank/smsCode');
$urlGetSmsVerifyCode = $this->createAbsoluteUrl('/auth/sendSmsVerifyCode');
$urlAjaxVerifyCode = $this->createUrl('userbank/ajaxVerifyCode', array('code' => ''));
$urlViewSetKey = $this->createUrl('userbank/ViewSetKey');
$authActionType = AuthSmsVerify::ACTION_USER_BANK;
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
?>
<div id="section_container" <?php echo $this->createPageAttributes(); ?> >
    <section id="main_section" class="active">
        <article id="smsCode_article" class="active" data-scroll="true">
            <div>
                <?php
                echo CHtml::hiddenField("smsverify[actionUrl]", $urlGetSmsVerifyCode);
                echo CHtml::hiddenField("smsverify[actionType]", $authActionType);
                ?>
                <div class="pt20 pl10">
                    <?php echo '请输入' . $mobile . '收到的短信验证码' ?>
                </div>
                <div class="grid mt20 pt5 pb5 bg-white">
                    <div class="col-1">
                        <input name="smsCode" type="number" placeholder="请输入短信验证码">
                    </div>
                    <div class="col-0 w95p text-center bl-gray">
                        <button id="btn-sendSmsCode" class="btn btn-sendSmsCode ui-corner-all ui-shadow">获取验证码</button>
                    </div>
                </div>
                <div class="pt30 pl10 pr10">
                    <a id="submitBtn" href="javascript:;" class="btn btn-full btn-yellow2">下一步</a>
                </div>
            </div>
        </article>
    </section>
</div>
<script>
    $(document).ready(function () {
        timerId = null;
        $("#btn-sendSmsCode").click(function (e) {
            e.preventDefault();
            sendSmsVerifyCode($(this),<?php echo $mobile; ?>);
        });

        $('#submitBtn').click(function () {
            var btnSubmit = $('#submitBtn');
            var code = $('input[name="smsCode"]').val();
            if (code.length != 6) {
                J.showToast('请输入六位短信验证码', '', '1500');
                return;
            }
            disabled(btnSubmit);
            $.ajax({
                url: '<?php echo $urlAjaxVerifyCode; ?>/' + code,
                success: function (data) {
                    if (data.status == 'ok') {
                        location.href = '<?php echo $urlViewSetKey ?>';
                        enable(btnSubmit);
                    } else {
                        enable(btnSubmit);
                        clearInterval(timerId);
                        timerId = null;
                        $("#btn-sendSmsCode").html("重新发送");
                        $("#btn-sendSmsCode").attr("disabled", false).removeAttr("disabled");
                        J.showToast(data.errors, '', '1500');
                    }
                },
                error: function (XmlHttpRequest, textStatus, errorThrown) {
                    enable(btnSubmit);
                    console.log(XmlHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });

        function sendSmsVerifyCode(domBtn, mobile) {
            $(".error").html("");//删除错误信息
            var actionUrl = $("input[name='smsverify[actionUrl]']").val();
            var actionType = $("input[name='smsverify[actionType]']").val();
            var formData = new FormData();
            formData.append("AuthSmsVerify[mobile]", mobile);
            formData.append("AuthSmsVerify[actionType]", actionType);
            $.ajax({
                type: 'post',
                url: actionUrl,
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                'success': function (data) {
                    if (data.status === true || data.status === 'ok') {
                        //domForm[0].reset();
                        buttonTimerStart(domBtn, 60000);
                    }
                    else {
                        console.log(data);
                    }
                },
                'error': function (data) {
                    console.log(data);
                },
                'complete': function () {
                }
            });
        }
        function buttonTimerStart(domBtn, timer) {
            timer = timer / 1000 //convert to second.
            var interval = 1000;
            var timerTitle = '秒后重发';
            domBtn.attr("disabled", true);
            domBtn.html(timer + timerTitle);

            timerId = setInterval(function () {
                timer--;
                if (timer > 0) {
                    domBtn.html(timer + timerTitle);
                } else {
                    clearInterval(timerId);
                    timerId = null;
                    domBtn.html("重新发送");
                    domBtn.attr("disabled", false).removeAttr("disabled");
                    ;
                }
            }, interval);
        }
        //disabledBtn
        function disabled(btnSubmit) {
            J.showMask('加载中...');
            btnSubmit.attr("disabled", true);
        }
        //enableBtn
        function enable(btnSubmit) {
            J.hideMask();
            btnSubmit.removeAttr("disabled");
        }
    });
</script>