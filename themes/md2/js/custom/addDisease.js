$(function () {
    var domForm = $('#patient-form'),
            requestUrl = domForm.attr('data-url-action'),
            returnUrl = domForm.attr('data-url-return'),
            sourceReturn = domForm.attr('data-source-return'),
            btnSubmit = $('#btnSubmit');
    btnSubmit.click(function () {
        if ($('input[name="patient[disease_name]"]').val() == '') {
            J.showToast('请选择疾病名称', '', '1500');
            return false;
        }
        if ($('textarea[name="patient[disease_detail]"]').val() == '') {
            J.showToast('请输入疾病情况', '', '1500');
            return false;
        } else if ($('textarea[name="patient[disease_detail]"]').val().length < 10) {
            J.showToast('疾病情况至少10个字', '', '1500');
            return false;
        }
        formAjaxSubmit();
    });

    function formAjaxSubmit() {
        J.showMask();
        var id = $('input[name="patient[id]"]').val();
        var disease_name = $('input[name="patient[disease_name]"]').val();
        var disease_detail = $('textarea[name="patient[disease_detail]"]').val();
        $.ajax({
            type: 'post',
            url: requestUrl,
            data: {'patient[id]': id, 'patient[disease_name]': disease_name, 'patient[disease_detail]': disease_detail},
            success: function (data) {
                if (data.status == 'ok') {
                    location.href = returnUrl + '?id=' + id + '&returnUrl=' + sourceReturn;
                } else {
                    J.hideMask();
                    J.showToast('网络错误，请稍后再试', '', '1500');
                }
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                J.hideMask();
                console.log(XmlHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }
});