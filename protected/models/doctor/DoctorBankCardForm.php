<?php

class DoctorBankCardForm extends EFormModel {

    public $id;
    public $user_id;
    public $name;
    public $card_no;
    public $state_id;
    public $city_id;
    public $bank;
    public $subbranch;
    public $is_default;

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, card_no, state_id, city_id, is_default', 'numerical', 'integerOnly' => true),
            array('name, bank, subbranch', 'length', 'max' => 50),
            array('date_updated, date_deleted, date_created', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, name, card_no, state_id, city_id, bank, subbranch, is_default', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => '持卡人',
            'card_no' => '卡号',
            'state_id' => 'State',
            'state_name' => 'State Name',
            'city_id' => 'City',
            'city_name' => 'City Name',
            'bank' => '开户银行',
            'subbranch' => '银行支行',
            'is_default' => '默认'
        );
    }

    public function initModel(DoctorBankCard $model = null) {
        if (isset($model)) {
            $attributes = $model->getAttributes();
            $this->setAttributes($attributes, true);
        }
    }

}
