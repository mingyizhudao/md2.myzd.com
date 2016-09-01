<?php

class PatientManager {

    public function loadPatientInfoById($id) {
        return PatientInfo::model()->getById($id);
    }

    public function loadPatientMRById($id, $attributes = '*', $with = null) {
        if (is_null($attributes)) {
            $attributes = '*';
        }
        $criteria = new CDbCriteria();
        $criteria->select = $attributes;
        $criteria->addCondition('t.date_deleted is NULL');
        $criteria->compare('t.id', $id);
        if (is_array($with)) {
            $criteria->with = $with;
        }
        return PatientMR::model()->find($criteria);
    }

    //查询所有患者信息总数
    public function loadPatientCount($creator_id) {
        $criteria = new CDbCriteria();
        $criteria->compare('t.creator_id', $creator_id);
        $criteria->addCondition('t.date_deleted is NULL');
        return PatientInfo::model()->count($criteria);
        ;
    }

    //查询该创建者所有预约患者的总数
    public function loadPatientBookingNumberByCreatorId($creator_id, $status) {
        $criteria = new CDbCriteria();
        $criteria->compare('t.creator_id', $creator_id);
        if ($status != '0') {
            $criteria->compare('t.status', $status);
        }
        $criteria->addCondition('t.date_deleted is NULL');
        return PatientBooking::model()->count($criteria);
    }

    //分组查询各个状态的预约量
    public function loadCountByStatus($creator_id) {
        $criteria = new CDbCriteria();
        $criteria->select = 't.status,count(1) id';
        $criteria->compare('t.creator_id', $creator_id);
        $criteria->addCondition('t.date_deleted is NULL');
        $criteria->group = 't.status';
        return PatientBooking::model()->findAll($criteria);
    }

    //查询该医生所有的预约患者总数
    public function loadPatientBookingNumberByDoctorId($doctor_id) {
        $criteria = new CDbCriteria();
        $criteria->compare('t.doctor_id', $doctor_id);
        $criteria->addCondition('t.date_deleted is NULL');
        return PatientBooking::model()->count($criteria);
    }

    //加载mr信息
    public function loadPatientMRByPatientId($patientId, $attributes = null, $with = null) {
        return PatientMR::model()->getByPatientId($patientId, $attributes, $with);
    }

    //加载病人文件信息
    public function loadPatientMRFilesByPatientId($patientId, $attributes = null, $with = null) {
        return PatientMRFile::model()->getAllByPatientId($patientId, $attributes, $with);
    }

    public function loadPatientBookingById($bookingId, $attributes = null, $with = null) {
        return PatientBooking::model()->getById($bookingId, $with);
    }

    //根据patientid加载booking
    public function loadPatientBookingByPatientId($patientId, $attributes = null, $with = null) {
        return PatientBooking::model()->getByPatientId($patientId, $attributes, $with);
    }

    //查询预约该医生的患者列表
    public function loadPatientBookingListByDoctorId($doctorId, $attributes = '*', $with = null, $options = null) {
        return PatientBooking::model()->getAllByDoctorId($doctorId, $with = null, $options = null);
    }

    //查询预约该医生的患者详细信息
    public function loadPatientBookingByIdAndDoctorId($id, $doctorId, $attributes = '*', $with = null) {
        return PatientBooking::model()->getByIdAndDoctorId($id, $doctorId, $with);
    }

    //查询创建者预约列表
    public function loadAllPatientBookingByCreatorId($creatorId, $status, $attributes = null, $with = null, $options = null) {
        if (is_null($attributes)) {
            $attributes = '*';
        }
        return PatientBooking::model()->getAllByCreatorId($creatorId, $status, $attributes, $with, $options);
    }

    public function loadAllPatientBookingByCreatorIdAndName($creatorId, $name, $with) {
        $criteria = new CDbCriteria();
        $criteria->select = '*';
        $criteria->compare('t.creator_id', $creatorId);
        $criteria->addCondition("(t.patient_name like '%{$name}%' or t.expected_doctor like '%{$name}%')");
        $criteria->with = $with;
        $criteria->order = 't.date_updated desc';
        return PatientBooking::model()->findAll($criteria);
    }

    /**
     * 查询创建者的预约详情
     * @param $id
     * @param $creatorId
     * @param null $attributes
     * @param null $with
     * @param null $options
     * @return PatientBooking
     */
    public function loadPatientBookingByIdAndCreatorId($id, $creatorId, $attributes = null, $with = null, $options = null) {
        if (is_null($attributes)) {
            $attributes = '*';
        }
        return PatientBooking::model()->getByIdAndCreatorId($id, $creatorId, $with);
    }

    //查询患者的病历/出院小结图片/
    public function loadFilesOfPatientByPatientIdAndCreaterIdAndType($patientId, $creatorId, $type, $attributes = null, $with = null, $options = null) {
        if (is_null($attributes)) {
            $attributes = '*';
        }

        return PatientMRFile::model()->getFilesOfPatientByPatientIdAndCreaterIdAndType($patientId, $creatorId, $type, $attributes, $with);
    }

