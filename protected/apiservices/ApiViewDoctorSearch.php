<?php

class ApiViewDoctorSearch extends EApiViewService {

    private $searchInputs;      // Search inputs passed from request url.
    private $getCount = false;  // whether to count no. of Doctors satisfying the search conditions.
    private $pageSize = 12;
    private $doctorSearch;  // DoctorSearch model.
    private $doctors;
    private $hospital;
    private $doctorCount;     // count no. of Doctors.
    private $api = false;

    public function __construct($searchInputs) {
        parent::__construct();
        isset($searchInputs['api']) && $this->api = $searchInputs['api'];
        $this->searchInputs = $searchInputs;
        $this->getCount = isset($searchInputs['getcount']) && $searchInputs['getcount'] == 1 ? true : false;
        $this->searchInputs['pagesize'] = isset($searchInputs['pagesize']) && $searchInputs['pagesize'] > 0 ? $searchInputs['pagesize'] : $this->pageSize;
        $this->doctorSearch = new DoctorSearch($this->searchInputs);
        $this->doctorSearch->addSearchCondition("t.date_deleted is NULL");
    }

    protected function loadData() {
        // load Doctors.
        $this->loadDoctors();
        if ($this->getCount) {
            $this->loadDoctorCount();
        }
    }

    protected function createOutput() {
        if (is_null($this->output)) {
//             $this->output = array(
//                 'status' => self::RESPONSE_OK,
//                 'errorCode' => 0,
//                 'dataNum' => $this->doctorCount,
//                 'errorMsg' => 'success',
//                 'results' => $this->doctors,
//             );
            $this->output = new stdClass();
            $this->output->status = self::RESPONSE_OK;
            $this->output->errorCode = 0;
            $this->output->dataNum = $this->doctorCount;
            $this->output->errorMsg = 'success';
            if ($this->api >= 3) {
                $this->output->results = new stdClass();
                $this->output->results->doctors = $this->doctors;
                $this->output->results->hospital = $this->hospital;
            }
            else {
                $this->output->results = $this->doctors;
            }
        }
    }

    private function loadDoctors() {
        if (is_null($this->doctors)) {
            $models = $this->doctorSearch->search();
            if (arrayNotEmpty($models)) {
                $this->setDoctors($models);
            }
        }
    }

    private function setDoctors(array $models) {
        $hospital = array();
        foreach ($models as $model) {
            $hospital[$model->getHospitalId()] = $model->getHospitalName();
            
            $data = new stdClass();
            $data->id = $model->getId();
            $data->name = $model->getName();
            $data->mTitle = $model->getMedicalTitle();
            $data->aTitle = $model->getAcademicTitle();
            $data->hpId = $model->getHospitalId();
            $data->hpName = $model->getHospitalName();
            $data->hpDeptName = $model->getHpDeptName();
            $data->desc = $model->getDescription();
            $data->imageUrl = $model->getAbsUrlAvatar();
            $data->isContracted = $model->getIsContracted();
            $data->reasons = $model->getReasons();
            $data->actionUrl = Yii::app()->createAbsoluteUrl('/apimd/contractdoctor/' . $model->getId());
            $this->doctors[] = $data;
        }
        ksort($hospital);
        
        foreach ($hospital as $id => $h) {
            $data = new stdClass();
            $data->id = $id;
            $data->name = $h;
            $this->hospital[] = $data;
        }
    }

    private function loadDoctorCount() {
        if (is_null($this->doctorCount)) {
            $count = $this->doctorSearch->count();
            $this->setCount($count);
        }
    }

    private function setCount($count) {
        $this->doctorCount = $count;
    }

}
