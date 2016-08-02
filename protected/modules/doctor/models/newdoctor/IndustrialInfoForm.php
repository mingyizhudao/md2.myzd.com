<?php

class IndustrialInfoForm extends EFormModel {

    public $id;
    public $hospital_id;
    public $hospital_name;
    public $category_id;
    public $cat_name;
    public $clinic_title;
    public $academic_title;
    public $options_c_title;
    public $options_a_title;
    public $options_subcat;

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, hospital_id, category_id, clinic_title, academic_title', 'numerical', 'integerOnly' => true),
            array('hospital_name, cat_name', 'length', 'max' => 50),
        );
    }

    public function attributeLabels() {
        return array(
            'hospital_id' => '医院id',
            'hospital_name' => '医院名',
            'category_id' => '专业id',
            'cat_name' => '专业',
            'clinic_title' => '临床职称',
            'academic_title' => '学术职称',
        );
    }

    public function loadOptions() {
        $this->loadOptionsClinicalTitle();
        $this->loadOptionsAcademicTitle();
        $this->loadOptionsSubcat();
    }

    public function loadOptionsClinicalTitle() {
        if (is_null($this->options_c_title)) {
            $this->options_c_title = StatCode::getOptionsClinicalTitle();
        }
        return $this->options_c_title;
    }

    public function loadOptionsAcademicTitle() {
        if (is_null($this->options_a_title)) {
            $this->options_a_title = StatCode::getOptionsAcademicTitle();
        }
        return $this->options_a_title;
    }

    public function loadOptionsSubcat() {
        if (is_null($this->options_subcat)) {
            $this->options_subcat = CHtml::listData(NewDiseaseCategory::model()->loadAllCatSub(), 'cat_id', 'cat_name');
        }
        return $this->options_subcat;
    }

}
