<?php


/**
 * 名医主刀公众号——患者端模板消息
 * 模板标题：用户支付成功提示
 * @author zhongtw
 */
class MyzdTemplate1 extends WechatTemplate{
    
    //标题
    private $first_Value;
    
    private $first_Color;
    
    //提交时间
    private $keyword1_Value;
    
    private $keyword1_Color;
    
    //订单类型
    private $keyword2_Value;
    
    private $keyword2_Color;
    
    //订单状态
    private $keyword3_Value;
    
    private $keyword3_Color;
    
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
        $template->template_id = "v-8qppZNx6LFcrizf1JZtzpdynzeEa6sKhJMK9lJN7I";
        
        $data = array(
                        'first' => array('value' => $template->first_Value, 'color' => $template->first_Color),
                        'keyword1' => array('value' => $template->keyword1_Value, 'color' => $template->keyword1_Color),
                        'keyword2' => array('value' => $template->keyword2_Value, 'color' => $template->keyword2_Color),
                        'keyword3' => array('value' => $template->keyword3_Value, 'color' => $template->keyword3_Color),
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
