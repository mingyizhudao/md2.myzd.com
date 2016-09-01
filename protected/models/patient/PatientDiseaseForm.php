<?php

class PatientDiseaseForm extends EFormModel {

    public $id;
    public $disease_name;
    public $disease_detail;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, disease_name, disease_detail', 'required'),
            array('id', 'numerical', 'integerOnly' => true),
            array('disease_name', 'length', 'max' => 50),
            array('disease_detail', 'length', 'max' => 1000),
            array('id', 'safe'),
        );
    }

    public function initModel(PatientInfo $model = null) {
        if (isset($model)) {
            $attributes = $model->getAttributes();
            // set safe attributes.
            $this->setAttributes($attributes, true);
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'disease_name' => '疾病诊断',
            'disease_detail' => '病史描述',
        );
    }
}
