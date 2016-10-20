<?php
/*
 * $model DoctorForm.
 */
$this->setPageID('pUserDoctorProfile');
$this->setPageTitle('个人信息');
$urlDoctorInfo = $this->createUrl('doctor/doctorInfo', array('addBackBtn' => 1));
$urlUploadCert = $this->createUrl('doctor/uploadCert', array('addBackBtn' => 1));
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$urlDoctorProfile = $this->createUrl('doctor/profile');
$urlDoctorTerms = $this->createUrl('doctor/doctorTerms');
$urlDoctorUploadCert = $this->createUrl('doctor/uploadCert');
$urlUploadRealAuth = $this->createUrl('doctor/uploadRealAuth');
$urlLogout = $this->createUrl('doctor/logout');
$this->show_footer = false;
?>
<article id="doctorAccount_article" class="active bg-gray" data-scroll="true">
    <div class="">
        <p class="list-label">个人信息</p>
        <ul class="list">
            <li>
                <a href="<?php echo $urlDoctorInfo; ?>" class="color-000 text-center" data-target="link">
                    <div class="grid font-type">
                        <div class="col-1 w10 user-icon">
                        </div>
                        <div class="col-1 w40 text-left pl5">
                            基本信息
                        </div>
                        <div class="col-1 w50 grid">
                            <div class="col-1 text-right">
                                <?php
                                if ($userDoctorProfile == 0) {
                                    echo '<span class="color-gray">未填写</span>';
                                } else if ($userDoctorProfile == 1) {
                                    echo '<span class="c-red">认证中</span>';
                                } else if ($userDoctorProfile == 2) {
                                    echo '<span class="c-red">已认证</span>';
                                } else if ($userDoctorProfile == 3) {
                                    echo '<span class="c-red">未通过认证</span>';
                                }
                                ?>
                            </div>
                            <div class="col-0">
                                <div class="next-icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
        <p class="list-label">实名认证</p>
        <ul class="list">
            <li>
                <a href="<?php echo $urlUploadRealAuth; ?>" class="color-000 text-center" data-target="link">
                    <div class="grid font-type">
                        <div class="col-1 w10 idCard-icon"></div>
                        <div class="col-1 w40 text-left pl5">
                            身份认证
                        </div>
                        <div class="col-1 w50 grid">
                            <div class="col-1 text-right">
                                <?php
                                if ($realAuth == 0) {
                                    echo '<span class="color-gray">未上传</span>';
                                } else if ($realAuth == 1) {
                                    echo '<span class="c-red">认证中</span>';
                                } else if ($realAuth == 2) {
                                    echo '<span class="c-red">已认证</span>';
                                } else if ($realAuth == 3) {
                                    echo '<span class="c-red">未通过认证</span>';
                                }
                                ?>
                            </div>
                            <div class="col-0">
                                <div class="next-icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo $urlUploadCert; ?>" class="color-000 text-center" data-target="link">
                    <div class="grid font-type">
                        <div class="col-1 w10 qualify-icon"></div>
                        <div class="col-1 w40 text-left pl5">
                            医师资格认证
                        </div>
                        <div class="col-1 w50 grid">
                            <div class="col-1 text-right">
                                <?php
                                if ($doctorCerts == 0) {
                                    echo '<span class="color-gray">未上传</span>';
                                } else if ($doctorCerts == 1) {
                                    echo '<span class="c-red">认证中</span>';
                                } else if ($doctorCerts == 2) {
                                    echo '<span class="c-red">已认证</span>';
                                } else if ($doctorCerts == 3) {
                                    echo '<span class="c-red">未通过认证</span>';
                                }
                                ?>
                            </div>
                            <div class="col-0">
                                <div class="next-icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="mt50 pl10 pr10">
            <div id="btn_out" class="br5 bg-red pad10 text-center color-white">退出登录</div>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        //退出登录
        $("#btn_out").tap(function (e) {
            e.preventDefault();
            J.customConfirm('退出',
                    '<div class="mt10 mb10">您确定要退出该账号？</div>',
                    '<a id="closeLogout" class="w50">取消</a>',
                    '<a data="ok" class="color-green w50">确定</a>',
                    function () {
                        location.href = '<?php echo $urlLogout; ?>';
                    },
                    function () {
                    });
            $('#closeLogout').click(function () {
                J.closePopup();
            });
        });
    });
</script>