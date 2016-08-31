<?php

class ApiViewSearchDoctor extends EApiViewService 
{
    private $name;
    private $islike;
    private $status = false;
    private $errorMsg = false;

    public function __construct($name, $islike = 0)
    {
        parent::__construct();
        $this->name = $name;
        $this->islike = $islike;
    }
    
    protected function loadData() 
    {
        $this->SearchDoctor();
    }
    
    protected function createOutput() 
    {
        if (is_null($this->output)) {
            $this->output = array(
                'status' => $this->status === false ? self::RESPONSE_OK : $this->status,
                'errorCode' => 0,
                "errorMsg" => $this->errorMsg === false ? 'success' : $this->errorMsg,
                'results' => $this->results,
            );
        }
    }

    protected function SearchDoctor() 
    {
        $form = new DoctorSearchForm();
        $form->setAttributes(array('name' => $this->name), true);
        if ($form->validate()) {
            $data = $form->getSafeAttributes();
            $doctorInfo = $this->islike == 1 ? 
                Doctor::model()->findAll('is_contracted = 1 and name like :name', array(':name' => "%{$data['name']}%")) :
                Doctor::model()->findAll('is_contracted = 1 and name = :name', array(':name' => $data['name']));
            $result = array();
            foreach ($doctorInfo as $r) {
                $data = new stdClass();
                $data->id = $r->getId();
                $data->name = $r->getName();
                $data->hpName = $r->getHospitalName();
                $data->mTitle = $r->getMedicalTitle();
                $data->aTitle = $r->getAcademicTitle();
                $data->imageUrl = $r->getAbsUrlAvatar();
                $data->hpDeptName = $r->getHpDeptName();
                $data->isContracted = $r->getIsContracted();
                $data->desc = $r->getDescription();
                $data->actionUrl = Yii::app()->createAbsoluteUrl('/apimd/contractdoctor/' . $r->getId());
                $result[] = $data;
            }

            $this->results = $result;

        } else {
            $this->status = self::RESPONSE_VALIDATION_ERRORS;
            $this->errorMsg = $form->getFirstErrors();
        }
    }
}
