<?php

/**
 * This is the model class for table "new_doctor".
 *
 * The followings are the available columns in table 'new_doctor':
 * @property integer $id
 * @property string $name
 * @property integer $gender
 * @property string $birthday
 * @property string $mobile
 * @property string $email
 * @property integer $hospital_id
 * @property string $hospital_name
 * @property integer $sub_cat_id
 * @property string $sub_cat_name
 * @property integer $clinic_title
 * @property integer $academic_title
 * @property integer $display_order
 * @property string $date_created
 * @property string $date_updated
 * @property string $date_deleted
 */
class NewDoctor extends EActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'new_doctor';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gender, hospital_id, sub_cat_id, clinic_title, academic_title, display_order', 'numerical', 'integerOnly' => true),
            array('name, email, hospital_name, sub_cat_name', 'length', 'max' => 50),
            array('mobile', 'length', 'max' => 20),
            array('birthday, date_updated, date_deleted', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, gender, birthday, mobile, email, hospital_id, hospital_name, sub_cat_id, sub_cat_name, clinic_title, academic_title, display_order, date_created, date_updated, date_deleted', 'safe', 'on' => 'search'),
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
            'name' => '姓名',
            'gender' => '性别',
            'birthday' => '生日',
            'mobile' => '手机',
            'email' => 'Email',
            'hospital_id' => '执业医院ID',
            'hospital_name' => '执业医院',
            'sub_cat_id' => '科目ID',
            'sub_cat_name' => '二级科目（专业）',
            'clinic_title' => '临床职称',
            'academic_title' => '学术职称',
            'display_order' => 'Display Order',
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
        $criteria->compare('gender', $this->gender);
        $criteria->compare('birthday', $this->birthday, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('hospital_id', $this->hospital_id);
        $criteria->compare('hospital_name', $this->hospital_name, true);
        $criteria->compare('sub_cat_id', $this->sub_cat_id);
        $criteria->compare('sub_cat_name', $this->sub_cat_name, true);
        $criteria->compare('clinic_title', $this->clinic_title);
        $criteria->compare('academic_title', $this->academic_title);
        $criteria->compare('display_order', $this->display_order);
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
     * @return NewDoctor the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
