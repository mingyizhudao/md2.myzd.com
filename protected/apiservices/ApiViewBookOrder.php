<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiViewBookOrder
 *
 * @author shuming
 */
class ApiViewBookOrder extends EApiViewService {

    private $bookingId;
    private $status;
    private $patientMgr;
    private $orderMgr;
    private $bookingInfo;
    private $notPay;
    private $payList;

    public function __construct($bookingId) {
        parent::__construct();
        $this->bookingId = $bookingId;
        $this->patientMgr = new PatientManager();
        $this->orderMgr = new OrderManager();
        $this->bookingInfo = null;
        $this->notPay = null;
        $this->payList = array();
    }

    protected function createOutput() {
        $this->output = array(
            'status' => self::RESPONSE_OK,
            'errorCode' => 0,
            'errorMsg' => 'success',
            'results' => $this->results,
        );
    }

    protected function loadData() {
        $this->loadBooking();
        $this->loadOrders();
    }

    private function loadBooking() {
        $with = array('pbPatient' => array('patientDAFiles'));
        $booking = $this->patientMgr->loadPatientBookingById($this->bookingId, null, $with);
        if (isset($booking)) {
            $this->setBooking($booking);
        }
        $this->results->booking = $this->bookingInfo;
    }

    private function loadOrders() {
        $orders = $this->orderMgr->loadSalesOrderByBkIdAndBkType($this->bookingId);
        if (arrayNotEmpty($orders)) {
            $this->setOrder($orders);
        }
        $this->results->notPays = $this->notPay;
        $this->results->pays = $this->payList;
    }

    private function setBooking(PatientBooking $model) {
        $data = new stdClass();
        $data->id = $model->getId();
        $data->refNo = $model->getRefNo();
        $data->expectedDoctor = strIsEmpty($model->getExpectedDoctor()) ? "暂无" : $model->getExpectedDoctor();
        $data->expectedHospital = strIsEmpty($model->getExpectedHospital()) ? "暂无" : $model->getExpectedHospital();
        $data->expectedDept = strIsEmpty($model->getExpectedDept()) ? "暂无" : $model->getExpectedDept();
        $data->operationFinished = $model->operation_finished;
        $data->patientId = $model->getPatientId();
        $data->patientName = $model->getPatientName();
        $data->statusTitle = $model->getStatusTitle();
        $data->hasFile = 0;
        $patientInfo = $model->getPatient();
        $files = $patientInfo->patientDAFiles;
        if (arrayNotEmpty($files)) {
            $data->hasFile = 1;
        }
        $data->statusCode = $model->getStatus(false);
        $data->travelType = $model->getTravelType();
        $data->detail = $model->getDetail(false);
        $data->dateCreated = $model->getDateCreated('Y-m-d H:i:s');
        $data->actionUrl = Yii::app()->createAbsoluteUrl('/apimd/bookinginfo/' . $model->getId());
        $this->bookingInfo = $data;
        $this->status = $model->getStatus(false);
    }

    private function setOrder($models) {
        $needPay = 0; //剩余支付
        $payed = 0;
        $salesOrder = new SalesOrder();
        foreach ($models as $model) {
            $data = new stdClass();
            $data->id = $model->getId();
            $data->refNo = $model->ref_no;
            $data->orderTypeText = $model->getOrderType();
            $data->orderType = $model->getOrderType(false);
            $data->finalAmount = $model->getFinalAmount();
            $data->dateClosed = $model->getDateClose();
            $data->needPay = 0;
            $data->payed = 0;
            $data->isPaid = $model->getIsPaid();
            if ($model->getIsPaid(false) == '0') {
                if ($this->status == PatientBooking::BK_STATUS_NEW && $model->getOrderType(false) == SalesOrder::ORDER_TYPE_DEPOSIT) {
                    $needPay += $model->getFinalAmount();
                    $this->notPay = $data;
                } elseif ($this->status == PatientBooking::BK_STATUS_SERVICE_UNPAID && $model->getOrderType(false) == SalesOrder::ORDER_TYPE_SERVICE) {
                    $isInvalid = true;
                    $salesOrder = $salesOrder->getByAttributes(array('is_paid' => 0, 'ref_no' => $data->refNo));
                    if (isset($salesOrder->date_invalid)) {
                        strtotime($salesOrder->date_invalid) > time() && $isInvalid = false;
                    }
                    else {
                        $isInvalid = false;
                    }
                    
                    //服务费没有过期的话累加
                    if ($isInvalid === false) {
                        $needPay += $model->getFinalAmount();
                    }
                    
                    $this->notPay = $data;
                }
            } else {
                $this->payList[] = $data;
                if ($this->status == PatientBooking::BK_STATUS_SERVICE_UNPAID && $model->getOrderType(false) == SalesOrder::ORDER_TYPE_SERVICE) {
                    $payed += $model->getFinalAmount();
                }
            }
        }

        if (isset($this->notPay)) {
            $this->notPay->needPay = $needPay;
            $this->notPay->payed = $payed;
        }
    }

}
