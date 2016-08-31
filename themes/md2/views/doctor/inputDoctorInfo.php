<?php
$this->setPageTitle('填写专家信息');
$patientbookingCreate = $this->createUrl('patientbooking/create');
$pid = Yii::app()->request->getQuery('pid', '');
$doctorName = Yii::app()->request->getQuery('doctorName', '');
?>
<style>
    article {
        background-color: #F1F1F1;
    }
</style>
<header class="bg-green">
    <h1 class="title">填写专家信息</h1>
</header>
<article class="active" data-scroll="true">
    <div class="pad10">
        <input type="hidden" name="expectDoctor" value="<?php echo $doctorName; ?>">
        <div class="color-green pt20 pb20">请告诉我们您想预约的专家</div>
        <div class="grid">
            <div class="col-0 pt7">所在医院：</div>
            <div class="col-1">
                <input type="text" name="expectHospital" placeholder="请输入医生所在医院">
            </div>
        </div>
        <div class="grid">
            <div class="col-0 pt7">所在科室：</div>
            <div class="col-1">
                <input type="text" name="expectDept" placeholder="请输入医生所在科室">
            </div>
        </div>
        <div class="grid">
            <div class="col-0 pt7">医生姓名：</div>
            <div class="col-1 pt7">
                <?php echo $doctorName; ?>
            </div>
        </div>
        <div class="pt30">
            <button id="submitBtn" class="btn btn-block bg-green color-white" disabled="disabled">保存</button>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        $("article").on("input", function () {
            var bool = true;
            $('input').each(function () {
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
        });
        $('#submitBtn').click(function () {
            var bool = true;
            $('input').each(function () {
                if ($(this).val() == '') {
                    J.showToast($(this).attr('placeholder'), '', '1500');
                    bool = false;
                    return false;
                }
            });
            if (bool) {
                var expectHospital = $('input[name="expectHospital"]').val();
                var expectDept = $('input[name="expectDept"]').val();
                var expectDoctor = $('input[name="expectDoctor"]').val();
                location.href = '<?php echo $patientbookingCreate; ?>/pid/' + '<?php echo $pid; ?>/expectHospital/' + expectHospital + '/expectDept/' + expectDept + "/expectDoctor/" + expectDoctor;
            }
        });
    });
</script>