<?php
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/jquery.formvalidate.min.1.0.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/card.min.1.0.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/card.js?ts=' . time(), CClientScript::POS_END);
?>
<?php
$this->setPageTitle('银行卡认证');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlLoadCity = $this->createUrl('/region/loadCities', array('state' => ''));
$urlAjaxCreate = $this->createUrl('userbank/ajaxCreate');
$urlIdentify = $this->createUrl('userbank/identify', array('card_id' => ''));
// var_dump($urlIdentify);die;
$urlCardList = $this->createUrl('userbank/cardList', array('addBackBtn' => 1));
$this->show_footer = false;
// var_dump($name);die;
?>
<article id="userbankCreate_article" class="active userbank_article" data-scroll="true">
    <div class="pt10">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'card-form',
            'htmlOptions' => array('data-return-url' => $urlIdentify, 'data-action-url' => $urlAjaxCreate),
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
                        持卡人：
                    </div>
                    <div class="col-1 pt5 pb5">
                        <?php echo $name; ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        银行卡号：
                    </div>
                    <div class="col-1">
                        <?php echo $form->telField($model, 'card_no', array('name' => 'card[card_no]', 'placeholder' => '请输入银行卡号', 'class' => 'noPaddingInput', 'id' => 'bankCard')); ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="pad10 font-s12"style="color:#FD8C47;">
            * 为了您的资金账户安全，只能绑定持卡人本人的银行卡
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
    $(document).ready(function () {
        $('#bankCard').on('input', function () {
            var $this = $(this);
            var v = $this.val();
            /\S{5}/.test(v) && $this.val(v.replace(/[^(\d)]/g, "").replace(/(.{4})/g, "$1 "));
        });
    });
</script>