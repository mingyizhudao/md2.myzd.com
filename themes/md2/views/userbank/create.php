<?php
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/doctor/custom.min.1.0.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/doctor/jquery.formvalidate.min.1.1.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/card.min.1.0.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/qiniu/js/bankCreate.js?ts=' . time(), CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/card.js?ts=' . time(), CClientScript::POS_END);
?>
<?php
$this->setPageTitle('银行卡认证');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlLoadCity = $this->createUrl('/region/loadCities', array('state' => ''));
$urlAjaxCreate = $this->createUrl('userbank/ajaxCreate');
$urlIdentify = $this->createUrl('userbank/identify', array('card_id' => ''));
$urlCardList = $this->createUrl('userbank/cardList', array('addBackBtn' => 1));
$isFirst = Yii::app()->request->getQuery('isFirst', '');
$ajaxDoctorRealAuth = $this->createUrl('qiniu/ajaxDoctorRealAuth');
$urlQiniuAjaxToken = $this->createUrl('qiniu/ajaxDrToken');
// var_dump($user_id);die;
$urlDoctorRealAuth = 'http://file.mingyizhudao.com/api/loadrealauth?userId=' . $user_id;
$this->show_footer = false;
// var_dump($isFirst);die;
?>
<style>
    .w130p{width:130px;}
    .c-h{color:#A9A9A9;}
</style>
<article id="userbankCreate_article" class="active userbank_article" data-scroll="true"data-realAuth="<?php echo $urlDoctorRealAuth; ?>">
    <div class="pt10">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'card-form',
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
        <div class=" bg-white">
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0  vertical-center w130p pl10">
                        银行卡开户人：
                    </div>
                    <div class="col-1 pt5 pb5 pr10 text-right">
                        <?php echo $name; ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0  vertical-center pl10"style="width:100px;">
                        银行卡号：
                    </div>
                    <div class="col-1 pr10 ">
                        <?php echo $form->telField($model, 'card_no', array('name' => 'card[card_no]', 'placeholder' => '请输入您的银行卡号', 'class' => 'text-right', 'id' => 'bankCard')); ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0  vertical-center w130 pl10">
                        开户人身份证：
                    </div>
                    <div class="col-1 pr10 text-right">
                        <?php echo $form->textField($model, 'identification_card', array('name' => 'card[identification_card]', 'placeholder' => '请输入开户人身份证号', 'class' => 'text-right')); ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="pad10 font-s12 mb10"style="color:#FD8C47;">
            * 为了您可以成功提现，请填写正确有效的身份证号
        </div>
        <div class="pt10 bg-white">
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 vertical-center pl10"style="width:66px;">
                        开户行:
                    </div>
                    <div class="col-1 pr10">
                        <?php echo $form->textField($model, 'bank', array('name' => 'card[bank]', 'placeholder' => '例如:招商银行股份有限公司杭州分行', 'class' => 'text-right')); ?>
                    </div>
                </div>
            </div> 
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center pl10">
                        开户省：
                    </div>
                    <div class="col-1">
                        <?php
                        $model->state_id = null;
                        echo $form->dropDownList($model, 'state_id', $model->loadOptionsState(), array(
                            'name' => 'card[state_id]',
                            'prompt' => '请输入开户银行卡所在的省份',
                            'class' => 'text-right color-000',
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center pl10">
                        开户市：
                    </div>
                    <div class="col-1">                        
                        <?php
                        echo $form->dropDownList($model, 'city_id', $model->loadOptionsCity(), array(
                            'name' => 'card[city_id]',
                            'prompt' => '请输入开户银行卡所在的城市',
                            'class' => 'text-right color-000',
                        ));
                        ?>
                    </div>
                </div>
            </div>
            </div>
           <!-- 
         -->
        <?php
        $this->endWidget();
        ?>
      <!--   <div class="container">
            <div class="text-left wrapper">
                <form id="idCard-form" data-url-uploadfile="<?php echo $ajaxDoctorRealAuth; ?>" data-url-return="<?php echo $urlCardList; ?>">
                    <input type="hidden" id="domain" value="http://drcert.file.mingyizhudao.com">
                    <input type="hidden" id="uptoken_url" value="<?php echo $urlQiniuAjaxToken; ?>">
                </form>
            </div>
        </div>
        <div class="grid pt10 pl10 pr10 pb10 bg-white mt10">
                <div class="col-1 ">
                    <div >请上传一张银行卡的正面照</div>
                   
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
            </div> -->
        
        <div class="pad10 mt20">
            <!--  <button id="submitBtn"  class="btn btn-full btn-yellow3">下一步</button> -->
            <button id="submitBtn"  class="btn btn-full btn-yellow3"disabled="disabled">下一步</button>
        </div>

    </div>
</article>
<script>
    $(document).ready(function () {
      var isFirst='<?php echo $isFirst;?>';
      if(isFirst=='0'){
         J.customConfirm('',
                        '<div class="text-justify">尊敬的医生，因账户服务优化提升，烦请您再次填写正确的银行卡信息。谢谢您的谅解和支持。</div>',
                        '<a id="close" class=" color-green">我知道了</a>',
                        '',
                        function () {
                        },
                        function () {
                        });
         $("#close").click(function(){
                J.closePopup();
             });

      }

       document.addEventListener('input', function (e) {
            checkInput();
        });
       $("select#card_state_id").change(function () {
            $("select#card_city_id").attr("disabled", true);
            var stateId = $(this).val();
            var actionUrl = "<?php echo $urlLoadCity; ?>";// + stateId + "&prompt=选择城市";
            $.ajax({
                type: 'get',
                url: actionUrl,
                data: {'state': this.value, 'prompt': '选择城市'},
                cache: false,
                // dataType: "html",
                'success': function (data) {
                    $("select#card_city_id").html(data);
                    checkInput();
                    // jquery mobile fix.
                    captionText = $("select#card_city_id>option:first-child").text();
                    $("#card_city_id-button>span:first-child").text(captionText);
                },
                'error': function (data) {
                },
                complete: function () {
                    $("select#card_city_id").attr("disabled", false);
                    $("select#card_city_id").removeAttr("disabled");
                }
            });
            return false;
        });

        function checkInput() {
            var bool = true;
            $('input').each(function () {
                if ($(this).val() == '') {
                    bool = false;
                    return false;
                }
            });
            $('select').each(function () {
                if ($(this).val() == '') {
                    bool = false;
                    return false;
                }
            });
           
            if (bool) {
                $('#submitBtn').removeAttr('disabled');
            } else {
                $('#submitBtn').attr('disabled', 'disabled');
            }
        }
    });
</script>