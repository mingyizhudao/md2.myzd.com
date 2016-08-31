<?php

class DiseaseCategoryManager
{
    const APP_VERSION = 8;
    
    public function getDiseaseCategoryToSub()
    {
        
        $oDbConnection = Yii::app()->db;
        $oCommand = $oDbConnection->createCommand(
            'SELECT dc.id as dcid, dc.sub_cat_id as subCatId, dc.sub_cat_name as subCatName, d.id as id, d.name as name, cdj.is_common as isCommon from ' . DiseaseCategory::model()->tableName() . ' as dc,' .
            'category_disease_join as cdj,' . Disease::model()->tableName() . ' as d  where dc.sub_cat_id = cdj.sub_cat_id and cdj.disease_id = d.id and dc.app_version = :app_version'
        );
        $appVersion = self::APP_VERSION;
        $oCommand->bindParam(':app_version', $appVersion);
        $data = $oCommand->queryAll();
        
        $result = array();
        $key = 0;
        $temp = false;
        
        foreach ($data as $d) {
            $std = new stdClass();
            $std->id = $d['id'];
            $std->name = $d['name'];
            $std->isCommon = $d['isCommon'];
            if (key_exists($d['subCatId'], $result)) {
                array_push($result[$d['subCatId']]['diseaseName'], $std);
            } else {
                $result[$d['subCatId']]['id'] = $d['dcid'];
                $result[$d['subCatId']]['subCatName'] = $d['subCatName'];
                $result[$d['subCatId']]['diseaseName'] = array();
                array_push($result[$d['subCatId']]['diseaseName'], $std);
            }
        }

        return $result;
    }
}
