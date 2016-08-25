<?php

class DiseaseManager 
{
    const APP_VERSION = 7;
    
    public function loadDiseaseById($id, $with = null) {
        return Disease::model()->getById($id, $with);
    }

    //获取疾病分类
    public function loadDiseaseCategoryList() {
        $models = DiseaseCategory::model()->getAllByInCondition('t.app_version', 7);
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
            $sql = 'SELECT id, name, category_id as categoryId FROM ' . Disease::model()->tableName().
            ' where app_version = :app_version ';
            $sql .= $islike == 1 ? 'and name like :name' : 'and name = :name';

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
