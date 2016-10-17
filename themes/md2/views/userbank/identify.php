<?php
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/jquery.formvalidate.min.1.0.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/card.min.1.0.js', CClientScript::POS_END);
?>
<?php
$card_id=Yii::app()->request->getQuery('card_id', '');
$this->setPageTitle('银行卡认证');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlLoadCity = $this->createUrl('/region/loadCities', array('state' => ''));
$urlAjaxCreate = $this->createUrl('userbank/ajaxCreate');
$urlCardList = $this->createUrl('userbank/cardList', array('addBackBtn' => 1));
$urlIdentify= $this->createUrl('userbank/identify', array('addBackBtn' => 1));
$this->show_footer = false;

?>
<style>
    #userbankIdentify_article .yzma{border:1px solid #FD8C47;width:130px;color:#FD8C47;border-radius:5px;}
</style>
<article id="userbankIdentify_article" class="active userbank_article" data-scroll="true">
    <div class="pt10">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'identify-form',
            'htmlOptions' => array('data-return-url' => $urlCardList, 'data-action-url' => $urlAjaxCreate),
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnType' => true,
                'validateOnDelay' => 500,
                'errorCssClass' => 'error',
            ),
            'enableAjaxValidation' => false,
        ));
        echo $form->hiddenField($model, 'is_default', array('name' => 'card[is_default]', 'value' => 0));
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
                    <div id="sendSmsCode" class="col-1 font-s12  vertical-center yzma pl5 mt5 mb5">获取验证码</div>
                </div>
            </div>
         
        </div>
        <div class="grid pt10 pl10 pr10">
            
            <div id="setDefault" class="col-0 font-s12" data-select="0"style="color:#A9A9BB;">
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
            }
        },
        messages: {
            'phone': {
                required: '请输入手机号码'
            },
            'DoctorBankCardAuthForm[verification]': {
                required: '请输入验证码'
            }
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
        var formdata = domForm.serializeArray();
        var requestUrl = domForm.attr('data-action-url');
        var returnUrl = domForm.attr('data-return-url');
        var dataArray = structure_formdata('identify', formdata);///////注意这个值（不一定对）
        var encryptContext = do_encrypt(dataArray, pubkey);
        var param = {param: encryptContext};
        $.ajax({
            type: 'post',
            url: requestUrl,
            data: param,
            success: function (data) {
                console.log(data);
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
      $("#sendSmsCode").click(function(){
         e.preventDefault();
        checkForm($(this));
      });
      function sendSmsCode(){
        
      }
    })
</script>
