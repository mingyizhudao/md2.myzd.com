<?php

/**
 * This is the model class for table "doctor_withdrawal".
 *
 * The followings are the available columns in table 'doctor_withdrawal':
 * @property string $id
 * @property integer $phone
 * @property string $name
 * @property integer $order_no
 * @property integer $bank_card_no
 * @property integer $is_withdrawal
 * @property integer $amount
 * @property integer $create_date
 */
class DoctorWithdrawal extends EActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'doctor_withdrawal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone, order_no, bank_card_no, is_withdrawal, amount, create_date', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, phone, name, order_no, bank_card_no, is_withdrawal, amount, create_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone' => 'Phone',
			'name' => 'Name',
			'order_no' => 'Order No',
			'bank_card_no' => 'Bank Card No',
			'is_withdrawal' => 'Is Withdrawal',
			'amount' => 'Amount',
			'create_date' => 'Create Date',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('phone',$this->phone);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('order_no',$this->order_no);
		$criteria->compare('bank_card_no',$this->bank_card_no);
		$criteria->compare('is_withdrawal',$this->is_withdrawal);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('create_date',$this->create_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DoctorWithdrawal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
