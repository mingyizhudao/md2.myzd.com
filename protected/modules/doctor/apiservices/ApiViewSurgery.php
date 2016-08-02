<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiViewSurgery
 *
 * @author ShuMing
 */
class ApiViewSurgery extends EApiViewService {

    private $catid;
    private $surgerys;

    public function __construct($catid) {
        parent::__construct();
        $this->catid = $catid;
        $this->surgerys = array();
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
        $this->loadSurgerys();
    }

    private function loadSurgerys() {
        $models = NewDiseaseCategory::model()->loadAllSubCatByCatId($this->catid, array('disease'));
        if (arrayNotEmpty($models)) {
            $this->setSubcat($models);
        }
        $this->results->subcatList = $this->subcats;
    }

    private function setSurgerys($models) {
        foreach ($models as $value) {
            $data = new stdClass();
            $data->subCatId = $value->sub_cat_id;
            $data->subCatName = $value->sub_cat_name;
            $diseases = array();
            if (arrayNotEmpty($value->disease)) {
                foreach ($value->disease as $d) {
                    $dis = new stdClass();
                    $dis->diseaseId = $d->id;
                    $dis->diseaseName = $d->name;
                    $diseases[] = $dis;
                }
            }
            $data->diseaseList = $diseases;
            $this->subcats[] = $data;
        }
    }

}
