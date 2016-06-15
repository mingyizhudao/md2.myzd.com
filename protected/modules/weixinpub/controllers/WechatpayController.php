<?php


/*
 * 微信支付接口
 *
 * @author zhongtw
 */
ini_set('date.timezone','Asia/Shanghai');
require_once 'protected/modules/weixinpub/lib/WxPay.Api.php';
class WechatpayController extends WeixinpubController { 
    
    private $wechatConfig;
    
    public function init(){
        parent::init();
        $this->wechatConfig = new WechatConfig();
    }
    
    /**
     * 微信公众号支付
     */
    public function actionJsapipay() {
        
        $get = $_GET;
        if(isset($get['out_trade_no']) === false || isset($get['total_fee']) === false ||isset($get['openid']) === false ||isset($get['body']) === false){
            //echo 'http://'.$_SERVER['HTTP_HOST'].'/weixinpub/wechatpay/callback';//获取当前域名
            echo 'missing payment parameters';
            Yii::app()->end();
        }
        $out_trade_no = $get['out_trade_no'];
        $total_fee = $get['total_fee'];
        $openid = $get['openid'];
        $body = $get['body'];
        $openid = "otqbXviMGjqNS236ynCNWbEf6nXE";//测试用
        $input = new WxPayUnifiedOrder();
        $input->SetBody($body);//商品或支付单简要描述
        $input->SetOut_trade_no($out_trade_no);//商户系统内部的订单号,32个字符内、可包含字母
        $input->SetTotal_fee($total_fee);//订单总金额，单位为分
        $input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/weixinpub/wechatpay/callback");//接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
        $input->SetTrade_type("JSAPI");//交易类型
        $input->SetOpenid($openid);//trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识
        
        $order = WxPayApi::unifiedOrder($input);
        $jsApiParameters = $this->GetJsApiParameters($order);
        
        $data = new stdClass();
        $data->jsApiParameters = $jsApiParameters;
        $this->render('jsapi', array('jsApiParameters' => $jsApiParameters));
               
    }
    
    
    /**
     * 微信扫码支付
     * 采用模式二，二维码有效时间为2小时
     */
    public function actionScancodepay() {
        
        $get = $_GET;
        if(isset($get['out_trade_no']) === false || isset($get['total_fee']) === false ||isset($get['product_id']) === false){
            //echo 'http://'.$_SERVER['HTTP_HOST'].'/weixinpub/wechatpay/callback';//获取当前域名
            echo 'missing payment parameters';
            Yii::app()->end();
        }
        $out_trade_no = $get['out_trade_no'];
        $total_fee = $get['total_fee'];
        $product_id = $get['product_id'];
		
        $input = new WxPayUnifiedOrder();
        //$input->SetBody("微信扫码支付");//商品或支付单简要描述
        $input->SetOut_trade_no("$out_trade_no");//商户系统内部的订单号,32个字符内、可包含字母
        $input->SetTotal_fee("$total_fee");//订单总金额，单位为分
        $input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/weixinpub/wechatpay/callback");//接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
        $input->SetTrade_type("NATIVE");//交易类型
        $input->SetProduct_id("$product_id");//trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。
        
        $result = WxPayApi::unifiedOrder($input);        
        $url = $result["code_url"];
	$data = new stdClass();
        $data->url = $url;
        
        $this->render('scancode', array('url' => $url, 'data'=>$url));
		
    }
    
    
   /**
    * 
    * 获取jsapi支付的参数
    * @param array $UnifiedOrderResult 统一支付接口返回的数据
    * @throws WxPayException
    * 
    * @return json数据，可直接填入js函数作为参数
    */
    public function GetJsApiParameters($UnifiedOrderResult){
        if(!array_key_exists("appid", $UnifiedOrderResult) || !array_key_exists("prepay_id", $UnifiedOrderResult) || $UnifiedOrderResult['prepay_id'] == ""){
            WeixinpubLog::log('参数错误', 'error', __METHOD__);
            exit();
        }
        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        return $this->renderJsonOutput($jsapi->GetValues());
        //$parameters = json_encode($jsapi->GetValues());
        //return $parameters;
    }
    
   
    /**
     * 微信支付结果通知
     */
    public function actionCallback() {
        //获取通知的xml数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $logMsg = "微信返回支付通知：$xml";
        WeixinpubLog::log($logMsg, 'info', __METHOD__);
        //将xml格式的数据转为数组
        $arr = CommonConfig::FromXml($xml);
        //如果返回的状态码是SUCCESS，并且签名验证通过
        if($arr['return_code'] == 'SUCCESS' && $arr['sign'] == $this->wechatConfig->MakeSign($arr)){
            $logMsg = "微信支付通知签名验证通过，开始处理业务数据，并返回消息给微信提示接受成功。";
            WeixinpubLog::log($logMsg, 'info', __METHOD__);
            //在这里进行数据页面处理，然后再返回数据
            
            
            $return = array("return_code"=>"SUCCESS","return_msg"=>"OK");
            echo CommonConfig::ToXml($return);
        }else{
            $logMsg = "通知提示支付失败或者签名出错。";
            WeixinpubLog::log($logMsg, 'info', __METHOD__);
        }
     
        Yii::app()->end();
    }
    
     
}
