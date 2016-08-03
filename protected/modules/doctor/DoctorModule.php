<?php

class DoctorModule extends CWebModule {

    /**
     * TranslateModule::init()
     * 
     * @return
     */
    public function init() {
        parent::init();
        Yii::setPathOfAlias('doctor', dirname(__FILE__));
        $this->setImport(array(
            'doctor.components.*',
            'doctor.models.*',
            'doctor.apiservices.*',
            'doctor.models.newdoctor.*',
            'doctor.models.hospital.*',
            'doctor.models.disease.*',
            'doctor.controllers.*',
        ));
         $this->setTheme('doctor');
    }

    private function setTheme($theme, $setViewPath = true) {
        // set theme.
        Yii::app()->theme = $theme;
        // set theme url & path.
        Yii::app()->themeManager->setBaseUrl(Yii::app()->theme->baseUrl);
        Yii::app()->themeManager->setBasePath(Yii::app()->theme->basePath);
        // set module viewPath.
        if ($setViewPath) {
            $this->setViewPath(Yii::app()->theme->basePath . '/views');
        }
    }

}
