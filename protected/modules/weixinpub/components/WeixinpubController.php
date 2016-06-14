<?php

class WeixinpubController extends Controller {
    

    public $weixinpubId;
    
    public $wechatAccount;


    public function init() {
        parent::init();
        $this->weixinpubId = Yii::app()->getModule('weixinpub')->weixinpubId;
    }

    /**
     * 根据weixinpubId获取单个wechatAccount
     */
    public function loadWechatAccount(){
        if (is_null($this->wechatAccount)){
            $this->wechatAccount = WechatAccount::model()->getByPubId($this->weixinpubId);
        }
    }
   

}
