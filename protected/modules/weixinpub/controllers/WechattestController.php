<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WechattestController
 *
 * @author Administrator
 */
define("MCH_ID", "123");
define("API_KEY", "456");
class WechattestController extends WeixinpubController{
    
    
    public function actionTest5() {
        echo MCH_ID;
        echo "</br>";
        echo API_KEY;
    }
    
    public function actionTest() {
        $this->render('test001');
    }
    
    public function actionTest1() {
        $wechatMsgRecord = new WechatMsgRecord();      
        $wechatMsgRecord->from_username = "1234";
        $wechatMsgRecord->to_username = "324";
        $wechatMsgRecord->send_content = "4321";
        $wechatMsgRecord->send_status = "1234";
        if($wechatMsgRecord->save()){
            echo "success";
        }else{
           var_dump($wechatMsgRecord->getErrors());  exit();
        };
    }
}
