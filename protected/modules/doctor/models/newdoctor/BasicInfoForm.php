<?php

class BasicInfoForm extends EFormModel {

    public $id;
    public $name;
    public $gender;
    public $birthday;
    public $mobile;
    public $email;

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, gender', 'numerical', 'integerOnly' => true),
            array('name, email', 'length', 'max' => 50),
            array('mobile', 'length', 'max' => 20),
            array('birthday', 'safe'),
        );
    }

    public function attributeLabels() {
        return array(
            'name' => '姓名',
            'gender' => '性别',
            'birthday' => '生日',
            'mobile' => '手机',
            'email' => 'Email',
        );
    }

}
