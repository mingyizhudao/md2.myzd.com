<?php

/**
 * Created by PhpStorm.
 * User: pengcheng
 * Date: 2016/11/4
 * Time: 14:33
 */

/**
 * Class UserAccountHistory
 * 用户账户明细
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $requestid
 * @property string  $ledgerno
 * @property string  $amount
 * @property integer $type
 * @property integer $status
 * @property string  $date_created
 * @property string  $date_updated
 * @property string  $date_deleted
 */
class UserAccountHistory extends EActiveRecord
{
    public function tableName() {
        return 'user_account_history';
    }

    public function rules() {
        return array(
            array('user_id, type, status', 'numerical', 'integerOnly' => true),
        );
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id')
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'USER',
            'requestid' => 'REQUEST ID',
            'ledgerno' => 'LED Gerno',
            'amount' => 'AMOUNT',
            'type' => 'TYPE',
            'status' => 'STATUS',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'date_deleted' => 'Date Deleted'

        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}