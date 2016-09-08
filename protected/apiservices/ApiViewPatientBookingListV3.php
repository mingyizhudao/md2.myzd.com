<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiViewPatientBookingListV2
 *
 * @author shuming
 */
class ApiViewPatientBookingListV3 extends EApiViewService {

    private $creatorId;  // User.id
    private $status;
    private $name;
    private $patientMgr;
    private $patientBookings;  // array
    private $pagesize;
    private $page;

    //初始化类的时候将参数注入
    public function __construct($creatorId, $status, $name = null, $pagesize = 200, $page = 1) {
        parent::__construct();
        $this->creatorId = $creatorId;
        $this->status = $status;
        $this->name = $name;
        $this->pagesize = $pagesize;
        $this->page = $page;

        $this->patientMgr = new PatientManager();
        $this->patientBookings = array();
    }

    protected function createOutput() {
        if (is_null($this->output)) {
            $this->output = array(
                'status' => self::RESPONSE_OK,
                'errorCode' => 0,
                'errorMsg' => 'success',
                'results' => $this->results,
            );
        }
    }

    protected function loadData() {
        $this->loadPatientBookings();
    }

    //调用model层方法
    private function loadPatientBookings() {
        $attributes = null;
        $with = array('pbPatient' => array('patientDAFiles'));
        $options = array('limit' => $this->pagesize, 'offset' => (($this->page - 1) * $this->pagesize), 'order' => 't.date_updated DESC');
        if (strIsEmpty($this->name)) {
            $models = $this->patientMgr->loadAllPatientBookingByCreatorIdV3($this->creatorId, $this->status, array(''), $attributes, $with, $options);
        } else {
            $models = $this->patientMgr->loadAllPatientBookingByCreatorIdAndName($this->creatorId, $this->name, $with);
        }
        if (arrayNotEmpty($models)) {
            $this->setPatientBookings($models);
        }
        $this->results->bookingList = $this->patientBookings;
    }

    //查询到的数据过滤
    private function setPatientBookings(array $models) {
        foreach ($models as $model) {
            $data = new stdClass();
            $data->id = $model->getId();
            $data->refNo = $model->getRefNo();
            $data->status = $model->getStatus(false);
            $data->statusText = $model->getStatus();
            $data->doctorName = strIsEmpty($model->getExpectedDoctor()) ? "暂无" : $model->getExpectedDoctor();
            $data->hospital = strIsEmpty($model->getExpectedHospital()) ? "暂无" : $model->getExpectedHospital();
            $patientInfo = $model->getPatient();
            $data->patientName = $patientInfo->getName();
            $files = $patientInfo->patientDAFiles;
            $data->hasFile = 0;
            if (arrayNotEmpty($files)) {
                $data->hasFile = 1;
            }
            $data->actionUrl = Yii::app()->createAbsoluteUrl('/apimd/orderinfo/' . $model->getId());
            $this->patientBookings[] = $data;
        }
    }

}
