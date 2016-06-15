<?php

/**
 * This is the model class for table "wechat_base_info".
 *
 * The followings are the available columns in table 'wechat_base_info':
 * @property integer $id
 * @property string $weixinpub_id
 * @property string $access_token
 * @property string $jsapi_ticket
 * @property string $date_created
 * @property string $date_updated
 * @property string $date_deleted
 */

date_default_timezone_set('PRC'); 
define("CURRENT_TIME", date("Y-m-d H:i:s"));

class WechatBaseInfo extends EActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wechat_base_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixinpub_id', 'required'),
			array('weixinpub_id', 'length', 'max'=>20),
			array('access_token, jsapi_ticket', 'length', 'max'=>512),
			array('date_created, date_updated, date_deleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixinpub_id, access_token, jsapi_ticket, date_created, date_updated, date_deleted', 'safe', 'on'=>'search'),
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
			'weixinpub_id' => '微信账号',
			'access_token' => '公众号的全局唯一票据',
			'jsapi_ticket' => '调用微信JS接口的临时票据',
			'date_created' => '创建时间',
			'date_updated' => '最后修改时间',
			'date_deleted' => '删除时间',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('weixinpub_id',$this->weixinpub_id,true);
		$criteria->compare('access_token',$this->access_token,true);
		$criteria->compare('jsapi_ticket',$this->jsapi_ticket,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_updated',$this->date_updated,true);
		$criteria->compare('date_deleted',$this->date_deleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WechatBaseInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        
        
    /***********************************************以下为自定义方法***********************************************/    
    
    
    /**
     * 根据weixinpub_id查看基本信息是否存在，不存在就插入一条数据
     * @param type $weixinpub_id
     * @return int
     */
    public function isExists($weixinpub_id){
        $exists = WechatBaseInfo::model()->exists("weixinpub_id=:weixinpub_id",array(":weixinpub_id"=>$weixinpub_id));
        if($exists){
            return TRUE;
        }     
        $this->weixinpub_id = $weixinpub_id;
        if($this->save() > 0){ 
            return TRUE;
        }else{ 
            return FALSE;           
        }
    }
    
    
    public function getByPubId($weixinpub_id){
        return $this->getByAttributes(array('weixinpub_id'=>$weixinpub_id));
    }
    
    
    /**
     * 根据 weixinpub_id 修改 access_token
     * @param type $weixinpub_id
     * @param type $access_token
     * @return type
     */
    public function updateAccessTokenByPubId($weixinpub_id, $access_token){
        $count = WechatBaseInfo::model()->updateAll(
                array('access_token'=>$access_token, 'date_updated'=>CURRENT_TIME),
                'weixinpub_id=:weixinpub_id',
                array(':weixinpub_id'=>$weixinpub_id)
            );
        return $count;
    }
    
    
    /**
     * 根据 weixinpub_id 修改 jsapi_ticket
     * @param type $weixinpub_id
     * @param type $jsapi_ticket
     * @return type
     */
    public function updateJsapiTicketByPubId($weixinpub_id, $jsapi_ticket){
        $count = WechatBaseInfo::model()->updateAll(
                array('jsapi_ticket'=>$jsapi_ticket, 'date_updated'=>CURRENT_TIME),
                'weixinpub_id=:weixinpub_id',
                array(':weixinpub_id'=>$weixinpub_id)
            );
        return $count;
    }
    
    public function getAccessToken(){
        return $this->access_token;
    }
    
    public function getJsapiTicket(){
        return $this->jsapi_ticket;
    }
    
    
}
