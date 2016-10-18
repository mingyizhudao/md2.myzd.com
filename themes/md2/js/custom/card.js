$(function () {
    var domForm = $('#card-form'),
            btnSubmit = $('#submitBtn');
    btnSubmit.click(function () {
        var cardTest = /^(\d{15,20})$/;
        // var cardTest = /^(\d{4}\s){3}\d{4}$/;
        var cardNo = $('input[name="card[card_no]"]').val();
        cardNo = cardNo.replace(/\s/g, "");
        if (!cardTest.test(cardNo)) {
            J.showToast('银行卡号不正确', '', '1500');
            return;
        }
        var bool = validator.form();
        // alert(bool);
        if (bool) {
            formAjaxSubmit();
        }
    });

    var validator = domForm.validate({
        rules: {
            'card[name]': {
                required: true
            },
            'card[card_no]': {
                required: true
            },
            'card[state_id]': {
                required: true
            },
            'card[city_id]': {
                required: true
            },
            'card[bank]': {
                required: true
            },
            'card[subbranch]': {
                required: true
            },
            'card[is_default]': {
                required: true
            }
        },
        messages: {
            'card[name]': {
                required: '请输入姓名'
            },
            'card[card_no]': {
                required: '请输入银行卡号'
            },
            'card[state_id]': {
                required: '省份'
            },
            'card[city_id]': {
                required: '城市'
            },
            'card[bank]': {
                required: '开户银行'
            },
            'card[subbranch]': {
                required: '银行支行'
            },
            'card[is_default]': {
                required: '设为默认'
            }
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
//            element.parents('div.input').find('div.error').remove();
//            element.parents('div.input').find('div.errorMessage').remove();
//            error.appendTo(element.parents('.inputRow'));
            element.removeClass('error');
            element.addClass('error');
        }
    });

    function formAjaxSubmit() {
        disabled(btnSubmit);
        var requestUrl = domForm.attr('data-action-url');
        var returnUrl = domForm.attr('data-return-url');
        //对卡号进行去除空格
        var cardNo = $('input[name="card[card_no]"]').val();
        cardNo = cardNo.replace(/\s/g, "");
        var dataArray = '{"card":{"card_no":"' + cardNo + '"}}';
        var encryptContext = do_encrypt(dataArray, pubkey);
        var param = {param: encryptContext};
        $.ajax({
            type: 'post',
            url: requestUrl,
            data: param,
            success: function (data) { 
                  console.log(data);
                if (data.status == 'ok') {
                  location.href = returnUrl+'/'+data.cardId;
                } else {
                    enable(btnSubmit);
                }
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                enable(btnSubmit);
                console.log(XmlHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            },
        });
    }
});