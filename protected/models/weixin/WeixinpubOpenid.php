<?php

/**
 * This is the model class for table "weixinpub_openid".
 *
 * The followings are the available columns in table 'weixinpub_openid':
 * @property integer $id
 * @property string $weixinpub_id
 * @property string $open_id
 * @property integer $user_id
 * @property string $date_created
 * @property string $date_updated
 * @property string $date_deleted
 */
class WeixinpubOpenid extends EActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'weixinpub_openid';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('weixinpub_id, open_id, user_id, date_created', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('weixinpub_id', 'length', 'max' => 20),
            array('open_id', 'length', 'max' => 40),
            array('date_updated, date_deleted', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, weixinpub_id, open_id, user_id, date_created, date_updated, date_deleted', 'safe', 'on' => 'search'),
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
            'weixinpub_id' => '微信公众号id',
            'open_id' => '微信openid',
            'user_id' => 'user.id',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'date_deleted' => 'Date Deleted',
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('weixinpub_id', $this->weixinpub_id, true);
        $criteria->compare('open_id', $this->open_id, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('date_created', $this->date_created, true);
        $criteria->compare('date_updated', $this->date_updated, true);
        $criteria->compare('date_deleted', $this->date_deleted, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function loadByUserId($userId) {
        return $this->getByAttributes(array('user_id' => $userId));
    }
    
    public function getOpenId(){
        return $this->open_id;
    }

}
