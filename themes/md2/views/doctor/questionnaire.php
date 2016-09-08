<?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/questionnaire.js?ts=' . time(), CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/jquery.formvalidate.min.1.0.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/md2/questionnaire.min.1.0.js', CClientScript::POS_END);
/*
 * $model UserDoctorMobileLoginForm.
 */
$this->setPageID('pCreateDoctorHz');
$this->setPageTitle('问卷调查');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlSubmit = $this->createUrl('doctor/ajaxQuestionnaire');
$urlReturn = $this->createUrl('doctor/drView', array('addBackBtn' => 1));
$this->show_footer = false;
?>
<article id="questionnaire_article" class="active bg-gray3" data-scroll="true">
    <div class="pad10">
        <div class="mb10">
            耽误您2分钟，请您回答一下问题，从而让我们更好的为您推荐优质患者：
        </div>
        <div class="mb20">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'docZz-form',
                'action' => $urlSubmit,
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'htmlOptions' => array('role' => 'form', 'autocomplete' => 'off', 'data-ajax' => 'false', 'data-url-return' => $urlReturn),
                'enableClientValidation' => false,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                    'validateOnType' => true,
                    'validateOnDelay' => 500,
                    'errorCssClass' => 'error',
                ),
                'enableAjaxValidation' => false,
            ));
            echo $form->hiddenField($zz_model, 'user_id', array('name' => 'DoctorZhuanzhenForm[user_id]'));
            echo $form->hiddenField($zz_model, 'is_join', array('name' => 'DoctorZhuanzhenForm[is_join]'));
            ?>
            <div class="bg-white br5">
                <div class="grid pad10">
                    <div class="col-1 pt5">您是否接受病人转诊？</div>
                    <div class="col-0">
                        <select id="zZSelect">
                            <option value="" <?php echo $zz_model->is_join == '' ? 'selected="true"' : ''; ?>>请选择</option>
                            <option value="1" <?php echo $zz_model->is_join == 1 ? 'selected="true"' : ''; ?>>会接受</option>
                            <option value="0" <?php echo $zz_model->is_join == 0 ? 'selected="true"' : ''; ?>>暂不接受</option>
                        </select>
                    </div>
                </div>
                <ul id="zZForm" class="list <?php echo $zz_model->is_join == 1 ? '' : 'hide'; ?>">
                    <li>
                        <label for="DoctorZhuanzhenForm_preferred_patient">1.对转诊病例有何要求?</label>
                        <?php echo $form->textArea($zz_model, 'preferred_patient', array('name' => 'DoctorZhuanzhenForm[preferred_patient]', 'placeholder' => '如没有特殊要求，可填"无"。', 'maxlength' => 500)); ?>
                        <?php echo $form->error($zz_model, 'preferred_patient'); ?>
                    </li>
                    <li>
                        <div>
                            <label>2.是否需要转诊费?</label>
                        </div>
                        <div class="grid mt10 numberFee">
                            <div class="col-1 checkFee p11 selectFee0">
                                <input type='radio' name='DoctorZhuanzhenForm[fee]' id='fee0' value='0'/>
                                <label for="fee0" class="ui-btn ui-corner-all">不需要</label>
                            </div>
                            <div class="col-1 checkFee p11 selectFee500">
                                <input type='radio' name='DoctorZhuanzhenForm[fee]' id='fee500' value='500'/>
                                <label for="fee500" class="ui-btn ui-corner-all">500元</label>
                            </div>
                            <div class="col-1 checkFee p11 selectFee1000">
                                <input type='radio' name='DoctorZhuanzhenForm[fee]' id='fee1000' value='1000'/>
                                <label for="fee1000" class="ui-btn ui-corner-all">1000元</label>
                            </div>
                        </div>
                        <div class="grid mt10 feeNum pl10">
                            <div id="otherFee">
                                <input type='radio' name='DoctorZhuanzhenForm[fee]' id='fee4' value=''/>
                            </div>
                            <label class="pl10" for="fee4">其他:</label>
                            <div class="w70p"><?php echo $form->textField($zz_model, 'fee', array('class' => 'zZCheckNumber', 'readonly' => 'ture', 'name' => 'fee')); ?></div>
                            <div class="">元</div>
                        </div>
                        <div class="clearfix mt10"></div>
                        <?php echo $form->error($zz_model, 'fee'); ?>
                    </li>
                    <li class="noborder">
                        <div>
                            <label>3.您最快多久能够安排床位?</label>   
                        </div>
                        <div class="mt10">
                            <span class="checkPrepDay p11 select-3d">
                                <input type='radio' name='DoctorZhuanzhenForm[prep_days]' id='3d' value='3d'/>
                                <label for="3d" class="ui-btn ui-corner-all">三天内</label>
                            </span>
                        </div>
                        <div class="mt20">
                            <span class="checkPrepDay p11 select-1w">
                                <input type='radio' name='DoctorZhuanzhenForm[prep_days]' id='1w' value='1w'/>
                                <label for="1w" class="ui-btn ui-corner-all">一周内</label>
                            </span>
                        </div>
                        <div class="mt20">
                            <span class="checkPrepDay p11 select-2w">
                                <input type='radio' name='DoctorZhuanzhenForm[prep_days]' id='2w' value='2w'/>
                                <label for="2w" class="ui-btn ui-corner-all">两周内</label>
                            </span>
                        </div>
                        <div class="mt20">
                            <span class="checkPrepDay p11 select-3w">
                                <input type='radio' name='DoctorZhuanzhenForm[prep_days]' id='3w' value='3w'/>
                                <label for="3w" class="ui-btn ui-corner-all">三周内</label>
                            </span>
                        </div>
                        <div class="clearfix mt15"></div>
                        <?php echo $form->error($zz_model, 'prep_days'); ?>
                    </li>
                </ul>
            </div>
            <?php $this->endWidget(); ?>
        </div>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'docHz-form',
            'action' => $urlSubmit,
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'htmlOptions' => array('role' => 'form', 'autocomplete' => 'off', 'data-ajax' => 'false', 'data-url-return' => $urlReturn),
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnType' => true,
                'validateOnDelay' => 500,
                'errorCssClass' => 'error',
            ),
            'enableAjaxValidation' => false,
        ));
        echo $form->hiddenField($hz_model, 'user_id', array('name' => 'DoctorHuizhenForm[user_id]'));
        echo $form->hiddenField($hz_model, 'is_join', array('name' => 'DoctorHuizhenForm[is_join]'));
        ?>
        <div class="bg-white br5">
            <div class="grid pad10">
                <div class="col-1 pt5">您是否接受病人会诊？</div>
                <div class="col-0">
                    <select id="hZSelect">
                        <option value="" <?php echo $hz_model->is_join == '' ? 'selected="true"' : ''; ?>>请选择</option>
                        <option value="1" <?php echo $hz_model->is_join == 1 ? 'selected="true"' : ''; ?>>会接受</option>
                        <option value="0" <?php echo $hz_model->is_join == 0 ? 'selected="true"' : ''; ?>>暂不接受</option>
                    </select>
                </div>
            </div>
            <ul id="hZForm" class="list <?php echo $hz_model->is_join == 1 ? '' : 'hide'; ?>">
                <li class="fee">
                    <label>1.几台手术您愿意外出会诊?</label>
                    <div class="grid mt10 numberSurgery">
                        <div class="col-1 checkSurgery p11 selectSurgery1">
                            <input type='radio' name='DoctorHuizhenForm[min_no_surgery]' id='surgery1d' class="surgery" value='1'/>
                            <label for="surgery1d" class="ui-btn ui-corner-all">1台</label>
                        </div>
                        <div class="col-1 checkSurgery p11 selectSurgery2">
                            <input type='radio' name='DoctorHuizhenForm[min_no_surgery]' id='surgery2d' class="surgery" value='2'/>
                            <label for="surgery2d" class="ui-btn ui-corner-all">2台</label>
                        </div>
                        <div class="col-1 checkSurgery p11 selectSurgery3">
                            <input type='radio' name='DoctorHuizhenForm[min_no_surgery]' id='surgery3d' class="surgery" value='3'/>
                            <label for="surgery3d" class="ui-btn ui-corner-all">3台</label>
                        </div>
                    </div>
                    <div class="mt10 grid surgeryNum pl10">
                        <div id="otherSurgery">
                            <input type='radio' name='DoctorHuizhenForm[min_no_surgery]' id='surgery4d' class="surgery" value=''/>
                        </div>
                        <label class="pl10" for="surgery4d">其他:</label>
                        <div class="w60p"><?php echo $form->textField($hz_model, 'min_no_surgery', array('class' => 'checkNumber', 'readonly' => 'true', 'name' => 'min_no_surgery')); ?></div>
                        <div class="">台</div>
                    </div>
                    <div id="surgery-error" class="error hide">请选择外出会诊要求</div>
                </li>
                <li class="fee">
                    <label for="DoctorHuizhenForm_fee">2.每台收费多少?</label>
                    <div class="grid mt10">
                        <div class="col-1">
                            <div>
                                <?php echo $form->numberField($hz_model, 'fee_min', array('name' => 'DoctorHuizhenForm[fee_min]', 'placeholder' => '最低额度')); ?>
                            </div>
                        </div>
                        <div class="col-0 text-center">
                            <span>~</span>
                        </div>
                        <div class="col-1">
                            <div>
                                <?php echo $form->numberField($hz_model, 'fee_max', array('name' => 'DoctorHuizhenForm[fee_max]', 'placeholder' => '最高额度')); ?>
                            </div>
                        </div>
                        <div class="col-0 text-center">
                            <span>元</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div>
                        <label>3.时间成本控制要求?<span class="color-green">(可多选)</span></label>
                    </div>
                    <div class="">
                        <div class="ui-block-a mt10">
                            <span class="checkDuration train3hSelect p11">
                                <input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='train3h' value='train3h'/>
                                <label for="train3h" class="ui-btn ui-corner-all">高铁3小时内</label>
                            </span>
                        </div>
                        <div class="ui-block-a mt20">
                            <span class="checkDuration train5hSelect p11">
                                <input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='train5h' value='train5h'/>
                                <label for="train5h" class="ui-btn ui-corner-all">高铁5小时内</label>
                            </span>
                        </div>
                        <div class="ui-block-a mt20">
                            <span class="checkDuration plane2hSelect p11">
                                <input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='plane2h' value='plane2h'/>
                                <label for="plane2h" class="ui-btn ui-corner-all">飞机2小时内</label>
                            </span>
                        </div>
                        <div class="ui-block-a mt20">
                            <div class="ui-block-b">
                                <span class="checkDuration plane3hSelect p11">
                                    <input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='plane3h' value='plane3h'/>
                                    <label for="plane3h" class="ui-btn ui-corner-all">飞机3小时内</label>
                                </span>
                            </div>
                        </div>
                        <div class="ui-block-a mt20">
                            <div class="ui-block-b">
                                <span class="checkDuration noneSelect p11">
                                    <input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='none' value='none'/>
                                    <label for="none" class="ui-btn ui-corner-all">无</label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix mt10"></div>
                    <?php echo $form->error($hz_model, 'travel_duration'); ?>  
                </li>
                <li>
                    <label for="DoctorHuizhenForm_week_days">4.一般周几方便外出会诊?<span class="color-green">(可多选)</span></label>
                    <div class="grid mt10">
                        <div class="col-1 w33 p11 checkDay select-1">
                            <?php //echo $form->checkBox($hz_model, 'week_days', array('name' => 'DoctorHuizhenForm[week_days]', 'id' => 'week_days1', 'value' => '1')); ?>
                            <input name="DoctorHuizhenForm[week_days]" id="week_days1" value="1" type="checkbox">
                            <label for="week_days1" class="ui-btn ui-corner-all">周一</label>
                        </div>
                        <div class="col-1 w33 p11 checkDay select-2">
                            <input name="DoctorHuizhenForm[week_days]" id="week_days2" value="2" type="checkbox">
                            <label for="week_days2" class="ui-btn ui-corner-all">周二</label>
                        </div>
                        <div class="col-1 w33 p11 checkDay select-3">
                            <input name="DoctorHuizhenForm[week_days]" id="week_days3" value="3" type="checkbox">
                            <label for="week_days3" class="ui-btn ui-corner-all">周三</label>
                        </div>
                    </div>
                    <div class="grid">
                        <div class="col-1 w33 p11 checkDay select-4">
                            <input name="DoctorHuizhenForm[week_days]" id="week_days4" value="4" type="checkbox">
                            <label for="week_days4" class="ui-btn ui-corner-all">周四</label>
                        </div>
                        <div class="col-1 w33 p11 checkDay select-5">
                            <input name="DoctorHuizhenForm[week_days]" id="week_days5" value="5" type="checkbox">
                            <label for="week_days5" class="ui-btn ui-corner-all">周五</label>
                        </div>
                        <div class="col-1 w33 p11 checkDay select-6">
                            <input name="DoctorHuizhenForm[week_days]" id="week_days6" value="6" type="checkbox">
                            <label for="week_days6" class="ui-btn ui-corner-all">周六</label>
                        </div>
                    </div>
                    <div class="grid">
                        <div class="col-1 w33 p11 checkDay select-7">
                            <input name="DoctorHuizhenForm[week_days]" id="week_days7" value="7" type="checkbox">
                            <label for="week_days7" class="ui-btn ui-corner-all">周日</label>
                        </div>
                        <div class="col-1 w33"></div>
                        <div class="col-1 w33"></div>
                    </div>
                </li>
                <li>
                    <?php //echo CHtml::activeLabel($hz_model, 'patients_prefer'); ?>
                    <label for="DoctorHuizhenForm_patients_prefer">5.您希望会诊什么样的病人?</label>
                    <?php echo $form->textArea($hz_model, 'patients_prefer', array('name' => 'DoctorHuizhenForm[patients_prefer]', 'placeholder' => '如没有特殊要求，可填"无"。', 'maxlength' => 500)); ?>
                    <?php echo $form->error($hz_model, 'patients_prefer'); ?>
                <li>
                    <?php //echo CHtml::activeLabel($hz_model, 'freq_destination'); ?>
                    <label for="DoctorHuizhenForm_freq_destination">6.您常去哪些地区或医院会诊?</label>
                    <?php echo $form->textArea($hz_model, 'freq_destination', array('name' => 'DoctorHuizhenForm[freq_destination]', 'placeholder' => '如没有特殊要求，可填"无"。', 'maxlength' => 500)); ?>
                    <?php echo $form->error($hz_model, 'freq_destination'); ?>
                </li>
                <li class="noborder">
                    <?php //echo CHtml::activeLabel($hz_model, 'destination_req'); ?>
                    <label for="DoctorHuizhenForm_action">7.您对手术地点/条件有何要求?</label>
                    <?php echo $form->textArea($hz_model, 'destination_req', array('name' => 'DoctorHuizhenForm[destination_req]', 'placeholder' => '', 'maxlength' => 500, 'placeholder' => '医院规模是否三甲/二甲、既往手术量、设备条件、手术室条件等等。')); ?>
                    <?php echo $form->error($hz_model, 'destination_req'); ?>
                </li>
            </ul>
        </div>
        <?php $this->endWidget(); ?>
        <div class="pt20 pb20">
            <button id="btnSubmit" class="btn btn-block bg-green">提交</button>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        //展开问卷
        document.addEventListener('input', function (e) {
            $('input[name="DoctorZhuanzhenForm[is_join]"]').val($('#zZSelect').val());
            $('input[name="DoctorHuizhenForm[is_join]"]').val($('#hZSelect').val());
            if ($('#zZSelect').val() == 1) {
                $('#zZForm').removeClass('hide');
            } else {
                $('#zZForm').addClass('hide');
            }
            if ($('#hZSelect').val() == 1) {
                $('#hZForm').removeClass('hide');
            } else {
                $('#hZForm').addClass('hide');
            }
        });

        //外出会诊
        var surgery = '<?php echo $hz_model->min_no_surgery; ?>';
        if (surgery == '1') {
            $(".checkNumber").val("");
            $('.selectSurgery1').html("<input type='radio' name='DoctorHuizhenForm[min_no_surgery]' id='surgery1d' class='surgery' value='1' checked='checked'/><label for='surgery1d' class='ui-btn ui-corner-all'> 1台</label>");
        } else if (surgery == '2') {
            $(".checkNumber").val("");
            $('.selectSurgery2').html("<input type='radio' name='DoctorHuizhenForm[min_no_surgery]' id='surgery2d' class='surgery' value='2' checked='checked'/><label for='surgery2d' class='ui-btn ui-corner-all'> 2台</label>");
        } else if (surgery == '3') {
            $(".checkNumber").val("");
            $('.selectSurgery3').html("<input type='radio' name='DoctorHuizhenForm[min_no_surgery]' id='surgery3d' class='surgery' value='3' checked='checked'/><label for='surgery3d' class='ui-btn ui-corner-all'> 3台</label>");
        } else if (surgery != '') {
            $('#otherSurgery').html("<input type='radio' name='DoctorHuizhenForm[min_no_surgery]' id='surgery4d' class='surgery' value='' checked='checked'/>");
        }

        //时间成本
        var travel = '<?php echo $hz_model->travel_duration; ?>';
        if (travel != '') {
            var travelList = travel.split(',');
            if (travelList.length > 0) {
                for (var i = 0; i < travelList.length; i++) {
                    if (travelList[i] == "train3h") {
                        $('.train3hSelect').html("<input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='train3h' value='train3h' checked='checked'/><label for='train3h' class='ui-btn ui-corner-all'> 高铁3小时内</label>");
                    }
                    if (travelList[i] == "plane2h") {
                        $('.plane2hSelect').html("<input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='plane2h' value='plane2h' checked='checked'/><label for='plane2h' class='ui-btn ui-corner-all'> 飞机2小时内</label>");
                    }
                    if (travelList[i] == "train5h") {
                        $('.train5hSelect').html("<input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='train5h' value='train5h' checked='checked'/><label for='train5h' class='ui-btn ui-corner-all'> 高铁5小时内</label>");
                    }
                    if (travelList[i] == "plane3h") {
                        $('.plane3hSelect').html("<input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='plane3h' value='plane3h' checked='checked'/><label for='plane3h' class='ui-btn ui-corner-all'> 飞机3小时内</label>");
                    }
                    if (travelList[i] == "none") {
                        $('.noneSelect').html("<input type='checkbox' name='DoctorHuizhenForm[travel_duration]' id='none' value='none' checked='checked'/><label for='none' class='ui-btn ui-corner-all'> 无</label>");
                    }
                }
            }
        }

        //方便会诊时间
        var weekDays = '<?php echo $hz_model->week_days; ?>';
        if (weekDays != '') {
            var dayList = weekDays.split(',');
            if (dayList.length > 0) {
                for (var i = 0; i < dayList.length; i++) {
                    if (dayList[i] == "1") {
                        $('.select-1').html('<input name="DoctorHuizhenForm[week_days]" id="week_days1" value="1" type="checkbox" checked="checked"><label for="week_days1" class="ui-btn ui-corner-all"> 周一</label>');
                    }
                    if (dayList[i] == "2") {
                        $('.select-2').html('<input name="DoctorHuizhenForm[week_days]" id="week_days2" value="2" type="checkbox" checked="checked"><label for="week_days2" class="ui-btn ui-corner-all"> 周二</label>');
                    }
                    if (dayList[i] == "3") {
                        $('.select-3').html('<input name="DoctorHuizhenForm[week_days]" id="week_days3" value="3" type="checkbox" checked="checked"><label for="week_days3" class="ui-btn ui-corner-all"> 周三</label>');
                    }
                    if (dayList[i] == "4") {
                        $('.select-4').html('<input name="DoctorHuizhenForm[week_days]" id="week_days4" value="4" type="checkbox" checked="checked"><label for="week_days4" class="ui-btn ui-corner-all"> 周四</label>');
                    }
                    if (dayList[i] == "5") {
                        $('.select-5').html('<input name="DoctorHuizhenForm[week_days]" id="week_days5" value="5" type="checkbox" checked="checked"><label for="week_days5" class="ui-btn ui-corner-all"> 周五</label>');
                    }
                    if (dayList[i] == "6") {
                        $('.select-6').html('<input name="DoctorHuizhenForm[week_days]" id="week_days6" value="6" type="checkbox" checked="checked"><label for="week_days6" class="ui-btn ui-corner-all"> 周六</label>');
                    }
                    if (dayList[i] == "7") {
                        $('.select-7').html('<input name="DoctorHuizhenForm[week_days]" id="week_days7" value="7" type="checkbox" checked="checked"><label for="week_days7" class="ui-btn ui-corner-all"> 周日</label>');
                    }
                }
            }
        }

        //转诊费
        var fee = '<?php echo $zz_model->fee; ?>';
        if (fee == '0') {
            $(".zZCheckNumber").val("");
            $('.selectFee0').html("<input type='radio' name='DoctorZhuanzhenForm[fee]' id='fee0' value='0' checked='checked'/><label for='fee0' class='ui-btn ui-corner-all'> 不需要</label>");
        } else if (fee == '500') {
            $(".zZCheckNumber").val("");
            $('.selectFee500').html("<input type='radio' name='DoctorZhuanzhenForm[fee]' id='fee500' value='500' checked='checked'/><label for='fee500' class='ui-btn ui-corner-all'> 500元</label>");
        } else if (fee == '1000') {
            $(".zZCheckNumber").val("");
            $('.selectFee1000').html("<input type='radio' name='DoctorZhuanzhenForm[fee]' id='fee1000' value='1000' checked='checked'/><label for='fee1000' class='ui-btn ui-corner-all'> 1000元</label>");
        } else if (fee != '') {
            $('#otherFee').html("<input type='radio' name='DoctorZhuanzhenForm[fee]' id='fee4' value='' checked='checked'/>");
        }

        //安排床位时间
        var days = '<?php echo $zz_model->prep_days; ?>';
        if (days != '') {
            if (days == '3d') {
                $('.select-3d').html("<input type='radio' name='DoctorZhuanzhenForm[prep_days]' id='3d' value='3d' checked='checked'/><label for='3d' class='ui-btn ui-corner-all'> 三天内</label>");
            }
            if (days == '1w') {
                $('.select-1w').html("<input type='radio' name='DoctorZhuanzhenForm[prep_days]' id='1w' value='1w' checked='checked'/><label for='1w' class='ui-btn ui-corner-all'> 一周内</label>");
            }
            if (days == '2w') {
                $('.select-2w').html("<input type='radio' name='DoctorZhuanzhenForm[prep_days]' id='2w' value='2w' checked='checked'/><label for='2w' class='ui-btn ui-corner-all'> 两周内</label>");
            }
            if (days == '3w') {
                $('.select-3w').html("<input type='radio' name='DoctorZhuanzhenForm[prep_days]' id='3w' value='3w' checked='checked'/><label for='3w' class='ui-btn ui-corner-all'> 三周内</label>");
            }
        }

    });
</script>