<?php


/**
 * 名医主刀公众号——患者端模板消息
 * 模板标题：订单未支付通知
 * @author zhongtw
 */
class MyzdTemplate2 extends WechatTemplate {
    
    
    //标题
    private $first_Value;
    
    private $first_Color;
    
    //下单时间
    private $ordertape_Value;
    
    private $ordertape_Color;
    
    //订单号
    private $ordeID_Value;
    
    private $ordeID_Color;
    
    private $remark_Value;
    
    private $remark_Color;
    
    
    public function __set($property_name, $value){
        $this->$property_name = $value;
    }
    
    public function __get($property_name){
        if(isset($this->$property_name)){
            return($this->$property_name);
        }else{
            return(NULL);
        }
    }
    
    
    public function getTemplateMessage($template) {
       
        //每个模板消息的模板ID都是固定的
        $template->template_id = "xvUzMSdE4S394l5_BWPAO6MPDZwIhikt1cJqYzDj37A";
        
        $data = array(
                        'first' => array('value' => $template->first_Value, 'color' => $template->first_Color),
                        'ordertape' => array('value' => $template->ordertape_Value, 'color' => $template->ordertape_Color),
                        'ordeID' => array('value' => $template->ordeID_Value, 'color' => $template->ordeID_Color),
	                'remark' => array('value' => $template->remark_Value,'color' => $template->remark_Color),
                    );
        
        $jsonparam  = array(
                            'touser' => $template->touser,
                            'template_id' => $template->template_id,
                            'url' => $template->url,
                            'topcolor' => $template->topcolor,
                            'data' => $data,
                        );
        
        $messageContent = json_encode($jsonparam,JSON_UNESCAPED_UNICODE);
        return parent::send_TemplateMessage($messageContent);
        
    }
    
    
}
