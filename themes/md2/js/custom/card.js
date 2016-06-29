$(function () {
    var domForm = $('#card-form'),
            btnSubmit = $('#submitBtn');
    btnSubmit.click(function () {
        var cardTest = /^(\d{15,20})$/;
        var cardNo = $('input[name="card[card_no]"]').val();
        if (!cardTest.test(cardNo)) {
            J.showToast('银行卡号不正确', '', '1500');
            return;
        }
        var bool = validator.form();
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
        var formdata = domForm.serializeArray();
        var requestUrl = domForm.attr('data-action-url');
        var returnUrl = domForm.attr('data-return-url');
        var dataArray = structure_formdata('card', formdata);
        var encryptContext = do_encrypt(dataArray, pubkey);
        var param = {param: encryptContext};
        $.ajax({
            type: 'post',
            url: requestUrl,
            data: param,
            success: function (data) {
                if (data.status == 'ok') {
                    location.href = returnUrl;
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