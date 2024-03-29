<?php

class ApiViewDiseaseCategory extends EApiViewService {
    public function __construct() {
        parent::__construct();
    }

    protected function loadData() {
       
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
    public function loadDiseaseCategory(){
        $disMgr = new DiseaseManager();
        $models = $disMgr->loadDiseaseCategoryList();
        $navList = array();
        foreach ($models as $key => $model) {
//             $data = new stdClass();
//             $data->id = $model->getCategoryId();

//             $data->name = $model->getCategoryName();
//             // sub group.
//             $subGroup = new stdClass();
//             $subGroup->id = $model->getSubCategoryId();
//             $subGroup->name = $model->getSubCategoryName();

//             if (isset($navList[$data->id])) {
//                 $navList[$data->id]->subCat[] = $subGroup;
//             } else {

//                 $navList[$data->id] = $data;
//                 $navList[$data->id]->subCat[] = $subGroup;
//             }
            $navList[$key]['id'] = $model->getSubCategoryId();
            $navList[$key]['name'] = $model->getSubCategoryName();
        }
        $this->setDiseaseCategory(array_values($navList));
    }
    
    public function getDiseaseCategoryToSub()
    {
        $diseaseCategory = new DiseaseCategoryManager();
        $navList = $diseaseCategory->getDiseaseCategoryToSub();
        
        $this->setDiseaseCategory(array_values($navList));
    }
    
    private function setDiseaseCategory($data){
        $this->results = $data;
    }

}
