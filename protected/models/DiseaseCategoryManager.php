<?php

class DiseaseCategoryManager
{
    const APP_VERSION = 7;
    
    public function getDiseaseCategoryToSub()
    {
        
        $oDbConnection = Yii::app()->db;
        $oCommand = $oDbConnection->createCommand(
            'SELECT d.id as did, dc.id as id, d.category_id as dcid, dc.sub_cat_name as subCatName, d.is_common as isCommon, d.name FROM ' . DiseaseCategory::model()->tableName() . ' as dc'.
            ' join ' . Disease::model()->tableName() . ' as d on dc.id = d.category_id'.
            ' where dc.app_version = :app_version and d.app_version = :app_version'
        );
        $appVersion = self::APP_VERSION;
        $oCommand->bindParam(':app_version', $appVersion);
        $data = $oCommand->queryAll();
        
        $result = array();
        $key = 0;
        $temp = false;
        
        foreach ($data as $d) {
            $std = new stdClass();
            $std->id = $d['did'];
            $std->name = $d['name'];
            $std->isCommon = $d['isCommon'];
            if (key_exists($d['dcid'], $result)) {
                array_push($result[$d['dcid']]['diseaseName'], $std);
            } else {
                $result[$d['dcid']]['id'] = $d['id'];
                $result[$d['dcid']]['subCatName'] = $d['subCatName'];
                $result[$d['dcid']]['diseaseName'] = array();
                array_push($result[$d['dcid']]['diseaseName'], $std);
            }
        }

        return $result;
    }
}
