<?php

class DoctorBankCardAuthForm extends EFormModel {

    public $id;
    public $user_id;
    public $card_type;
    public $phone;
    public $verification;

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'numerical', 'integerOnly' => true),
            array('card_type, phone, verification', 'length', 'max' => 50),
            array('id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, card_type, phone, verification', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'card_type' => '卡片类型',
            'phone' => '手机号码',
            'verification' => '验证码'
        );
    }

    public function initModel(DoctorBankCard $model = null) {
        if (isset($model)) {
            $attributes = $model->getAttributes();
            $this->setAttributes($attributes, true);
        }
    }
}
