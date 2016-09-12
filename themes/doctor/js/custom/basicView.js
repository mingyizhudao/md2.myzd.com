$(function () {
    //验证码登录
    var domForm = $("#basicInfo-form"), // form - html dom object.;
            btnSubmit = $("#btnSubmit");
    // 手机号码验证
    $.validator.addMethod("isMobile", function (value, element) {
        var length = value.length;
        var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
        return this.optional(element) || (length == 11 && mobile.test(value));
    }, "请填写正确的手机号码");

    btnSubmit.click(function () {
        if ($('input[name="BasicInfoForm[remote_file_key]"]').val() == '') {
            $('#jingle_toast').show();
            setTimeout(function () {
                $('#jingle_toast').hide();
            }, 1500);
            return;
        }
        var bool = validator.form();
        if (bool) {
            $('#formEmail').val($('#BasicInfoForm_email').val());
            formAjaxSubmit();
        }
    });
    //登陆页面表单验证模块
    var validator = domForm.validate({
        rules: {
            'BasicInfoForm[id]': {
            },
            'BasicInfoForm[gender]': {
                required: true
            },
            'BasicInfoForm[name]': {
                required: true
            },
            'BasicInfoForm[birthday]': {
                required: true
            },
            'BasicInfoForm[mobile]': {
                required: true,
                isMobile: true
            },
            'BasicInfoForm[email]': {
            }
        },
        messages: {
            'BasicInfoForm[id]': {
            },
            'BasicInfoForm[gender]': {
                required: '请选择性别'
            },
            'BasicInfoForm[name]': {
                required: '请输入姓名'
            },
            'BasicInfoForm[birthday]': {
                required: '请选择出生日期'
            },
            'BasicInfoForm[mobile]': {
                required: '请输入手机号码',
                isMobile: '请填写正确的手机号码'
            },
            'BasicInfoForm[email]': {
                email: '请输入正确的电子邮箱'
            }
        },
        errorElement: "div",
        errorPlacement: function (error, element) {                             //错误信息位置设置方法  
            element.parents('div.input').find("div.error").remove();
            element.parents('div.input').find("div.errorMessage").remove();
            error.appendTo(element.parents('div.inputBorder'));                        //这里的element是录入数据的对象  
        }
    });

    function formAjaxSubmit() {
        //form插件的异步无刷新提交
        disabledBtn(btnSubmit);
        var formdata = domForm.serializeArray();
//        var dataArray = structure_formdata('UserRegisterForm', formdata);
//        var encryptContext = do_encrypt(dataArray, pubkey);
//        var param = {param: encryptContext};
        var requestUrl = domForm.attr("data-url-action");
        var returnUrl = domForm.attr('data-url-return');
        $.ajax({
            type: 'post',
            url: requestUrl,
            data: formdata,
            success: function (data) {
                if (data.status == 'ok') {
//                    window.location.href = returnUrl + '?register=' + data.register;
                    window.location.href = returnUrl + '/' + data.id;
                } else {
                    domForm.find("div.error").remove();
                    for (error in data.errors) {
                        errerMsg = data.errors[error];
                        inputKey = '#UserRegisterForm_' + error;
                        $(inputKey).focus();
                        $(inputKey).parents('.inputBorder').after("<div class='error'>" + errerMsg + "</div> ");
                    }
                    enableBtn(btnSubmit);
                }
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                enableBtn(btnSubmit);
                console.log(XmlHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }
});

