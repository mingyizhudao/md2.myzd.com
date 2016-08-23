<?php

class DiseaseCategoryManager
{
    const APP_VERSION = 7;
    
    public function getDiseaseCategoryToSub()
    {
        
        $oDbConnection = Yii::app()->db;
        $oCommand = $oDbConnection->createCommand(
            'SELECT d.id as did, dc.id as id, dc.sub_cat_name as subCatName, d.is_common as isCommon, d.name FROM ' . DiseaseCategory::model()->tableName() . ' as dc'.
            ' join ' . Disease::model()->tableName() . ' as d on dc.id = d.category_id'.
            ' where dc.app_version = :app_version and d.app_version = :app_version'
        );
        $appVersion = self::APP_VERSION;
        $oCommand->bindParam(':app_version', $appVersion);
        $data = $oCommand->queryAll();
        
        $result = array();
        foreach ($data as $key => $d) {
            $result[$key]['id'] = $d['id'];
            $result[$key]['subCatName'] = $d['subCatName'];
            $std = new stdClass();
            $std->id = $d['did'];
            $std->name = $d['name'];
            $result[$key]['diseaseName'] = $std;
        }
        
        
        return $result;
    }
}
