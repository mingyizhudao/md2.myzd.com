<?php

/**
 * Created by PhpStorm.
 * User: zhangpengcheng
 * Date: 2016/8/30
 * Time: 16:04
 */
class IGtAndroid extends IGtFactory
{
    private $client_id = '67d0fc0f0f7b5e289d5fd1ffe36ee39b';
    private $client_id_list = [];

    //点击通知打开应用默认模板
    private $igt_notify_template = [
        'app_id' => APPID,
        'app_key' => APPKEY,
        'transmission_type' => 1, //4b
        'transmission_content' => '名医主刀',
        'title' => '名医主刀', //40字
        'text' => '名医主刀消息推送', //600字
        'logo' => '', //40字
        'logo_url' => '', //100字
        'is_ring' => true,
        'is_vibrate' => true,
        'is_clearable' => true,
        'push_info' => '', //ios使用
        'notify_style' => 0, //通知栏消息布局样式 0:系统样式 1:个推样式 默认0
    ];

    //点击通知打开网页默认模板
    private $igt_link_template = [
        'app_id' => APPID, //设定接收的应用
        'app_key' => APPKEY, //用于鉴定身份是否合法
        'title' => '名医主刀', //通知标题
        'text' => '打开名医主刀官网', //通知内容
        'logo' => '', //通知图标，包含后缀
        'logo_url' => 'http://static.mingyizhudao.com/14701222767407', //通知图标url地址
        'is_ring' => true, //收到通知是否响铃，默认响铃
        'is_vibrate' => true, //收到通知是否震动,默认震动
        'is_clearable' => true, //通知是否可清除,默认可清除
        'url' => 'http://www.mingyizhudao.com', //点击通知后打开的网页地址i
        'duration' => '', //消息展示时间
        'push_info' => '', //ios使用
        'notify_style' => 0, //通知栏消息布局样式 0:系统样式 1:个推样式 默认0
    ];

    //点击通知下载默认模板
    private $igt_load_template = [
        'noty_icon' => '', //通知栏图标
        'logo_url' => '', //图标地址
        'noty_title' => '名医主刀更新', //通知栏标题
        'noty_content' => '您的app有最新版本,是否更新？', //通知栏内容
        'is_cleared' => true, //通知是否可清楚
        'is_belled' => true, //是否响铃
        'is_vibrational' => true, //是否震动
        'pop_title' => '您的名医主刀版本有更新了', //弹框标题
        'pop_content' => '', //弹框内容
        'pop_image' => '', //弹框图标
        'pop_button1' => '下载', //左边按钮名称
        'pop_button2' => '取消', //右边按钮名称
        'load_icon' => '', //下载图标(本地的则需要加file:// 网络图标则直接输入网络url地址)
        'load_title' => '名医主刀', //下载标题
        'load_url' => "http://dizhensubao.igexin.com/dl/com.ceic.apk", //下载地址
        'is_auto_install' => false, //是否自动安装
        'is_actived' => false, //安装完成后是否自动启动应用程序(默认否)
        'android' => false, //安卓标识
        'symbian' => false, //塞班标识
        'ios' => false, //苹果标识
        'duration' => '', //消息展示时间(格式yyyy-MM-dd HH:mm:ss)
        'notify_style' => 0 //通知栏消息布局样式 0:系统样式 1:个推样式 默认0
    ];

    //透传消息默认模板
    private $igt_trans_template = [
        'app_id' => APPID,
        'app_key' => APPKEY,
        'transmission_type' => 1, //4b 收到消息是否立即启动应用，1为立即启动，2则广播等待客户端自启动
        'transmission_content' => '名医主刀', //透传内容，不支持转义字符
        'duration' => '', //消息展示时间(格式yyyy-MM-dd HH:mm:ss)
        'push_info' => '', //ios使用
        'notify_style' => 0, //通知栏消息布局样式 0:系统样式 1:个推样式 默认0
    ];

