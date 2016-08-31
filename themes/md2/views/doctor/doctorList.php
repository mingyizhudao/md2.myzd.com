<?php
$pid = Yii::app()->request->getQuery('pid', '');
$patientbookingCreate = $this->createUrl('patientbooking/create');
$inputDoctorInfo = $this->createUrl('doctor/inputDoctorInfo', array('pid' => $pid, 'doctorName' => ''));
$ajaxSearchDoctor = $this->createUrl('doctor/ajaxSearchDoctor', array('islike' => 1, 'name' => ''));
?>
<style>
    .icon_search {
        position: absolute;
        left: 27px;
        top: 9px;
        width: 15px;
        height: 25px;
        background: url('http://static.mingyizhudao.com/146243645256928') no-repeat;
        background-size: 15px 15px;
        background-position: 0 5px;
    }
    .icon_input {
        color: #000;
        margin-bottom: 0;
        border-radius: 5px!important;
        -webkit-box-shadow: none!important;
        box-shadow: none!important;
        padding: 0 10px 0 30px!important;
        height: 30px!important;
        border: none!important;
        margin-top: 7px;
    }
    .icon_clear {
        position: absolute;
        top: 9px;
        right: 45px;
        padding: 0 10px;
        width: 35px;
        height: 25px;
        background: url('http://static.mingyizhudao.com/146717942005220') no-repeat;
        background-size: 15px 15px;
        background-position: 10px 5px;
    }
    .confirmDoctor{
        border: 1px solid #A0A0A0;
        color: #A0A0A0;
        padding: 10px;
        display: block!important;
        width: 100%;
        border-radius: 5px;
        text-align: center;
    }
    article{
        background-color: #F1F1F1;
    }
    .hosIcon {
        color: #32c9c0;
        border: 1px solid #32c9c0;
        border-radius: 4px;
        padding: 0 2px;
    }
</style>
<header class="bg-green">
    <div class="grid w100">
        <div class="col-1 pl20">
            <i class="icon_search"></i>
            <input class="icon_input" type="text" placeholder="搜索您想要的医生">
            <a class="icon_clear hide"></a>
        </div>
        <a href="javascript:;" class="col-0 pl5 pr5 color-white" data-target="back">
            取消
        </a>
    </div>
</header>
<article class="active" data-scroll="true">
    <div id="doctorList">

    </div>
</article>
<script>
    $(document).ready(function () {
        $("header").on("input", function () {
            var searchValue = $('input').val();
            if (Trim(searchValue) == '') {
                $('.icon_clear').addClass('hide');
                return false;
            } else if (searchValue.match(/[a-zA-Z]/g) != null) {
                $('.icon_clear').removeClass('hide');
                return false;
            } else if (searchValue != '') {
                $('.icon_clear').removeClass('hide');
                ajaxSearch(searchValue);
            }
        });

        //清空input
        $('.icon_clear').click(function () {
            $('.icon_input').val('');
            $(this).addClass('hide');
            $('#doctorList').html('');
        });

        function ajaxSearch(searchValue) {
            $.ajax({
                type: 'get',
                url: '<?php echo $ajaxSearchDoctor; ?>/' + searchValue,
                success: function (data) {
                    if (data.status == 'ok') {
                        readyPage(data, searchValue);
                    }
                }
            });
        }

        function readyPage(data, searchValue) {
            var innerHtml = '';
            var results = data.results;
            if (results.length > 0) {
                for (var i = 0; i < results.length; i++) {
                    //擅长
                    var desc = results[i].desc;
                    desc = desc == null ? '暂无' : (desc.length > 40 ? desc.substr(0, 40) + '...' : desc);
                    innerHtml += '<div class="mt10 bg-white">' +
                            '<a href="' + '<?php echo $patientbookingCreate; ?>/pid/' + '<?php echo $pid; ?>/expectHospital/' + results[i].hpName + '/expectDept/' + results[i].hpDeptName + '/expectDoctor/' + results[i].name + '" data-target="link">' +
                            '<div class="grid pl15 pr15 pt10 pb10 bt-gray2">' +
                            '<div class="col-1 w25">' +
                            '<div class="w60p h60p br50 overflow-h">' +
                            '<img class="imgDoc" src="' + results[i].imageUrl + '">' +
                            '</div>' +
                            '</div>' +
                            '<div class="ml10 col-1 w75">' +
                            '<div class="color-black2 font-s16">' + results[i].name + '<span class="ml5 color-black6 font-s14">' + results[i].aTitle + '</span>' +
                            '</div>';
                    if (results[i].hpDeptName == null) {
                        innerHtml += '<div class="color-black6">' + results[i].mTitle + '</div>';
                    } else {
                        innerHtml += '<div class="color-black6">' + results[i].hpDeptName + '<span class="ml5">' + results[i].mTitle + '</span></div>';
                    }
                    innerHtml += '<div class="font-s12 pt5"><span class="hosIcon">' + results[i].hpName + '</span></div>' +
                            '</div>' +
                            '</div>' +
                            '</a>' +
                            '<div class="pl15 pr15 pt5 pb10 font-s12 color-black bb-gray2">' +
                            '擅长:<span class="color-gray">' + desc + '</span>' +
                            '</div>' +
                            '</div>';
                }
            }
            innerHtml += '<div class="pad20">' +
                    '<a class="confirmDoctor" href="<?php echo $inputDoctorInfo; ?>/' + searchValue + '">' +
                    '还没您想要的专家？直接填写>' +
                    '</a>' +
                    '</div>';
            $('#doctorList').html(innerHtml);
        }

        function Trim(str) {
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
    });
</script>