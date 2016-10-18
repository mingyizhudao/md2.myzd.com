<?php
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/jquery.formvalidate.min.1.0.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/card.min.1.0.js', CClientScript::POS_END);
?>
<?php
$card_id=Yii::app()->request->getQuery('card_id', '');
$this->setPageTitle('银行卡认证');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlLoadCity = $this->createUrl('/region/loadCities', array('state' => ''));
$urlAjaxAuth = $this->createUrl('userbank/ajaxAuth');

$urlCardList = $this->createUrl('userbank/cardList', array('addBackBtn' => 1));
$urlIdentify= $this->createUrl('userbank/identify', array('addBackBtn' => 1));
$this->show_footer = false;
$authActionType = AuthSmsVerify::ACTION_USER_LOGIN;
$urlGetSmsVerifyCode = $this->createAbsoluteUrl('/auth/sendSmsVerifyCode');

// var_dump($urlDoctorValiCaptcha);die;///md2.myzd.com/mobiledoctor/doctor/valiCaptcha

?>
<style>
    #userbankIdentify_article .yzma{border:1px solid #FD8C47;width:136px;color:#FD8C47;border-radius:5px;background: #fff;}
</style>
<article id="userbankIdentify_article" class="active userbank_article" data-scroll="true">
    <div class="pt10">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'identify-form',
            'htmlOptions' => array('data-return-url' => $urlCardList, 'data-action-url' => $urlAjaxAuth),
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnType' => true,
                'validateOnDelay' => 500,
                'errorCssClass' => 'error',
            ),
            'enableAjaxValidation' => false,
        ));
         echo CHtml::hiddenField("smsverify[actionUrl]", $urlGetSmsVerifyCode);
         echo CHtml::hiddenField("smsverify[actionType]", $authActionType);
        ?>
        <div class="pl10 pr10 bg-white">
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        卡片类型：
                    </div>
                    <div class="col-1 pt5 pb5">
                        
                       <?php echo  $cardType;?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        手机号码：
                    </div>
                    <div class="col-1">
                        <?php echo $form->numberField($model, 'phone', array('name' => 'phone', 'placeholder' => '请输入您在办理该卡时预留的手机号码', 'class' => 'noPaddingInput')); ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        验证码：
                    </div>
                    <div class="col-1">
                        
                        <?php echo $form->textField($model, 'verification', array('placeholder' => '请输入验证码', 'class' => 'noPaddingInput')); ?>
                    </div>
                    <!-- <div id="btn-sendSmsCode" class="col-1 font-s12  vertical-center yzma pl5 mt5 mb5"disabled="disabled">获取验证码</div> -->
                    <button id="btn-sendSmsCode" class="col-1 font-s12  vertical-center yzma pl5 mt5 mb5">获取验证码</button>
                </div>
            </div>
         
        </div>
        <div class="grid pt10 pl10 pr10">
            
            <div id="setAgreement"name="setAgreement" class="col-0 font-s12" data-select="0"style="color:#A9A9BB;">
                <img src="http://static.mingyizhudao.com/146664844004130" class="w17p mr6 ">我已同意《名医主刀用户协议》
            </div>
        </div>
        <?php
        $this->endWidget();
        ?>
        <div class="pad10">
           <!--  <button id="submitBtn"  class="btn btn-full btn-yellow3">下一步</button> -->
           <button id="submitBtn"  class="btn btn-full btn-yellow3">下一步</button>
        </div>
    </div>
</article>
<script>
    $(function(){
        var domForm = $('#identify-form'),
        btnSubmit = $('#submitBtn');
        btnSubmit.click(function () {
            var phoneTest = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
            var phone = $('input[name="phone"]').val();
            if (!phoneTest.test(phone)) {
                J.showToast('手机号不正确', '', '1500');
                return;
            }
            var bool = validator.form();
            // console.log('aaa',bool);
            if (bool) {
              
                formAjaxSubmit();
            }
      });
        var validator = domForm.validate({
        rules: {
            
            'phone': {
                required: true
            },
            'DoctorBankCardAuthForm[verification]': {
                required: true
            },
            'setAgreement':{
                required:true
            }
        },
        messages: {
            'phone': {
                required: '请输入手机号码'
            },
            'DoctorBankCardAuthForm[verification]': {
                required: '请输入验证码'
            }
            // 'setAgreement':{
            //     required:''
            // }
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
//            element.parents('div.input').find('div.error').remove();
//            element.parents('div.input').find('div.errorMessage').remove();
//            error.appendTo(element.parents('.inputRow'));
            element.removeClass('error');
            element.addClass('error');
        }
    });
    function formAjaxSubmit(){
            disabled(btnSubmit);
        var requestUrl = domForm.attr('data-action-url');
        var returnUrl = domForm.attr('data-return-url');
        var code=$("#DoctorBankCardAuthForm_verification").val();
        var phone=$("#phone").val();
        $.ajax({
            type: 'get',
            url: requestUrl+'?code='+code+'&phone='+phone,
            success: function (data) {
                console.log('aa',data);
                if (data.status == 'ok') {
                    location.href = returnUrl+'/'+data.cardId;
                } else {
                    enable(btnSubmit);
                }
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                enable(btnSubmit);
                console.log(XmlHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            },
        });
        }

      $("#btn-sendSmsCode").click(function(e){
       e.preventDefault();
       var domForm = $("#identify-form");
       var domMobile = domForm.find("#phone");//手机号码
       var captchaCode = $('#DoctorBankCardAuthForm_verification').val();//验证码
       var mobile = domMobile.val();//手机号码
       var formdata = domForm.serializeArray();
       sendSmsVerifyCode($(this), domForm, mobile, captchaCode);
   });

      function sendSmsVerifyCode(domBtn, domForm, mobile, captchaCode) {
        $(".error").html(""); //删除错误信息
        var actionUrl = domForm.find("input[name='smsverify[actionUrl]']").val();//http://localhost/md2.myzd.com/auth/sendSmsVerifyCode
        var actionType = domForm.find("input[name='smsverify[actionType]']").val();//102
        var formData = new FormData();
        formData.append("AuthSmsVerify[mobile]", mobile);
        formData.append("AuthSmsVerify[actionType]", actionType);
        $.ajax({
            type: 'post',
            url: actionUrl ,
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            'success': function (data) {
                console.log('ad',data);
                if (data.status === true || data.status === 'ok') {
                    //domForm[0].reset();
                    buttonTimerStart(domBtn, 60000);
                }
                else {
                    console.log(data);
                    if (data.errors.captcha_code != undefined) {
                        $('#captchaCode').after('<div id="UserDoctorMobileLoginForm_captcha_code-error" class="error">' + data.errors.captcha_code + '</div>');
                    }
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
    
      $("#setAgreement").click(function(){
        $(this).find('img').attr('src','http://static.mingyizhudao.com/146665213709967');
      });
   
    })
</script>
