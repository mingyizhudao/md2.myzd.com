<?php
$this->setPageTitle('订单搜索');
$ajaxSearch = $this->createUrl('patientBooking/ajaxSearch', array('name' => ''));
$urlPatientBookingAjaxCancell = $this->createUrl('patientBooking/ajaxCancell', array('id' => ''));
$searchValue = Yii::app()->request->getQuery('searchValue', '');
$urlSearchView = $this->createUrl('patientBooking/searchView');
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
?>
<header class="list_header bg-green">
    <div class="grid w100">
        <div class="col-0 pl5 pr5">
            <a href="javascript:;" data-target="back">
                <div class="pl5">
                    <img src="<?php echo $urlResImage; ?>back.png" class="w11p">
                </div>
            </a>
        </div>
        <div class="col-1">
            <input type="text" placeholder="您可以输入患者姓名，医生姓名">
        </div>
        <div id="searchBtn" class="col-0 pl5 pr5">
            搜索
        </div>
    </div>
</header>
<div id="section_container" <?php echo $this->createPageAttributes(); ?>>
    <section id="main_section" class="active" data-init="true">
        <article id="searchListView_article" class="active list_article bg-gray3" data-scroll="true">
            <div>

            </div>
        </article>
    </section>
</div>
<script>
    $(document).ready(function () {
        $condition = new Array();
        $condition["searchValue"] = '<?php echo $searchValue; ?>';
        $condition["addBackBtn"] = 1;
        if ('<?php echo $searchValue; ?>' != '') {
            $('input[type="text"]').val('<?php echo $searchValue; ?>');
            J.showMask();
            ajaxSearch('<?php echo $searchValue; ?>');
        }
        $('#searchBtn').click(function () {
            J.showMask();
            var searchValue = $('input[type="text"]').val();
            console.log(searchValue);
            if (searchValue == '') {
                J.showToast('请先输入条件', '', '1500');
            } else {
                ajaxSearch(searchValue);
            }
        });

        function ajaxSearch(searchValue) {
            $.ajax({
                url: '<?php echo $ajaxSearch; ?>/' + searchValue,
                success: function (data) {
                    var structureData = structure_data(data);
                    var returnData = do_decrypt(structureData, privkey);
                    returnData = analysis_data(returnData);
                    readyPage(returnData, searchValue);
                },
                error: function (XmlHttpRequest, textStatus, errorThrown) {
                    J.hideMask();
                    console.log(XmlHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
            });
        }

        function readyPage(returnData, searchValue) {
            var innerHtml = '<div class="mb10">';
            var bookingList = returnData.results.bookingList;
            if (bookingList.length > 0) {
                for (var i = 0; i < bookingList.length; i++) {
                    var booking = bookingList[i];
                    console.log(booking);
                    innerHtml += '<div class="mt10 ml5 mr5 bg-white br5">' +
                            '<a href="<?php echo $orderView = $this->createUrl('order/orderView'); ?>/bookingid/' + booking.id + '/status/' + booking.status + '/addBackBtn/1" data-target="link">' +
                            '<div class="pad10 bb-gray">';
                    if (booking.status == 9) {

                    } else if (booking.hasFile == 0) {
                        innerHtml += '未传小结 ';
                    } else {
                        innerHtml += '已传小结 ';
                    }
                    innerHtml += booking.statusText +
                            '</div>' +
                            '<div class="pad10 bb-gray">' +
                            '<div class="grid">' +
                            '<div class="col-0">就诊医生:</div>' +
                            '<div class="col-1 pl5">' + booking.doctorName + '</div>' +
                            '</div>' +
                            '<div class="grid mt10">' +
                            '<div class="col-0">就诊医院:</div>' +
                            '<div class="col-1 pl5">' + booking.hospital + '</div>' +
                            '</div>' +
                            '<div class="grid mt10">' +
                            '<div class="col-0">患者姓名:</div>' +
                            '<div class="col-1 pl5">' + booking.patientName + '</div>' +
                            '</div>' +
                            '</div>' +
                            '</a>';
                    if ((booking.status == 1) || (booking.status == 2)) {
                        innerHtml +=
                                '<div class="grid pl10 pr10">' +
                                '<div class="col-1 pt8">' +
                                '预约单号:' + booking.refNo +
                                '</div>' +
                                '<div class="col-0">' +
                                '<div class="cancelIcon" data-id="' + booking.id + '">' +
                                '取消订单' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                    } else {
                        innerHtml += '<div class="pad10">' +
                                '预约单号:' + booking.refNo +
                                '</div>' +
                                '</div>';
                    }
                }
            } else {
                innerHtml += '<div class="mt50 text-center">' +
                        '<img src="http://7xsq2z.com2.z0.glb.qiniucdn.com/146295490734874" class="w170p">' +
                        '</div>' +
                        '<div class="text-center font-s24 color-gray9">' +
                        '暂无搜索结果' +
                        '</div>';
            }
            innerHtml += '</div>';
            $('article').html(innerHtml);
            J.hideMask();
            //修改url
            $condition["searchValue"] = searchValue;
            setLocationUrl();
            $('.cancelIcon').click(function () {
                var id = $(this).attr('data-id');
                J.customConfirm('',
                        '<div class="mt10 mb10">取消订单</div>',
                        '<a id="closePopup" class="w50">取消</a>',
                        '<a id="cancelOrder" class="color-green w50">确认</a>', function () {
                        }, function () {
                });
                $('#closePopup').click(function () {
                    J.hideMask();
                });
                $('#cancelOrder').click(function () {
                    J.hideMask();
                    J.showMask();
                    $.ajax({
                        url: '<?php echo $urlPatientBookingAjaxCancell; ?>/' + id,
                        success: function (data) {
                            J.hideMask();
                            if (data.status == 'ok') {
                                J.showToast('操作成功', '', '1500');
                                location.reload();
                            } else {
                                J.showToast(data.errors, '', '1500');
                            }
                        },
                        error: function (XmlHttpRequest, textStatus, errorThrown) {
                            J.hideMask();
                            J.showToast('网络错误，请稍后再试', '', '1500');
                            console.log(XmlHttpRequest);
                            console.log(textStatus);
                            console.log(errorThrown);
                        },
                    });
                });
            });
        }

        //更改url
        function setLocationUrl() {
            var stateObject = {};
            var title = "";
            var urlCondition = '';
            for ($key in $condition) {
                if ($condition[$key] && $condition[$key] !== "") {
                    urlCondition += "&" + $key + "=" + $condition[$key];
                }
            }
            urlCondition = urlCondition.substring(1);
            urlCondition = "?" + urlCondition;
            var newUrl = '<?php echo $urlSearchView; ?>' + urlCondition;
            history.pushState(stateObject, title, newUrl);
        }
    });
</script>