<?php
$this->setPageTitle('密码验证');
$returnType = Yii::app()->request->getQuery('type', 0);
$cardId = Yii::app()->request->getQuery('id', 0);
$urlUpdate = $this->createUrl('userbank/update', array('id' => $cardId, 'addBackBtn' => 1));
$urlVerifyKey = $this->createUrl('userbank/verifyKey');
$urlCardList = $this->createUrl('userbank/cardList', array('addBackBtn' => 1));
$urlSmsCode = $this->createUrl('userbank/smsCode', array('addBackBtn' => 1));
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$this->show_footer=false;
?>
<article id="viewInputKey_article" class="active pwd_article" data-scroll="true">
    <div class="pad10">
        <div class="pwd-box mt30">
            <input type="tel" maxlength="6" class="pwd-input" id="pwd-input">
            <div class="fake-box grid">
                <div class="col-1">
                    <input type="password" readonly="">
                </div>
                <div class="col-1">
                    <input type="password" readonly="">
                </div>
                <div class="col-1">
                    <input type="password" readonly="">
                </div>
                <div class="col-1">
                    <input type="password" readonly="">
                </div>
                <div class="col-1">
                    <input type="password" readonly="">
                </div>
                <div class="col-1">
                    <input type="password" readonly="">
                </div>
            </div>
        </div>
        <div class="pt20">
            <a href="<?php echo $urlSmsCode; ?>" data-target="link">忘记密码</a>
        </div>
        <div class="pt40">
            <button id="submitBtn" class="btn btn-full btn-yellow2">下一步</button>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        var pwd = '';
        var $input = $(".fake-box input");
        $("#pwd-input").on("input", function () {
            pwd = $(this).val().trim();
            for (var i = 0; i < pwd.length; i++) {
                $input.eq("" + i + "").val(pwd[i]);
            }
            var len = pwd.length;
            $input.each(function () {
                var index = $(this).parents('.col-1').index();
                if (index >= len) {
                    $(this).val("");
                }
            });
        });

        $('#submitBtn').click(function () {
            var btnSubmit = $('#submitBtn');
            if (pwd.length != 6) {
                J.closePopup();
                J.customConfirm('',
                        '<div class="mt10 mb10">请输入6位密码</div>',
                        '<a id="closeLogout" class="w50">取消</a>',
                        '<a id="emptyKey" class="color-green w50">重新输入</a>',
                        function () {
                        },
                        function () {
                        });
                $('#closeLogout').click(function () {
                    J.closePopup();
                });
                $('#emptyKey').click(function () {
                    $('input').each(function () {
                        pwd = '';
                        $("#pwd-input").val('');
                        $('input').each(function () {
                            $(this).val('');
                        });
                    });
                    J.closePopup();
                });
                return;
            }
            disabled(btnSubmit);
            var data = '{"bank":{"userkey":' + pwd + '}}';
            var encryptContext = do_encrypt(data, pubkey);
            var param = {param: encryptContext};
            $.ajax({
                type: 'post',
                url: '<?php echo $urlVerifyKey; ?>',
                data: param,
                success: function (data) {
                    if (data.status == 'ok') {
                        if ('<?php echo $returnType; ?>' == 0) {
                            location.href = '<?php echo $urlCardList; ?>';
                        } else {
                            location.href = '<?php echo $urlUpdate; ?>';
                        }
                    } else {
                        pwd = '';
                        $("#pwd-input").val('');
                        $('input').each(function () {
                            $(this).val('');
                        });
                        enable(btnSubmit);
                        J.showToast(data.errors, '', '1500');
                    }
                },
                error: function (XmlHttpRequest, textStatus, errorThrown) {
                    enable(btnSubmit);
                    console.log(XmlHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });
    });
</script>