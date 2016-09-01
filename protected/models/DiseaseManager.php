<?php

class DiseaseManager 
{
    const APP_VERSION = 8;
    
    public function loadDiseaseById($id, $with = null) {
        return Disease::model()->getById($id, $with);
    }

    //获取疾病分类
    public function loadDiseaseCategoryList() {
        $models = DiseaseCategory::model()->getAllByInCondition('t.app_version', self::APP_VERSION);
        return $models;
    }
    
    public function getDiseaseByCategoryId($catId)
    {
        $oDbConnection = Yii::app()->db;
        $oCommand = $oDbConnection->createCommand(
            'SELECT id, name, category_id as categoryId FROM ' . Disease::model()->tableName().
            ' where app_version = :app_version and category_id = :category_id'
        );
        $appVersion = self::APP_VERSION;
        $oCommand->bindParam(':app_version', $appVersion);
        $oCommand->bindParam(':category_id', $catId);
        
        return $oCommand->queryAll();
    }

    /**
     * 根据关键字查询疾病
     * @param string $name 关键字
     * @param int $islike 是否模糊查询
     */
    public function getDiseaseByName($name, $islike = 0)
    {
        $output = array('status' => 'no', 'errorCode' => ErrorList::NOT_FOUND);
        $form = new DiseaseSearchForm();
        $form->setAttributes(array('name' => $name), true);
        if ($form->validate()) {
            $data = $form->getSafeAttributes();
            $oDbConnection = Yii::app()->db;
            $sql = 'SELECT disease.id, cdj.is_common as isCommon, name, dc.sub_cat_id as subCatId, dc.sub_cat_name as subCatName FROM ' . Disease::model()->tableName().
            ',category_disease_join as cdj,disease_category as dc where cdj.sub_cat_id = dc.sub_cat_id and cdj.disease_id = disease.id and disease.app_version = :app_version ';
            $sql .= $islike == 1 ? 'and disease.name like :name' : 'and disease.name = :name';

            $oCommand = $oDbConnection->createCommand($sql);
            $appVersion = self::APP_VERSION;
            $oCommand->bindParam(':app_version', $appVersion, PDO::PARAM_INT);
            $likeName = "%{$data['name']}%";
            $islike == 1 ? $oCommand->bindParam(':name', $likeName, PDO::PARAM_STR) : $oCommand->bindParam(':name', $data['name'], PDO::PARAM_STR);

            $output['status'] = 'ok';
            $output['errorCode'] = 'success';
            $output['results'] = $oCommand->queryAll();
        }
        else {
            $output['errorMsg'] = $form->getFirstErrors();
        }
        
        return $output;
    }
}
