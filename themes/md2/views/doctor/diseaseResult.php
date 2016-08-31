<?php
$searchDisease = $this->createUrl('doctor/searchDisease', array('islike' => 1, 'name' => ''));
$addDisease = $this->createUrl('doctor/addDisease');
$id = Yii::app()->request->getQuery('id', '');
$sourceReturn = Yii::app()->request->getQuery('returnUrl', '');
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
</style>
<header class="bg-green">
    <div class="grid w100">
        <div class="col-1 pl20">
            <i class="icon_search"></i>
            <input class="icon_input" type="text" placeholder="您可以输入疾病名称">
            <a class="icon_clear hide"></a>
        </div>
        <a href="javascript" class="col-0 pl5 pr5 color-white" data-target="back">
            取消
        </a>
    </div>
</header>
<article class="active" data-scroll="true">
    <div id="searchDiseaseView">

    </div>
</article>
<script>
    $(document).ready(function () {
        $("header").on("input", function () {
            var searchValue = $('input').val();
            if (Trim(searchValue) == '') {
                $('.icon_clear').addClass('hide');
                $('#searchDiseaseView').html('');
                return false;
            } else if (searchValue.match(/[a-zA-Z]/g) != null) {
                $('.icon_clear').removeClass('hide');
                var innerHtml = '<ul class="list"><li class="selectDisease color-green" data-name="' + searchValue + '">使用"' + searchValue + '"为您想要的疾病名称.</li></ul>';
                $('#searchDiseaseView').html(innerHtml);
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
            $('#searchDiseaseView').html('');
        });

        function ajaxSearch(searchValue) {
            $.ajax({
                type: 'get',
                url: '<?php echo $searchDisease; ?>/' + searchValue,
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
                innerHtml += '<ul class="list">';
                for (var i = 0; i < results.length; i++) {
                    innerHtml += '<li class="selectDisease" data-name="' + results[i].name + '">' + results[i].name + '</li>';
                }
                innerHtml += '<ul>';
            } else {
                innerHtml += '<ul class="list"><li>没有找到该疾病</li>' +
                        '<li class="selectDisease color-green" data-name="' + searchValue + '">使用"' + searchValue + '"为您想要的疾病名称.</li></ul>';
            }
            $('#searchDiseaseView').html(innerHtml);
            //选择疾病
            $('.selectDisease').click(function () {
                var diseaseName = $(this).attr('data-name');
                location.href = '<?php echo $addDisease; ?>?id=' + '<?php echo $id; ?>&returnUrl=' + '<?php echo $sourceReturn; ?>&diseaseName=' + diseaseName;
            });
        }

        function Trim(str) {
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
    });
</script>