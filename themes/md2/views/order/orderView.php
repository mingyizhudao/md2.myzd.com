<?php
/*
 * $model DoctorForm.
 */
$this->setPageTitle('订单详情');
$urlPatientBooking = $this->createUrl('booking/patientBooking', array('id' => ''));
$urlPatientBookingList = $this->createUrl('booking/patientBookingList');
$urlPatientBookingView = $this->createUrl('patientBooking/view', array('id' => ''));
$status = Yii::app()->request->getQuery('status', 0);
$patientBookingList = $this->createUrl('patientBooking/list', array('status' => $status, 'addBackBtn' => 1));
$patientBookingListAll = $this->createUrl('patientbooking/list', array('status' => 0, 'addBackBtn' => 1));
$payUrl = $this->createUrl('/payment/doPingxxPay');
$refUrl = $this->createAbsoluteUrl('order/view');
$urlDoctorView = $this->createUrl('doctor/view');
$urlAjaxOperation = $this->createUrl('patientbooking/ajaxOperation', array('id' => ''));
$urlResImage = Yii::app()->theme->baseUrl . "/images/";
$BK_STATUS_NEW = StatCode::BK_STATUS_NEW;
$BK_STATUS_SERVICE_UNPAID = StatCode::BK_STATUS_SERVICE_UNPAID;
$BK_STATUS_SERVICE_PAIDED = StatCode::BK_STATUS_SERVICE_PAIDED;
$BK_STATUS_DONE = StatCode::BK_STATUS_DONE;
$BK_STATUS_PROCESS_DONE = StatCode::BK_STATUS_PROCESS_DONE;
$orderType = SalesOrder::ORDER_TYPE_SERVICE;
$user = $this->loadUser();
$booking = $data->results->booking;
$notPays = $data->results->notPays;
$pays = $data->results->pays;
$this->show_footer = false;
$hasFile = $data->results->booking->hasFile;
if ($hasFile == 0) {
    $urlUpload = $this->createUrl('patient/uploadDAFile', array('id' => $booking->patientId, 'bookingid' => $booking->id, 'addBackBtn' => 1));
} else {
    $urlUpload = $this->createUrl('patient/viewDaFile', array('id' => $booking->patientId, 'bookingid' => $booking->id, 'addBackBtn' => 1));
}
$urlPatientMRFiles = 'http://file.mingyizhudao.com/api/loadpatientmr?userId=' . $user->id . '&patientId=' . $booking->patientId . '&reportType=da'; //$this->createUrl('patient/patientMRFiles', array('id' => $patientId));
?>
<header id="payOrder_header" class="bg-green">
    <nav class="left">
        <?php
        if (($booking->statusCode == $BK_STATUS_NEW) && (isset($notPays))) {
            ?>
            <a id="noPayNew">
                <div class="pl5">
                    <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                </div>
            </a>
            <?php
        } else if (($booking->statusCode == $BK_STATUS_SERVICE_UNPAID) && (isset($notPays))) {
            ?>
            <a id="noPayService">
                <div class="pl5">
                    <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                </div>
            </a>
            <?php
        } else {
            ?>
            <a href="" data-target="back">
                <div class="pl5">
                    <img src="http://static.mingyizhudao.com/146968435878253" class="w11p">
                </div>
            </a>
            <?php
        }
        ?>
    </nav>
    <h1 class="title">订单详情</h1>
    <nav class="right">
        <a class="submitSummary" data-target="link" href="<?php echo $urlUpload; ?>">
            传小结
        </a>
    </nav>