    /**
     * 对单个用户推送消息
     * @param int $type
     * @param array $template
     */
    public function pushMessageToSingle($type=0,$template=[])
    {
        $this->pushMessage($type, $template);

        $igt = new IGeTui(HOST, APPKEY, MASTERSECRET);

        $method = $this->demo_name[$type];
        $igt_template = $this->$method();
        $message = new IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($igt_template);//设置推送消息类型
        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId(APPID);
        $target->set_clientId($this->client_id);
        //$target->set_alias(Alias);

        try {
            $response = $igt->pushMessageToSingle($message, $target);
        }catch(RequestException $e){
            $requstId =e.getRequestId();
            //失败时重发
            $response = $igt->pushMessageToSingle($message, $target,$requstId);
        }
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
     * @throws Exception
     */
    public function pushMessageToList($type=0,$template=[])
    {
        $this->pushMessage($type, $template);
        putenv("needDetails=true");
        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        //消息模版：
        // LinkTemplate:通知打开链接功能模板
        $method = $this->demo_name[$type];
        $igt_template = $this->$method();

        //定义"ListMessage"信息体
        $message = new IGtListMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($igt_template);//设置推送消息类型
        $message->set_PushNetWorkType(1);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        $contentId = $igt->getContentId($message);

        $targetList = [];
        foreach($this->client_id_list as $item) {
            $target = new IGtTarget();
            $target->set_appId(APPID);
            $target->set_clientId($item);
            $targetList[] = $target;
        }
        $response = $igt->pushMessageToList($contentId, $targetList);
        if(isset($response['result']) && $response['result'] == 'ok'){
            echo '推送成功';
        } else {
            var_dump($response);
        }
    }

    /**
     * 点击通知打开应用模板
     * @return IGtNotificationTemplate
     */
    public function IGtNotificationTemplateDemo()
    {
        // TODO: Implement IGtNotificationTemplateDemo() method.
        $notify_template = new IGtNotificationTemplate();
        $notify_template->set_appId($this->current_template['app_id']);  //应用appid
        $notify_template->set_appkey($this->current_template['app_key']); //应用appkey
        $notify_template->set_transmissionType($this->current_template['transmission_type']); //透传消息类型，包括1和2两种类型，1为强制打开应用，客户端SDK接收到消息后会立即启动客户端应用；2为等待客户端应用启动。
        $notify_template->set_transmissionContent($this->current_template['transmission_content']); //透传内容
        $notify_template->set_title($this->current_template['title']); //通知栏标题
        $notify_template->set_text($this->current_template['text']); //通知栏内容
        $notify_template->set_logo($this->current_template['logo']); //通知栏logo
        $notify_template->set_logoURL($this->current_template['logo_url']); //通知栏logo链接
        $notify_template->set_isRing($this->current_template['is_ring']); //是否响铃
        $notify_template->set_isVibrate($this->current_template['is_vibrate']); //是否震动
        $notify_template->set_isClearable($this->current_template['is_clearable']); //通知栏是否可清除
        //$notify_template->set_duration(); //设置ANDROID客户端在此时间区间内展示消息

        return $notify_template;
    }

    /**
     * 点击通知打开网页模板
     * @throws Exception
     */
    public function IGtLinkTemplateDemo()
    {
        $link_template =  new IGtLinkTemplate();
        $link_template->set_appId(APPID);                  //应用appid
        $link_template->set_appkey(APPKEY);                //应用appkey
        $link_template->set_title($this->current_template['title']);       //通知栏标题
        $link_template->set_text($this->current_template['text']);        //通知栏内容
        $link_template->set_logo($this->current_template['logo']);                       //通知栏logo
        $link_template->set_logoURL($this->current_template['logo_url']);                    //通知栏logo链接
        $link_template->set_isRing($this->current_template['is_ring']);                  //是否响铃
        $link_template->set_isVibrate($this->current_template['is_vibrate']);               //是否震动
        $link_template->set_isClearable($this->current_template['is_clearable']);             //通知栏是否可清除
        $link_template->set_url($this->current_template['url']); //打开连接地址
        //设置通知定时展示时间，结束时间与开始时间相差需大于6分钟，消息推送后，客户端将在指定时间差内展示消息（误差6分钟）
//        $begin = "2015-02-28 15:26:22";
//        $end = "2015-02-28 15:31:24";
//        $link_template->set_duration($begin,$end);
        return $link_template;
    }

    /**
     * 点击通知下载模板
     * @return IGtNotyPopLoadTemplate
     * @throws Exception
     */
    public function IGtNotyPopLoadTemplateDemo()
    {
        $pop_template = new IGtNotyPopLoadTemplate();

        $pop_template->set_appId(APPID);                      //应用appid
        $pop_template->set_appkey(APPKEY);                    //应用appkey
        //通知栏
        $pop_template->set_notyTitle($this->current_template['noty_title']);                 //通知栏标题
        $pop_template->set_notyContent($this->current_template['noty_content']); //通知栏内容
        $pop_template->set_notyIcon($this->current_template['noty_icon']);                      //通知栏logo
        $pop_template->set_isBelled($this->current_template['is_belled']);                    //是否响铃
        $pop_template->set_isVibrationed($this->current_template['is_vibrational']);               //是否震动
        $pop_template->set_isCleared($this->current_template['is_cleared']);                   //通知栏是否可清除
        //弹框
        $pop_template->set_popTitle($this->current_template['pop_title']);              //弹框标题
        $pop_template->set_popContent($this->current_template['pop_content']);            //弹框内容
        $pop_template->set_popImage($this->current_template['pop_image']);                      //弹框图片
        $pop_template->set_popButton1($this->current_template['pop_button1']);                //左键
        $pop_template->set_popButton2($this->current_template['pop_button2']);                //右键
        //下载
        $pop_template->set_loadIcon($this->current_template['load_icon']);                      //弹框图片
        $pop_template->set_loadTitle($this->current_template['load_title']);
        $pop_template->set_loadUrl($this->current_template['load_url']);
        $pop_template->set_isAutoInstall($this->current_template['is_auto_install']);
        $pop_template->set_isActived($this->current_template['is_actived']);

        //设置通知定时展示时间，结束时间与开始时间相差需大于6分钟，消息推送后，客户端将在指定时间差内展示消息（误差6分钟）
//        $begin = "2015-02-28 15:26:22";
//        $end = "2015-02-28 15:31:24";
//        $pop_template->set_duration($begin,$end);
        return $pop_template;
    }

    /**
     * 透传消息模板
     */
    public function IGtTransmissionTemplateDemo()
    {
        $trans_template =  new IGtTransmissionTemplate();
        //应用appid
        $trans_template->set_appId(APPID);
        //应用appkey
        $trans_template->set_appkey(APPKEY);
        //透传消息类型
        $trans_template->set_transmissionType(1);
        //透传内容
        $trans_template->set_transmissionContent($this->current_template['transmission_content']);
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        return $trans_template;
    }

    /**
     * 设置cid
     * @param $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * 设置推送列表
     * @param array $client_list
     */
    public function setClientList($client_list = [])
    {
        if(is_array($client_list)) {
            $this->client_id_list = $client_list;
        }
    }

    /**
     * 设置模板
     * @param array $template
     */
    public function setTemplate($template)
    {
        // TODO: Implement setTemplate() method.
        if(is_array($template)) {
            switch($this->getCurrentType()){
                case IGT_TRANS: //透传
                    $this->current_template = $this->igt_trans_template;
                    break;
                case IGT_NOTIFY: //点击打开应用
                    $this->current_template = $this->igt_notify_template;
                    break;
                case IGT_LINK: //点击打开网页
                    $this->current_template = $this->igt_link_template;
                    break;
                case IGT_LOAD: //点击下载
                    $this->current_template = $this->igt_load_template;
                    break;
                default:
                    $this->current_template = $this->igt_trans_template;
                    break;
            }
            $this->setValue($template);
        }
    }
}