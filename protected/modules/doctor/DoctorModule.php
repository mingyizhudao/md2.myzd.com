<?php

class DoctorModule extends CWebModule {

    /**
     * TranslateModule::init()
     * 
     * @return
     */
    public function init() {
        $this->setImport(array(
            'doctor.components.*',
            'doctor.models.*',
            'doctor.models.newdoctor.*',
            'doctor.models.disease.*',
            'doctor.controllers.*',
        ));
        return parent::init();
    }

}