    //查询患者列表
    public function loadPatientInfoListByCreateorId($creatorId, $attributes, $with = null, $options = null) {
        if (is_null($attributes)) {
            $attributes = '*';
        }
        return PatientInfo::model()->getAllByCreateorId($creatorId, $attributes, $with, $options);
    }

    //患者详情查询
    public function loadPatientInfoByIdAndCreateorId($id, $creatorId, $attributes, $with = null, $options = null) {
        if (is_null($attributes)) {
            $attributes = '*';
        }
        return PatientInfo::model()->getByIdAndCreatorId($id, $creatorId, $attributes, $with, $options);
    }

    //根据患者名字查询患者
    public function loadPatientListByCreateorIdAndName($createorId, $name, $attributes = '*', $with = null, $options = null) {
        return PatientInfo::model()->getAllByAttributes(array('creator_id' => $createorId, 'name' => $name), $attributes, $with, $options);
    }

    /**
     * Get EUploadedFile from $_FILE. 
     * Create DoctorCert model. 
     * Save file in filesystem. 
     * Save model in db.
     * @param EUploadedFile $file EUploadedFile::getInstances()
     * @param integer $doctorId Doctor.id     
     * @return DoctorCert 
     */
    private function savePatientMRFile($patientId, $creatorId, $reportType, $file) {
        $pFile = new PatientMRFile();
        $pFile->initModel($patientId, $creatorId, $reportType, $file);
        //文件保存于本地返回model存于数据库
        $pFile->saveModel();

        return $pFile;
    }

    public function sendSmsToCreator($patientBooking, $user) {
        $mobile = $user->getUsername();
        $smsMgr = new SmsManager();
        $data = new stdClass();
        $data->refno = $patientBooking->getRefNo();
        $doctor = $patientBooking->getDoctor();
        if (isset($doctor)) {
            $name = $doctor->name;
        } else {
            $name = '';
        }
        $data->expertBooked = $name;
        //发送提示的信息
        $smsMgr->sendSmsBookingSubmit($mobile, $data);
    }

    /*     * ************************************************app专用方法***************************************** */

    public function apiSavePatient($values, $userId) {
        $output = array('status' => 'no', 'errorCode' => ErrorList::NOT_FOUND);
        $form = new PatientInfoForm();
        $form->setAttributes($values, true);
        $form->creator_id = $userId;
        $form->country_id = 1;  // default country is China.
        if ($form->validate()) {
            $patient = $this->loadPatientInfoById($form->id);
            if (isset($patient) === false) {
                $patient = new PatientInfo();
            }
            $patient->setAttributes($form->attributes, true);
            $patient->setAge();
            $regionState = RegionState::model()->getById($patient->state_id);
            $patient->state_name = $regionState->getName();
            $regionCity = RegionCity::model()->getById($patient->city_id);
            $patient->city_name = $regionCity->getName();
            if ($patient->save()) {
                $output['status'] = 'ok';
                $output['errorMsg'] = 'success';
                Yii::app()->session['addPatientId'] = $patient->getId();
                $output['results'] = array('id' => $patient->getId());
            } else {
                $output['errorMsg'] = $patient->getFirstErrors();
            }
        } else {
            $output['errorMsg'] = $form->getFirstErrors();
        }
        return $output;
    }

    public function apiSaveDiseaseByPatient($values) {
        $output = array('status' => 'no', 'errorCode' => ErrorList::NOT_FOUND);
        $form = new PatientDiseaseForm();
        $form->setAttributes($values, true);
        if ($form->validate()) {
            $data = $form->getSafeAttributes();
            $attrs = array('id' => $data['id']);
            unset($data['id']);
            $patient = new PatientInfo();
            $return = $patient::model()->updateAllByAttributes($data, $attrs);
            if($return != 0) {
                $output['status'] = 'ok';
                $output['errorMsg'] = 'success';
            } else {
                $output['errorMsg'] = $patient->getFirstErrors();
            }
        } else {
            $output['errorMsg'] = $form->getFirstErrors();
        }
        
        return $output;
    }
    