</header>
<?php
if ($booking->statusCode != $BK_STATUS_SERVICE_PAIDED) {
    if (isset($notPays)) {
        if ($notPays->orderType == $orderType) {
            ?>
            <footer class="bg-white grid">
                <div class="col-1 w60 color-green middle grid">还需支付<?php echo $notPays->needPay; ?>元</div>
                <div id="pay" class="col-1 w40 bg-green color-white middle grid">
                    继续支付
                </div>
            </footer>
            <?php
        } else {
            ?>
            <footer class="bg-white grid">
                <div class="col-1 w60 color-green middle grid"><?php echo $notPays->needPay; ?>元</div>
                <div id="payNow" data-refNo="<?php echo $notPays->refNo; ?>" class="col-1 w40 bg-green color-white middle grid">
                    支付
                </div>
            </footer>
            <?php
        }
    }
} else {
    if ($booking->operationFinished == 0) {
        ?>
        <footer class="bg-white">
            <div id="completeOperation" class="w100 bg-green color-white middle grid">
                确认完成
            </div>
        </footer>
        <?php
    }
}
?>
<article id='payOrder_article' class="active bg-gray3" data-scroll="true">
    <div>
        <?php
        if ($booking->statusCode == $BK_STATUS_DONE) {
            $fontSize = 'font-s16';
        } else {
            $fontSize = 'font-s18';
        }
        ?>
        <div class="grid pl10 pr10 mt20 color-green <?php echo $fontSize; ?>">
            <div class="col-0">
                <img src="http://static.mingyizhudao.com/146968572624750" class="w20p mr10">
            </div>
            <div class="col-1 pt3 grid">
                <div class="col-0"><?php echo $booking->statusTitle; ?></div>
            </div>
        </div>
        <div class="mt20 ml10 mr10 bbb">
            <ul class="list">
                <li class="grid">
                    <div class="col-0">就诊方式</div>
                    <div class="col-1 text-right"><?php echo $booking->travelType; ?></div>
                </li>
                <li class="grid">
                    <div class="col-0">主刀专家</div>
                    <div class="col-1 text-right">
                        <div><?php echo $booking->expectedDoctor; ?></div>
                        <div class="font-s12">(<?php echo $booking->expectedHospital . '' . $booking->expectedDept; ?>)</div>
                    </div>
                </li>
                <li>
                    <div>诊疗意见</div>
                    <div class="w100"><?php echo $booking->detail; ?></div>
                </li>
                <li class="grid">
                    <div class="col-0">患者姓名</div>
                    <div class="col-1 text-right"><?php echo $booking->patientName; ?></div>
                </li>
                <li class="detailLi">
                    <div class="text-center">
                        <a href="<?php echo $urlPatientBookingView; ?>/<?php echo $booking->id; ?>/addBackBtn/1" class="btn btn-gray2">查看详情</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="font-s12 letter-s1 ml20 mr20 mt10">
            <div>订单编号:<?php echo $booking->refNo; ?></div>
            <div>创建时间:<?php echo $booking->dateCreated; ?></div>
            <?php
            if (isset($pays)) {
                for ($i = 0; $i < count($pays); $i++) {
                    if ($pays[$i]->orderType == 'deposit') {
                        echo '<div>确认时间:' . $pays[$i]->dateClosed . '</div><div>已支付' . $pays[$i]->orderTypeText . $pays[$i]->finalAmount . '元</div>';
                    } else {
                        echo '<div>提交时间:' . $pays[$i]->dateClosed . '</div><div>已支付' . $pays[$i]->orderTypeText . $pays[$i]->finalAmount . '元</div>';
                    }
                }
            }
            ?>
        </div>
    </div>
</article>
<script>
    $(document).ready(function () {
        $('#completeOperation').tap(function (e) {
            e.preventDefault();
            J.showMask();
            $.ajax({
                url: '<?php echo $urlAjaxOperation; ?>/' + '<?php echo $booking->id; ?>',
                success: function (data) {
                    console.log(data);
                    if (data.status == 'ok') {
                        J.hideMask();
                        J.customConfirm('<div class="color-black3">感谢您的辛勤付出和协助！</div>',
                                '<div class="mt10 mb10">请记得上传出院小结哦~</div>',
                                '',
                                '<a id="closeLogout">返回订单列表</a>',
                                function () {
                                },
                                function () {
                                });
                        $('#closeLogout').tap(function () {
                            J.hideMask();
                            location.href = '<?php echo $patientBookingListAll; ?>';
                        });
                    }
                },
                error: function (XmlHttpRequest, textStatus, errorThrown) {
                    J.hideMask();
                    console.log(XmlHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
            });
        });

        //待处理返回
        $('#noPayNew').tap(function () {
            J.customConfirm('提示',
                    '<div class="mb10">确定暂不支付手术预约金?</div><div class="font-s12">（稍后可在"订单-待支付"里完成）</div>',
                    '<a data="cancel" class="w50">取消</a>',
                    '<a href="<?php echo $patientBookingList; ?>" class="w50 color-green">确定</a>',
                    function () {
                    },
                    function () {
                        J.hideMask();
                    });
        });

        //待确定返回
        $('#noPayService').tap(function () {
            J.customConfirm('提示',
                    '<div class="mb10">确定暂不支付手术咨询费?</div><div class="font-s12">（稍后可在"订单-待确认"里完成）</div>',
                    '<a data="cancel" class="w50">取消</a>',
                    '<a href="<?php echo $patientBookingList; ?>" class="w50 color-green">确定</a>',
                    function () {
                    },
                    function () {
                        J.hideMask();
                    });
        });

        $('#payNow').tap(function () {
            var refNo = $(this).attr('data-refNo');
            location.href = '<?php echo $this->createUrl('order/view', array('bookingId' => $booking->id, 'refNo' => '')); ?>/' + refNo;
        });
        $('#pay').tap(function () {
            location.href = '<?php echo $this->createUrl('order/payOrders', array('bookingId' => $booking->id, 'orderType' => $orderType, 'addBackBtn' => 1)); ?>';
        });
    });
</script>