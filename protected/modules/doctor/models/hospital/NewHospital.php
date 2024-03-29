<?php

/**
 * This is the model class for table "new_hospital".
 *
 * The followings are the available columns in table 'new_hospital':
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property integer $class
 * @property integer $type
 * @property integer $state_id
 * @property string $state_name
 * @property integer $city_id
 * @property string $city_name
 * @property integer $display
 * @property string $date_created
 * @property string $date_updated
 * @property string $date_deleted
 */
class NewHospital extends EActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'new_hospital';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('date_created', 'required'),
            array('class, type, state_id, city_id, display', 'numerical', 'integerOnly' => true),
            array('name, short_name, state_name, city_name', 'length', 'max' => 50),
            array('date_updated, date_deleted', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, short_name, class, type, state_id, state_name, city_id, city_name, display, date_created, date_updated, date_deleted', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'short_name' => 'Short Name',
            'class' => 'Class',
            'type' => 'Type',
            'state_id' => 'State',
            'state_name' => 'State Name',
            'city_id' => 'City',
            'city_name' => 'City Name',
            'display' => 'Display',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'date_deleted' => 'Date Deleted',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('short_name', $this->short_name, true);
        $criteria->compare('class', $this->class);
        $criteria->compare('type', $this->type);
        $criteria->compare('state_id', $this->state_id);
        $criteria->compare('state_name', $this->state_name, true);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('city_name', $this->city_name, true);
        $criteria->compare('display', $this->display);
        $criteria->compare('date_created', $this->date_created, true);
        $criteria->compare('date_updated', $this->date_updated, true);
        $criteria->compare('date_deleted', $this->date_deleted, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return NewHospital the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
