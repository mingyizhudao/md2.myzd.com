<?php


/**
 * 患者端模板消息
 * 公众号：名医主刀
 * @author zhongtw
 */
class PatienttempmesController extends WeixinpubController {
    
    
    /**
     * 订单支付成功
     */
    public function actionPaysuccess(){
        
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        $postStr = json_decode($postStr, true);
        
        $template = new MyzdTemplate1();              
        $template->touser = $postStr["touser"];
        $template->url = $postStr["url"];
        $template->first_Value = $postStr["first_Value"];
        $template->keyword1_Value = $postStr["keyword1_Value"];
        $template->keyword2_Value = $postStr["keyword2_Value"];
        $template->keyword3_Value = $postStr["keyword3_Value"];
        $template->remark_Value = $postStr["remark_Value"];
        
        $result = $template->getTemplateMessage($template);
        echo $result;
        
    }
    
    
    /**
     * 订单未支付通知
     */
    public function actionUnpaid(){
        
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        $postStr = json_decode($postStr, true);
        
        $template = new MyzdTemplate2();
        $template->touser = $postStr["touser"];
        $template->url = $postStr["url"];
        $template->first_Value = $postStr["first_Value"];
        $template->ordertape_Value = $postStr["ordertape_Value"];
        $template->ordeID_Value = $postStr["orderID_Value"];
        $template->remark_Value = $postStr["remark_Value"];
        
        $result = $template->getTemplateMessage($template);
        echo $result;
    }
    
    
}
