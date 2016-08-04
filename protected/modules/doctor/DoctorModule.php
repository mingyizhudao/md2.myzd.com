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
        Yii::app()->setComponents(array(
            'errorHandler' => array(
                'class' => 'CErrorHandler',
                'errorAction' => $this->getId() . '/home/error',
            ),
        ), true);
         $this->setTheme('doctor');
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
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
