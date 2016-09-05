<?php

class ApiViewDisease extends EApiViewService {
    public function __construct() {
        parent::__construct();
    }

    protected function loadData() {}

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
    public function getDiseaseByName($name, $islike){
        $disMgr = new DiseaseManager();
        $navList = $disMgr->getDiseaseByName($name, $islike);

        return $navList;
    }
    
    public function getDiseaseByCategoryId($categoryid)
    {
        $disease = new DiseaseManager();
        $navList = $disease->getDiseaseByCategoryId($categoryid);

        $this->setDiseaseCategory($navList);
    }
    
    private function setDiseaseCategory($data){
        $this->results = $data;
    }

}
