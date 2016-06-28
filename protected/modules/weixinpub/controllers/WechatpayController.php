<?php


/*
 * 微信支付实例
 *
 * @author zhongtw
 */
class WechatpayController extends WeixinpubController { 
    
    /**
     * 微信公众号支付
     */
    public function actionJsapipay() {
        
        $url = "http://121.40.127.64:8900/wechatpay/jsapipay";
        //拼接请求json数据
        $array = array ("weixinpub_id"=>"myzdtest", "body"=>"bodytest", "openid"=>"otqbXviMGjqNS236ynCNWbEf6nXE", "out_trade_no"=>"outtradenotest123", "total_fee"=>"1");
        $data = json_encode($array,JSON_UNESCAPED_UNICODE);
        
        $result = CommonConfig::https_post($url, $data);
        $arr = json_decode($result, true);
        if(is_array($arr) && array_key_exists('flag', $arr) && $arr['flag'] == '0'){
            echo $arr['info'];
            $this->render('jsapi',array('jsApiParameters'=>$arr['info']));
        }else{
            echo $result;    
            Yii::app()->end();
        }
               
    }
    
    
    /**
     * 微信扫码支付
     * 采用模式二，二维码有效时间为2小时
     */
    public function actionScancodepay() {

		
    }
     
}
