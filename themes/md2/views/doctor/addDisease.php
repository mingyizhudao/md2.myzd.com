<?php
$this->setPageTitle('疾病信息');
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/md2/addDisease.min.1.0.js', CClientScript::POS_END);
$diseaseSearch = $this->createUrl('doctor/diseaseSearch');
$diseaseName = Yii::app()->request->getQuery('diseaseName', '');
$diseaseId = Yii::app()->request->getQuery('diseaseId', '');
$savepatientdisease = $this->createUrl('doctor/savepatientdisease');
//$patientBookingCreate = $this->createUrl('patientbooking/create', array('pid' => ''));
$id = Yii::app()->request->getQuery('id', '');
$sourceReturn = Yii::app()->request->getQuery('returnUrl', '');
$urlReturn = $this->createUrl('patient/uploadMRFile', array('type' => 'create'));
$addDisease = $this->createUrl('doctor/addDisease');
?>
<article id="addDisease_article" class="active" data-scroll="true">
    <div class="pad10">
        <form id="patient-form" data-url-action="<?php echo $savepatientdisease; ?>" data-url-return="<?php echo $urlReturn; ?>" data-source-return="<?php echo $sourceReturn; ?>">
            <input type="hidden" name="patient[id]" value="<?php echo $id; ?>">
            <input type="hidden" name="patient[disease_name]" value="<?php echo $diseaseName; ?>">
            <div class="pt20">
                请选择患者疾病名称：
            </div>
            <div>
                <div class="selectDisease grid">
                    <?php
                    if ($diseaseName != '') {
                        echo '<div class="col-1 color-black">' . $diseaseName . '</div>';
                    } else {
                        echo '<div class="col-1">点击选择患者的疾病</div>';
                    }
                    ?>
                    <div class="col-0 icon-clear <?php echo $diseaseName != '' ? '' : 'hide' ?>"></div>
                </div>
            </div>
            <div class="pt30">
                请简要描述患者的疾病情况：
            </div>
            <div>
                <textarea name="patient[disease_detail]" placeholder="请至少输入10个字"></textarea>
            </div>
        </form>
        <div>
            <button id="btnSubmit" class="btn btn-yes btn-block" disabled="disabled">下一步</button>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        //按钮操作
        document.addEventListener('input', function (e) {
            var bool = true;
            $('input').each(function () {
                if ($(this).val() == '') {
                    bool = false;
                    return false;
                }
            });
            $('textarea').each(function () {
                if ($(this).val() == '') {
                    bool = false;
                    return false;
                }
            });
            if (bool) {
                $('#btnSubmit').removeAttr('disabled');
            } else {
                $('#btnSubmit').attr('disabled', 'disabled');
            }
        });
        $('.selectDisease').find('.col-1').click(function () {
            location.href = '<?php echo $diseaseSearch; ?>?id=' + '<?php echo $id; ?>&returnUrl=' + '<?php echo $sourceReturn; ?>';
        });
        //清空疾病
        $('.icon-clear').click(function () {
            var newUrl = '<?php echo $addDisease; ?>?id=' + '<?php echo $id; ?>&returnUrl=' + '<?php echo $sourceReturn; ?>';
            history.pushState({}, '', newUrl);
            location.href = '<?php echo $diseaseSearch; ?>?id=' + '<?php echo $id; ?>&returnUrl=' + '<?php echo $sourceReturn; ?>';
        });
    });
</script>