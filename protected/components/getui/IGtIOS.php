<?php

/**
 * Created by PhpStorm.
 * User: zhangpengcheng
 * Date: 2016/8/30
 * Time: 16:04
 */
class IGtIOS extends IGtFactory
{
    private $device_token = ''; //设备唯一标识号
    private $device_token_list = []; //设备标识列表

    private $igt_template = [
        'alert_body' => 'body',
        'alert_action_loc_key' => 'ActionLocKey',
        'alert_loc_key' => 'LocKey',  //通知栏内容
        'alert_loc_args' => ['locargs'],
        'alert_launch_image' => 'launchImage',
        //IOS8.2 支持
        'alert_title' => '名医主刀', //通知栏标题
        'alert_title_loc_key' => 'TitleLocKey', //通知栏标题
        'alert_title_loc_args' => ['TitleLocArg'],
        'apn_badge' => 1,
        'apn_sound' => '',
        'apn_custom_msg' => '名医主刀推送',
        'apn_category' => 'ACTIONABLE',
        'apn_content_available' => 1
    ];

    /**
     * 对单个用户推送消息
     * @param int $type
     * @param array $template
     */
    public function pushMessageToSingle($type=0, $template=[])
    {
        $igt = new IGeTui(HOST, APPKEY, MASTERSECRET);
        $method = $this->demo_name[$type];
        $igt_template = $this->$method();
        $message = new IGtSingleMessage();
        $message->set_data($igt_template);

        $response = $igt->pushAPNMessageToSingle(APPID, $this->device_token, $message);
        if(isset($response['result']) && $response['result'] == 'ok'){
            echo '推送成功';
        } else {
            var_dump($response);
        }
    }

    /**
     * 对指定用户列表用户推送消息
     * @param int $type
     * @param array $template
     */
    public function pushMessageToList($type=0, $template=[])
    {
        $igt = new IGeTui(HOST, APPKEY, MASTERSECRET);
        //多个用户推送接口
        putenv("needDetails=true");

        $list_message = new IGtListMessage();
        $method = $this->demo_name[$type];
        $igt_template = $this->$method();
        $list_message->set_data($igt_template);

        $contentId = $igt->getAPNContentId(APPID, $list_message);
        //$deviceTokenList = array("3337de7aa297065657c087a041d28b3c90c9ed51bdc37c58e8d13ced523f5f5f");
        $response = $igt->pushAPNMessageToList(APPID, $contentId, $this->device_token_list);
        if(isset($response['result']) && $response['result'] == 'ok'){
            echo '推送成功';
        } else {
            var_dump($response);
        }
    }

    /**
     * IOS点击打开应用模板
     * @return IGtNotificationTemplate
     * @throws Exception
     */
    public function IGtNotificationTemplateDemo()
    {
        // TODO: Implement IGtNotificationTemplateDemo() method.
        $notify_template = new IGtNotificationTemplate();
        $apn = $this->getAPNPayLoad();
        $notify_template->set_apnInfo($apn);
        return $notify_template;
    }

    /**
     * IOS点击通知打开网页模板
     */
    public function IGtLinkTemplateDemo()
    {
        $link_template = new IGtLinkTemplate();
        $apn = $this->getAPNPayLoad();
        $link_template->set_apnInfo($apn);
        return $link_template;
    }

    /**
     * IOS透传消息模板
     */
    public function IGtTransmissionTemplateDemo()
    {
        $trans_template = new IGtTransmissionTemplate();

        $trans_template->set_appId(APPID);//应用appid
        $trans_template->set_appkey(APPKEY);//应用appkey
        $trans_template->set_transmissionType(1);//透传消息类型
        $trans_template->set_transmissionContent("测试离线ddd");//透传内容
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        $apn = $this->getAPNPayLoad();
        $trans_template->set_apnInfo($apn);

        return $trans_template;
    }

    /**
     * IOS暂不支持离线点击下载功能
     */
    public function IGtNotyPopLoadTemplateDemo()
    {

    }

    public function getAPNPayLoad()
    {
        //APN高级推送
        $apn = new IGtAPNPayload();
        $alertMsg = new DictionaryAlertMsg();
        $alertMsg->body = $this->current_template['alert_body'];
        $alertMsg->actionLocKey = $this->current_template['alert_action_loc_key'];
        $alertMsg->locKey = $this->current_template['alert_loc_key'];
        $alertMsg->locArgs = $this->current_template['alert_loc_args'];
        $alertMsg->launchImage = $this->current_template['alert_launch_image'];
//        IOS8.2 支持
        $alertMsg->title = $this->current_template['alert_title'];
        $alertMsg->titleLocKey = $this->current_template['alert_title_loc_key'];
        $alertMsg->titleLocArgs = $this->current_template['alert_title_loc_args'];

        $apn->alertMsg = $alertMsg;
        $apn->badge = $this->current_template['apn_badge'];
        $apn->sound = $this->current_template['apn_sound'];
        $apn->add_customMsg("payload",$this->current_template['apn_custom_msg']);
//      $apn->contentAvailable=1;
        $apn->category = $this->current_template['apn_category'];

        return $apn;
    }

    /**
     * 单个推送时，设备唯一标识号
     * @param $device_token
     */
    public function setDeviceToken($device_token)
    {
        $this->device_token = $device_token;
    }

    /**
     * 群推列表设置
     * @param $device_token_list
     */
    public function setDeviceTokenList($device_token_list)
    {
        if(is_array($device_token_list)) {
            $this->device_token_list = $device_token_list;
        }
    }

    public function setTemplate($template)
    {
        // TODO: Implement setTemplate() method.
        if(is_array($template)){
            $this->current_template = $this->igt_template;
            $this->setValue($template);
        }

    }
}