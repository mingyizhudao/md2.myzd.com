<?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom/viewContractDoctors.js?ts=' . time(), CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('http://static.mingyizhudao.com/md2/viewContractDoctors.min.1.9.js', CClientScript::POS_END);
?>                                                                               
<?php
$this->setPageTitle('签约专家');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$state = Yii::app()->request->getQuery('state', '');
$source = Yii::app()->request->getQuery('source', 0);
$pid = Yii::app()->request->getQuery('pid', '');
$patientbookingCreate = $this->createUrl('patientbooking/create');
$disease_sub_category = Yii::app()->request->getQuery('disease_sub_category', '');
$page = Yii::app()->request->getQuery('page', '');
$urlAjaxContractDoctor = $this->createUrl('doctor/ajaxContractDoctor');
$urlViewContractDoctors = $this->createUrl('doctor/viewContractDoctors');
$urlState = $this->createUrl('doctor/ajaxStateList');
$urlDept = $this->createUrl('doctor/ajaxDeptList');
$urlDoctorView = $this->createUrl('doctor/viewDoctor', array('id' => ''));
$urlSearchContractDoctors = $this->createUrl('doctor/doctorList', array('pid' => $pid));
$this->show_footer = false;
?>
<style>
    #jingle_popup{
        text-align: inherit;
    }
    .activeIcon{background: url('http://static.mingyizhudao.com/146729114401744') no-repeat;background-size: 3px 21px;background-position-y:15px; }
</style>
<?php
$navTop = 'top0p';
$articleTop = 'top50p';
if ($source == 1) {
    $navTop = '';
    $articleTop = 'top84p';
    ?>
    <header id="viewContractDoctors_article" class="bg-green">
        <div class="grid w100">
            <div class="col-0 pl5 pr10">
                <a href="javascript:;" data-target="back">
                    <div class="pl5">
                        <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                    </div>
                </a>
            </div>
            <div class="col-1 pt7 pb7 pr20">
                <a href="<?php echo $urlSearchContractDoctors; ?>" class="searchInput">搜索您想要的医生</a>
            </div>
        </div>
    </header>
    <?php
}
?>
<nav id="contractDoctors_nav" class="header-secondary bg-white <?php echo $navTop; ?>">
    <div class="grid w100 font-s16 color-black6">
        <div id="deptSelect" class="col-1 w33 br-gray bb-gray grid middle grayImg">
            <span id="deptTitle" data-dept="">专业</span><img src="http://static.mingyizhudao.com/147323378222999">
        </div>
        <div id="stateSelect" class="col-1 w33 br-gray bb-gray grid middle grayImg">
            <span id="stateTitle" data-state="">地区</span><img src="http://static.mingyizhudao.com/147323378222999">
        </div>
        <div id="hospitalSelect" class="col-1 w33 bb-gray grid middle grayImg">
            <span id="hospitalTitle" data-hospital="">医院</span><img src="http://static.mingyizhudao.com/147323378222999">
        </div>
    </div>
</nav>
<article id="contractDoctors_article" class="active <?php echo $articleTop; ?>" data-scroll="true" data-source="<?php echo $source; ?>">
    <div id="docPage">

    </div>
</article>
<script>
    $(document).ready(function () {
        J.showMask();
        //请求医生
        $requestDoc = '<?php echo $urlAjaxContractDoctor; ?>';

        //签约专家访问地址
        $requestViewContractDoctors = '<?php echo $urlViewContractDoctors; ?>';

        //预约页面
        if ('<?php echo $source ?>' == 1) {
            $doctorTrigger = '<?php echo $patientbookingCreate; ?>/pid/' + '<?php echo $pid; ?>';
        } else {
            $doctorTrigger = '<?php echo $urlDoctorView; ?>';
        }

        $condition = new Array();
        $condition["source"] = '<?php echo $source; ?>';
        $condition["hospital"] = '';
        $condition["state"] = '<?php echo $state ?>';
        $condition["disease_sub_category"] = '<?php echo $disease_sub_category; ?>';
        $condition["page"] = '<?php echo $page == '' ? 1 : $page; ?>';

        var urlAjaxLoadDoctor = '<?php echo $urlAjaxContractDoctor; ?>?getcount=1';
        //J.showMask();
        $.ajax({
            url: urlAjaxLoadDoctor,
            success: function (data) {
                readyDoc(data);
                $hospital = data.results.hospital;
            }
        });


        //ajax异步加载地区
        $stateHtml = '';
        var requestState = '<?php echo $urlState; ?>';
        $.ajax({
            url: requestState,
            success: function (data) {
                $stateHtml = readyState(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
        //ajax 异步加载医院
        // $hospital='';

        $deptId = '';
        deptId = '';
        //ajax异步加载科室
        $deptHtml = '';
        var urlloadDiseaseCategory = '<?php echo $urlDept; ?>';
        $.ajax({
            url: urlloadDiseaseCategory,
            success: function (data) {
                $deptHtml = readyDept(data);
            }
        });

        function readyDept(data) {
            var results = data.results;
            var innerHtml = '';
            if ('<?php echo $source; ?>' == 1) {
                innerHtml += '<div id="deptScroll" class="color-black" style="margin-top:93px;height:315px;" data-scroll="true">';
            } else {
                innerHtml += '<div id="deptScroll" class="color-black" style="margin-top:49px;height:315px;" data-scroll="true">';
            }
            innerHtml += '<ul class="list">';
            if (results.length > 0) {
                var deptId = $('#deptTitle').attr('data-dept');

                for (var i = 0; i < results.length; i++) {
                    // if(deptId==results[i].id){
                    //   innerHtml += '<li class="cDept activeIcon" data-dept="' + results[i].id + '">' + results[i].name + '</li>';   
                    // }
                    innerHtml += '<li class="cDept" data-dept="' + results[i].id + '">' + results[i].name + '</li>';
                }
            }
            innerHtml += '</ul></div></div>';
            return innerHtml;
        }

        function readyState(data) {
            var stateList = data.results.stateList;
            var innerHtml = '';
            if ('<?php echo $source; ?>' == 1) {
                innerHtml += '<div id="cityScroll" data-scroll="true" style="height:315px;margin-top:93px;">';
            } else {
                innerHtml += '<div id="cityScroll" data-scroll="true" style="height:315px;margin-top:49px;">';
            }
            innerHtml += '<ul class="list">'
                    + '<li class="state" data-state="">全部</li>';
            for (var s in stateList) {
                var stateId = $('#stateTitle').attr('data-state');
                // if(stateId==stateList[s]){
                //  innerHtml += '<li class="state activeIcon" data-state="' + s + '">' + stateList[s] + '</li>';
                // }else{
                innerHtml += '<li class="state" data-state="' + s + '">' + stateList[s] + '</li>';
                // }

            }
            innerHtml += '</ul></div>';
            return innerHtml;
        }

    });
</script>