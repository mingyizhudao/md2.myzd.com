<?php
$diseaseResult = $this->createUrl('doctor/diseaseResult');
$addDisease = $this->createUrl('doctor/addDisease');
$diseaseCategoryToSub = $this->createUrl('doctor/diseaseCategoryToSub');
$diseaseByCategoryId = $this->createUrl('doctor/diseaseByCategoryId', array('categoryid' => ''));
$id = Yii::app()->request->getQuery('id', '');
$sourceReturn = Yii::app()->request->getQuery('returnUrl', '');
?>
<style>
    .w130p{width: 130px;}
    .searchInput {
        background: #fff url('http://static.mingyizhudao.com/146243645256928') no-repeat;
        background-size: 15px 15px;
        background-position: 7px 7px;
        height: 30px;
        border-radius: 5px;
        padding: 0 10px 0 30px;
        color: #a9a9a9;
        line-height: 32px;
        letter-spacing: 0;
        overflow: hidden;
        text-align: left;
    }
    .list>li{
        padding: 10px;
    }
</style>
<header class="bg-green">
    <div class="grid w100">
        <div class="col-0 pl5 pr10">
            <a href="javascript:;" data-target="back">
                <div class="pl5">
                    <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                </div>
            </a>
        </div>
        <div class="col-1 pt7 pb7 pr20">
            <div class="searchInput">您可以输入疾病名称</div>
        </div>
    </div>
</header>
<article class="active">
    <div class="grid">
        <div class="scrollModular col-0 w130p br-gray text-center">
            <ul id="deptList" class="list">

            </ul>
        </div>
        <div id="diseaseView" class="scrollModular col-1">

        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        J.showMask();
        var articleHeight = $('article').height();
        $('.scrollModular').css('height', articleHeight);
        $('.scrollModular').css('overflow', 'auto');
        //搜索疾病
        $('.searchInput').click(function () {
            location.href = '<?php echo $diseaseResult; ?>?id=' + '<?php echo $id; ?>&returnUrl=' + '<?php echo $sourceReturn; ?>';
        });
        //科室列表
        $.ajax({
            url: '<?php echo $diseaseCategoryToSub; ?>',
            success: function (data) {
                if (data.status == 'ok') {
                    readyDept(data);
                }
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                J.hideMask();
                console.log(XmlHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });

        function readyDept(data) {
            var innerDept = '';
            var innerDisease = '';
            var results = data.results;
            if (results.length > 0) {
                for (var i = 0; i < results.length; i++) {
                    if (i == 0) {
                        innerDept += '<li class="deptChange bg-gray3" data-id="' + results[i].id + '">' + results[i].subCatName + '</li>';
                        innerDisease += '<div class="diseaseList" data-dept="' + results[i].id + '">';
                    } else {
                        innerDept += '<li class="deptChange" data-id="' + results[i].id + '">' + results[i].subCatName + '</li>';
                        innerDisease += '<div class="diseaseList hide" data-dept="' + results[i].id + '">';
                    }
                    var diseaseName = results[i].diseaseName;
                    if (diseaseName.length > 0) {
                        //常见疾病
                        var commonDisease = '<div class="bg-gray3 pad10">常见疾病</div><ul class="list">';
                        var allDisease = '<div class="bg-gray3 pad10">全部</div><ul class="list">';
                        for (var j = 0; j < diseaseName.length; j++) {
                            if (diseaseName[j].isCommon == 1) {
                                commonDisease += '<li class="selectDisease">' + diseaseName[j].name + '</li>';
                            }
                            allDisease += '<li class="selectDisease">' + diseaseName[j].name + '</li>';
                        }
                        commonDisease += '</ul>';
                        allDisease += '</ul>';
                        innerDisease += commonDisease;
                        innerDisease += allDisease;
                    } else {
                        innerDisease += '<ul class="class"><li>暂无疾病</li></ul>';
                    }
                    innerDisease += '</div>';
                }
            }
            $('#deptList').html(innerDept);
            $('#diseaseView').html(innerDisease);
            J.hideMask();
            //科室切换
            $('.deptChange').click(function () {
                if (!$(this).hasClass('bg-gray3')) {
                    //科室添加背景色
                    $('.deptChange').each(function () {
                        $(this).removeClass('bg-gray3');
                    });
                    $(this).addClass('bg-gray3');
                    var id = $(this).attr('data-id');
                    //疾病显示、隐藏
                    $('.diseaseList').each(function () {
                        var deptId = $(this).attr('data-dept');
                        if (deptId == id) {
                            $(this).removeClass('hide');
                        } else {
                            $(this).addClass('hide');
                        }
                    });
                    $('#diseaseView').scrollTop(0);
                }
            });
            //选择疾病
            $('.selectDisease').click(function () {
                var name = $(this).text();
                location.href = '<?php echo $addDisease; ?>?id=' + '<?php echo $id; ?>&returnUrl=' + '<?php echo $sourceReturn; ?>&diseaseName=' + name;
            });
        }
    });
</script>