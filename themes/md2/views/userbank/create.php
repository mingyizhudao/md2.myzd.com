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
// var_dump($model);die;
?>
<style>
    .w130p{width:130px;}
    .c-h{color:#A9A9A9;}
</style>
<article id="userbankCreate_article" class="active userbank_article" data-scroll="true">
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
                    <div class="col-1 pt5 pb5 pr10">
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
                        <?php echo $form->telField($model, 'card_no', array('name' => 'card[card_no]', 'placeholder' => '请输入您的银行卡号', 'class' => 'noPaddingInput', 'id' => 'bankCard')); ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0  vertical-center w130 pl10">
                        开户人身份证：
                    </div>
                    <div class="col-1 pr10 text-right">
                        <?php echo $form->textField($model, 'identification_card', array('name' => 'card[identification_card]', 'placeholder' => '请输入开户人身份证号', 'class' => 'noPaddingInput')); ?>
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
                        <?php echo $form->textField($model, 'bank', array('name' => 'card[bank]', 'placeholder' => '例如:招商银行股份有限公司杭州分行', 'class' => 'noPaddingInput')); ?>
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
                            'class' => '',
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
                            'class' => '',
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $this->endWidget();
        ?>
        <div class="pad10 mt20">
            <!--  <button id="submitBtn"  class="btn btn-full btn-yellow3">下一步</button> -->
            <button id="submitBtn"  class="btn btn-full btn-yellow3"disabled="disabled">下一步</button>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        document.addEventListener('input', function (e) {
            checkInput();
        });
        $('#bankCard').on('input', function () {
            var $this = $(this);
            var v = $this.val();
            /\S{5}/.test(v) && $this.val(v.replace(/[^(\d)]/g, "").replace(/(.{4})/g, "$1 "));
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
            // alert(bool);
            if (bool) {
                $('#submitBtn').removeAttr('disabled');
            } else {
                $('#submitBtn').attr('disabled', 'disabled');
            }
        }
    });
</script>