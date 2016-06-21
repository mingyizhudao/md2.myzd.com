<?php ?>
<?php
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlViewDoctor = $this->createUrl('doctor/viewDoctor');
$urlCommonwealList = $this->createUrl('doctor/commonwealList', array('addBackBtn' => 1));
$urlAjaxJoinCommonweal = $this->createUrl('doctor/ajaxJoinCommonweal');
$urlProfile = $this->createUrl('doctor/profile', array('register' => 2));
$showHeader = Yii::app()->request->getQuery('app', '1');
$this->setPageTitle('名医公益');
$this->show_footer = false;
$sectionTop = '';
if ($showHeader == 0) {
    $this->show_header = false;
    $urlCommonwealList = $this->createUrl('doctor/commonwealList', array('header' => '0', 'addBackBtn' => 1));
    $sectionTop = 'top0p';
}
$profile = $profile;
?>
<div id="section_container" <?php echo $this->createPageAttributes(); ?>>
    <section id="main_section" class="active <?php echo $sectionTop; ?>" data-init="true">
        <article id="viewCommonweal_article" class="active" data-scroll="true">
            <div class="pageBg">
                <div>
                    <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146356505242989" class="w100">
                </div>
                <div class="pl10 pr10 color-black11 text-justify">
                    <div class="font-s18 color-brown2 bl2-brown pl10 font-w800">
                        名医公益联盟是什么？
                    </div>
                    <div class="pt10">
                        名医公益联盟是名医主刀倡导发起，并联合公益组织、医生共建的一种可持续公益模式，旨在让更多患者有机会接受更好的治疗。
                    </div>
                    <div>
                        作为国内最大的移动医疗手术平台，名医主刀每天都能接触到大量需要手术的患者，其中不少患者家境贫寒难以全部承担手术服务费用。名医主刀一直将“仁爱”视为核心文化，希望通过名医公益联盟，汇聚社会爱心力量，帮助贫困患者解决“好看病，看好病”的切实需求。
                    </div>
                    <div class="font-s18 color-brown2 bl2-brown pl10 font-w800 mt10">
                        如何预约公益联盟？
                    </div>
                    <div class="pt10">
                        您可以直接在线点击或拨打客服热线预约以下医生，名医助手会在1个工作日回访确认，并指导填写申请表格。 通过审核的申请者可以免支付专家会诊费。如有家庭条件特别困难的患者，可以申请“名医公益援助金”。通过审核的申请者可以获得5000-10000元的援助金。如患者本人因病暂无能力自行申请，需指定委托人填写。
                    </div>
                    <div class="font-s18 color-brown2 bl2-brown pl10 font-w800 mt10">
                        捐赠手术的名医？
                    </div>
                    <div id="doctorList">
                        <?php
                        if (isset($data->results->page[0]) && !(is_null($data->results->page[0]))) {
                            $doctorList = $data->results->page[0];
                            for ($i = 0; $i < count($doctorList); $i++) {
                                ?>
                                <div class="bg-white mt10 border-grayD2">
                                    <a href="<?php echo $urlViewDoctor; ?>/id/<?php echo $doctorList[$i]->id; ?>/addBackBtn/1" class="color-black10">
                                        <div class="pb10">
                                            <div class="grid pl15 pr15 pb10 pt10">
                                                <div class="col-1 w25">
                                                    <div class="w60p h60p br50" style="overflow:hidden;">
                                                        <img class="imgDoc" src="<?php echo $doctorList[$i]->imageUrl; ?>">
                                                    </div>
                                                </div>
                                                <div class="ml10 col-1 w75">
                                                    <div class="grid">
                                                        <div class="col-0 font-s16"><?php echo $doctorList[$i]->name; ?></div>
                                                    </div>
                                                    <div class="color-black6"><?php echo $doctorList[$i]->hpDeptName; ?><span class="ml5"><?php echo $doctorList[$i]->mTitle; ?></span></div>
                                                    <div class="color-black6"><?php echo $doctorList[$i]->hpName; ?></div>
                                                </div>
                                            </div>
                                            <div class="ml10 mr10 pad10 bg-gray2 text-justify">擅长：<?php echo mb_substr($doctorList[$i]->desc, 0, 45, 'utf-8'); ?>...</div>
                                        </div>
                                    </a>
                                    <?php
                                    if ($i == (count($doctorList) - 1)) {
                                        if ($isCommonweal) {
                                            ?>
                                            <div class="grid mb10">
                                                <div class="col-1 w25"></div>
                                                <div class="col-1 w50 ml10 mr10">
                                                    <a href="<?php echo $urlCommonwealList; ?>" class="moreDoctor">
                                                        查看更多专家
                                                    </a>
                                                </div>
                                                <div class="col-1 w25"></div>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="grid mb10">
                                                <div class="col-1 w50 ml10 mr5">
                                                    <a href="<?php echo $urlCommonwealList; ?>" class="moreDoctor">
                                                        查看更多专家
                                                    </a>
                                                </div>
                                                <div class="col-1 w50 ml5 mr10">
                                                    <a href="javascript:;" id="joinCommonweal" class="joinMygy">
                                                        加入名医公益
                                                    </a>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="font-s18 color-brown2 bl2-brown pl10 font-w800 mt10">
                        公益合作？
                    </div>
                    <div class="grid mt10">
                        <div class="col-1 w33 text-center">
                            <div>
                                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146356274732063" class="w90p">
                            </div>
                            <div class="pad5">柏惠维康</div>
                        </div>
                        <div class="col-1 w33 text-center">
                            <div>
                                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146356274744742" class="w90p">
                            </div>
                            <div class="pad5">复兴基金会</div>
                        </div>
                        <div class="col-1 w33 text-center">
                            <div>
                                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/14635627474103" class="w90p">
                            </div>
                            <div class="pad5">上海德济医院</div>
                        </div>
                    </div>
                    <div class="grid">
                        <div class="col-1 w33 text-center">
                            <div>
                                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146356274736592" class="w90p">
                            </div>
                            <div class="pad5">春晖博爱</div>
                        </div>
                        <div class="col-1 w33 text-center">
                            <div>
                                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146356274748782" class="w90p">
                            </div>
                            <div class="pad5">和睦家医疗</div>
                        </div>
                        <div class="col-1 w33 text-center">
                            <div>
                                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146356274776219" class="w90p">
                            </div>
                            <div class="pad5">嫣然天使基金</div>
                        </div>
                    </div>
                    <div class="grid pb50">
                        <div class="col-1 w33 text-center">
                            <div>
                                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/14635627471871" class="w90p">
                            </div>
                            <div class="pad5">爱永纯</div>
                        </div>
                        <div class="col-1 w33 text-center">
                            <div>
                                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146356274771177" class="w90p">
                            </div>
                            <div class="pad5">暖阳基金</div>
                        </div>
                        <div class="col-1 w33 text-center">
                            <div>
                                <img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146356274781396" class="w90p">
                            </div>
                            <div class="pad5">中国儿童少年基金会</div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>
</div>
<script>
    $(document).ready(function () {
        $('#joinCommonweal').tap(function () {
            if (!'<?php echo $profile; ?>') {
                J.customConfirm('',
                        '<div class="mt10 mb10">请您先补充完个人信息</div>',
                        '<a href="javascript:;" id="closeLogout" class="w50">取消</a>',
                        '<a href="javascript:;" id="profile" class="color-green w50">现在就去</a>',
                        function () {
                        },
                        function () {
                        });
                $('#closeLogout').click(function () {
                    J.closePopup();
                });
                $('#profile').click(function () {
                    location.href = '<?php echo $urlProfile; ?>';
                });
                return;
            }
            J.customConfirm('',
                    '<div class="mt10 mb10">如您确认捐赠手术，请点击确认。名医助手将会尽快与您联系，谢谢！</div>',
                    '<a href="javascript:;" id="closeLogout" class="w50">取消</a>',
                    '<a href="javascript:;" id="ajaxJoginCommonweal" class="color-green w50">确认</a>',
                    function () {
                    },
                    function () {
                    });
            $('#closeLogout').click(function () {
                J.closePopup();
            });
            $('#ajaxJoginCommonweal').click(function () {
                $.ajax({
                    url: '<?php echo $urlAjaxJoinCommonweal; ?>',
                    success: function (data) {
                        J.closePopup();
                        if (data.status == 'ok') {
                            location.reload();
                        } else {
                            J.showToast('网络错误');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });
        });
    });
</script>