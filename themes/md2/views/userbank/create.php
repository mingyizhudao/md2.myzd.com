<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery.formvalidate.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/card.min.js?ts=' . time(), CClientScript::POS_END);
?>
<?php
$this->setPageTitle('添加银行卡信息');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlLoadCity = $this->createUrl('/region/loadCities', array('state' => ''));
$urlAjaxCreate = $this->createUrl('userbank/ajaxCreate');
$urlCardList = $this->createUrl('userbank/cardList', array('addBackBtn' => 1));
?>
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
        <div class="pl10 pr10 bg-white">
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        持卡人
                    </div>
                    <div class="col-1">
                        <?php echo $form->textField($model, 'name', array('name' => 'card[name]', 'placeholder' => '请输入姓名', 'class' => 'noPaddingInput')); ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        卡号
                    </div>
                    <div class="col-1">
                        <?php echo $form->numberField($model, 'card_no', array('name' => 'card[card_no]', 'placeholder' => '请输入银行卡号', 'class' => 'noPaddingInput')); ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        省份
                    </div>
                    <div class="col-1">
                        <?php
                        $model->state_id = null;
                        echo $form->dropDownList($model, 'state_id', $model->loadOptionsState(), array(
                            'name' => 'card[state_id]',
                            'prompt' => '选择省份',
                            'class' => '',
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        城市
                    </div>
                    <div class="col-1">                        
                        <?php
                        echo $form->dropDownList($model, 'city_id', $model->loadOptionsCity(), array(
                            'name' => 'card[city_id]',
                            'prompt' => '选择城市',
                            'class' => '',
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        开户银行
                    </div>
                    <div class="col-1">
                        <?php echo $form->textField($model, 'bank', array('name' => 'card[bank]', 'placeholder' => '选择开户银行', 'class' => 'noPaddingInput')); ?>
                    </div>
                </div>
            </div>
            <div class="inputRow">
                <div class="grid bb-gray">
                    <div class="col-0 w90p vertical-center">
                        支行名称
                    </div>
                    <div class="col-1">
                        <?php echo $form->textField($model, 'subbranch', array('name' => 'card[subbranch]', 'placeholder' => '选择银行支行', 'class' => 'noPaddingInput')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid pt10 pl10 pr10">
            <div class="col-1"></div>
            <div id="setDefault" class="col-0" data-select="0">
                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146664844004130" class="w17p mr6">设为默认
            </div>
        </div>
        <?php
        $this->endWidget();
        ?>
        <div class="pad10">
            <button id="submitBtn" class="btn btn-full btn-yellow3">保存</button>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        $('#setDefault').click(function () {
            if ($(this).attr('data-select') == 0) {
                $(this).attr('data-select', 1);
                $('input[name="card[is_default]"]').val(1);
                $(this).html('<img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146665213709967" class="w17p mr6">设为默认');
            } else {
                $(this).attr('data-select', 0);
                $('input[name="card[is_default]"]').val(0);
                $(this).html('<img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146664844004130" class="w17p mr6">设为默认');
            }
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
    });
</script>