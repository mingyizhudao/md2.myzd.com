<?php

class DiseaseCategoryManager
{
    const APP_VERSION = 7;
    
    public function getDiseaseCategoryToSub()
    {
        return DiseaseCategory::model()->findAll(
            array(
                'select' =>array('id', 'sub_cat_name', 'is_common'),
                'condition' => 'app_version = :app_version',
                'params' => array(':app_version' => self::APP_VERSION),
            )
        );
    }
}
