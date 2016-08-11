jQuery(function () {
    var $ = jQuery,
            domForm = $("#doctor-form"), // form - html dom object.;
            btnSubmit = $("#doctorSubmitBtn");

    var validator = domForm.validate({
        rules: {
            'DoctorForm[hospital_name]': {
                required: true
            },
            'DoctorForm[cat_name]': {
                required: true
            }
        },
        messages: {
            'DoctorForm[hospital_name]': {
                required: "请选择医院"
            },
            'DoctorForm[cat_name]': {
                required: "请选择您的专业"
            }
        },
        errorElement: "div",
        errorPlacement: function (error, element) {                             //错误信息位置设置方法  
            element.parent().find("div.error").remove();
            error.appendTo(element.parent());     //这里的element是录入数据的对象  
        }
    });
    btnSubmit.click(function () {
        if ($('input[name="DoctorForm[hospital_name]"]').val() == '') {
            $('#jingle_toast').find('a').text('请选择您的执业医院');
            $('#jingle_toast').show();
            setTimeout(function () {
                $('#jingle_toast').hide();
            }, 1500);
            return;
        }
        if ($('input[name="DoctorForm[cat_name]"]').val() == '') {
            $('#jingle_toast').find('a').text('请选择您的专业');
            $('#jingle_toast').show();
            setTimeout(function () {
                $('#jingle_toast').hide();
            }, 1500);
            return;
        }
        var bool = validator.form();
        if (bool) {
            formAjaxSubmit();
        }
    });
    function formAjaxSubmit() {
        //form插件的异步无刷新提交
        disabledBtn(btnSubmit);
        var formdata = domForm.serialize();
        var requestUrl = domForm.attr('data-url-action');
        var returnUrl = domForm.attr('data-url-return');
        $.ajax({
            type: 'post',
            url: requestUrl,
            data: formdata,
            success: function (data) {
                //success.
                if (data.status == 'ok') {
                    location.href = returnUrl + '/' + data.id;
                } else {
                    domForm.find("div.error").remove();
                    for (error in data.errors) {
                        errerMsg = data.errors[error];
                        inputKey = '#DoctorForm_' + error;
                        $(inputKey).focus();
                        $(inputKey).parent().append("<div class='error'>" + errerMsg + "</div> ");
                    }
                    //error.
                }
                enableBtn(btnSubmit);
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                enableBtn(btnSubmit);
                console.log(XmlHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            },
            complete: function () {
                enableBtn(btnSubmit);
                //btnSubmit.show();
            }
        });
    }
});

