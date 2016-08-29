<?php
$this->setPageTitle('填写专家信息');
$patientbookingCreate = $this->createUrl('patientbooking/create');
$pid = Yii::app()->request->getQuery('pid', '');
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
            <div class="col-1">
                <input type="text" name="expectDoctor" placeholder="请输入医生姓名">
            </div>
        </div>
        <div class="pt30">
            <a id="submitBtn" href="javascript:;" class="btn btn-block bg-green color-white">保存</a>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
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