    public function apiSavePatientBooking($values, $user) {
        $output = array('status' => 'no', 'errorCode' => ErrorList::NOT_FOUND, 'errorMsg' => '网络异常,请稍后尝试!');
        $patientId = null;
        $patientName = null;
        $patientMgr = new PatientManager();
        if (isset($values['patient_id'])) {
            $patientId = $values['patient_id'];
            $model = $patientMgr->loadPatientInfoById($patientId);
            if (isset($model)) {
                $patientName = $model->getName();
            } else {
                $output['errorMsg'] = '您还未创建此患者';
                return $output;
            }
        }
        $userId = $user->getId();
        $createName = $user->getUsername();
        $userDoctorProfile = $user->getUserDoctorProfile();
        if (isset($userDoctorProfile)) {
            if (strIsEmpty($userDoctorProfile->getName()) === false) {
                $createName = $userDoctorProfile->getName();
            }
        }
        $form = new PatientBookingForm();
        $form->setAttributes($values, true);
        $form->setPatientId($patientId);
        $form->patient_name = $patientName;
        $form->setCreatorId($userId);
        $form->creator_name = $createName;
        $form->setStatusNew();
        try {
            if ($form->validate() === false) {
                $output['errorMsg'] = $form->getFirstErrors();
                throw new CException('error saving data.');
            }
            $patientBooking = new PatientBooking();
            $patientBooking->setAttributes($form->attributes, true);
            if ($patientBooking->save() === false) {
                $output['errorMsg'] = $patientBooking->getFirstErrors();
                throw new CException('error saving data.');
            }
            $apiRequest = new ApiRequestUrl();
            $remote_url = $apiRequest->getUrlAdminSalesBookingCreate() . '?type=' . StatCode::TRANS_TYPE_PB . '&id=' . $patientBooking->id;
            $data = $apiRequest->send_get($remote_url);
            if ($data['status'] == "ok") {
                $output['status'] = EApiViewService::RESPONSE_OK;
                $output['errorCode'] = ErrorList::ERROR_NONE;
                $output['errorMsg'] = 'success';
                $output['results'] = array(
                    'refNo' => $data['salesOrderRefNo'],
                    'actionUrl' => Yii::app()->createAbsoluteUrl('/apimd/orderinfo/' . $patientBooking->getId()),
                );
                //发送提示短信
                $this->sendSmsToCreator($patientBooking, $user);
            } else {
                throw new CException('error saving data.');
            }
        } catch (CException $cex) {
            $output['errorCode'] = ErrorList::BAD_REQUEST;
            $output['errorMsg'] = '网络异常,请稍后尝试!';
        }
        return $output;
    }

    public function apiSaveDoctorOpinion($values) {
        $output = array('status' => 'no', 'errorCode' => ErrorList::NOT_FOUND, 'errorMsg' => '网络异常,请稍后尝试!');
        $userId = $values['userId'];
        $id = $values['id'];
        $type = $values['type'];
        $accept = $values['accept'];
        $opinion = $values['opinion'];
        if ($type == StatCode::TRANS_TYPE_PB) {
            $booking = PatientBooking::model()->getByAttributes(array('doctor_id' => $userId, 'id' => $id));
        } else {
            $booking = Booking::model()->getByIdAndDoctorUserId($id, $userId);
        }
        if (isset($booking)) {
            $booking->setDoctorAccept($accept);
            $booking->setDoctorOpinion($opinion);
            if ($booking->update(array('doctor_accept', 'doctor_opinion'))) {
                //医生评价成功 调用crm接口修改admin_booking的接口
                $urlMgr = new ApiRequestUrl();
                $url = $urlMgr->getUrlDoctorAccept() . "?id={$id}&type={$type}&accept={$accept}&opinion={$opinion}";
                $urlMgr->send_get($url);
                $output['status'] = 'ok';
                $output['errorCode'] = ErrorList::ERROR_NONE;
                $output['errorMsg'] = 'success';
            } else {
                $output['errorMsg'] = $booking->getFirstErrors();
            }
        } else {
            $output['errorMsg'] = '暂未填写预约信息!';
        }
        return $output;
    }

    //订单取消功能
    public function apiBookingCancell($id, $userId) {
        $output = array('status' => 'no', 'errorCode' => ErrorList::NOT_FOUND, 'errorMsg' => '网络异常,请稍后尝试!');
        $booking = $this->loadPatientBookingByIdAndCreatorId($id, $userId);
        if (isset($booking)) {
            $booking->setStatus(PatientBooking::BK_STATUS_CANCELLED);
            $booking->update(array('status'));
            //修改状态成功 远程接口调用
            $urlMgr = new ApiRequestUrl();
            $url = $urlMgr->getUrlCancell() . "?id={$id}&type=2";
            $urlMgr->send_get($url);
            $output['status'] = 'ok';
            $output['errorCode'] = ErrorList::ERROR_NONE;
            $output['errorMsg'] = 'success';
        } else {
            $output['errorMsg'] = '无权限操作!';
        }
        return $output;
    }

    //下级医生确认手术完成
    public function apiOperation($id, $userId) {
        $output = array('status' => 'no', 'errorCode' => ErrorList::NOT_FOUND, 'errorMsg' => '网络异常,请稍后尝试!');
        $booking = $this->loadPatientBookingByIdAndCreatorId($id, $userId);
        if (isset($booking)) {
            $booking->operation_finished = StatCode::OPERATION_FINISHED;
            $booking->update(array('operation_finished'));
            //远程调用接口
            $urlMgr = new ApiRequestUrl();
            $url = $urlMgr->getUrlFinished() . "?id={$id}";
            $urlMgr->send_get($url);
            $output['status'] = 'ok';
            $output['errorCode'] = ErrorList::ERROR_NONE;
            $output['errorMsg'] = 'success';
        } else {
            $output["errorMsg"] = '无权限操作!';
        }
        return $output;
    }

}
