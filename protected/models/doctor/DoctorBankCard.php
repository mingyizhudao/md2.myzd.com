<?php

/**
 * This is the model class for table "doctor_bank_card".
 *
 * The followings are the available columns in table 'doctor_bank_card':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $card_no
 * @property string $card_type
 * @property string $identification_card
 * @property integer $state_id
 * @property string $state_name
 * @property integer $city_id
 * @property string $city_name
 * @property string $bank
 * @property string $subbranch
 * @property integer $is_default
 * @property string $ledgerno
 * @property integer $is_active
 * @property string $date_updated
 * @property string $date_deleted
 * @property string $date_created
 *
 * The followings are the available model relations:
 * @property User $user
 */
class DoctorBankCard extends EActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'doctor_bank_card';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, card_no, state_id, city_id, is_default, is_active', 'numerical', 'integerOnly' => true),
            array('name, state_name, card_type, city_name, bank, subbranch, ledgerno', 'length', 'max' => 50),
            array('identification_card', 'length', 'max' => 20),
            array('date_updated, date_deleted, date_created', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, name, card_no, card_type, state_id, state_name, city_id, city_name, bank, subbranch, is_default, date_updated, date_deleted, date_created', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'name' => '持卡人姓名',
            'identification_card' => '身份证号',
            'card_no' => '卡号',
            'card_type' => '卡类型',
            'state_id' => 'State',
            'state_name' => 'State Name',
            'city_id' => 'City',
            'city_name' => 'City Name',
            'bank' => '开户银行',
            'subbranch' => '银行支行',
            'is_default' => '默认0 不是 1 是',
            'ledgerno' => '子商户编号',
            'is_active' => '激活状态0:等待激活1:可以使用2:无效卡',
            'date_updated' => 'Date Updated',
            'date_deleted' => 'Date Deleted',
            'date_created' => 'Date Created',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('card_no', $this->card_no);
        $criteria->compare('card_type', $this->card_type);
        $criteria->compare('state_id', $this->state_id);
        $criteria->compare('state_name', $this->state_name, true);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('city_name', $this->city_name, true);
        $criteria->compare('bank', $this->bank, true);
        $criteria->compare('subbranch', $this->subbranch, true);
        $criteria->compare('is_default', $this->is_default);
        $criteria->compare('date_updated', $this->date_updated, true);
        $criteria->compare('date_deleted', $this->date_deleted, true);
        $criteria->compare('date_created', $this->date_created, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DoctorBankCard the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
