<?php

class DiseaseManager {

    public function loadDiseaseById($id, $with = null) {
        return Disease::model()->getById($id, $with);
    }

    //获取疾病分类
    public function loadDiseaseCategoryList() {
        $models = DiseaseCategory::model()->getAllByInCondition('t.app_version', 7);
        return $models;
    }

    /**
     * 根据关键字查询疾病
     * @param string $name 关键字
     * @param int $islike 是否模糊查询
     */
    public function getDiseaseByName($name, $islike = 0)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'id, name, category_id';
        $islike == 1 ? $criteria->compare('name', $name, true) : $criteria->compare('name', $name);
        return Disease::model()->findAll($criteria);
    }
}
