<?php

/**
 * Created by PhpStorm.
 * User: zhangpengcheng
 * Date: 2016/8/30
 * Time: 16:05
 */
header("Content-Type: text/html; charset=utf-8");

require_once(dirname(__FILE__).'/../sdk/getui/IGt.Push.php');
require_once(dirname(__FILE__).'/../sdk/getui/igetui/IGt.AppMessage.php');
require_once(dirname(__FILE__).'/../sdk/getui/igetui/IGt.APNPayload.php');
require_once(dirname(__FILE__).'/../sdk/getui/igetui/template/IGt.BaseTemplate.php');
require_once(dirname(__FILE__).'/../sdk/getui/IGt.Batch.php');
require_once(dirname(__FILE__).'/../sdk/getui/igetui/utils/AppConditions.php');

//http的域名
define('HOST','http://sdk.open.api.igexin.com/apiex.htm');


//定义常量, appId、appKey、masterSecret 采用本文档 "第二步 获取访问凭证 "中获得的应用配置
define('AD_APPKEY','g3whVDUGTd5g5aprwRWXe3');
define('AD_APPID','s2hw98XtfX6sH6msNNTvL2');
define('AD_MASTERSECRET','LdKMlaXGKj8r2c3u1axPq9');
define('AD_APPSECRET', 'ANxDjR8hjA7DhuM8ExEAG8');
define('AD_Alias', '请输入您的Alias'); //别名推送方式

define('IOS_APPKEY','JVTsoqWjbu5sXfnG9Ilw47');
define('IOS_APPID','MHqMHR0rScAj0GvRD06Im5');
define('IOS_MASTERSECRET','wPzO5uzggO672DUccX8Oi1');
define('IOS_APPSECRET', 'b5Tpsak6XgAAKK5HPtzOZ8');
define('IOS_Alias', '请输入您的Alias'); //别名推送方式

const IGT_TRANS = 0;
const IGT_NOTIFY = 1;
const IGT_LINK = 2;
const IGT_LOAD = 3;

class IGtFactory
{
    private $current_igt_type = 0; //现在使用的通知类型
    public $current_template = [];//使用的通知模板

    //可用的推送通知类型
    private $validate_type = [
        IGT_TRANS,
        IGT_NOTIFY,
        IGT_LINK,
        IGT_LOAD
    ];

    //推送区域
    public $province_list = [];

    public $demo_name = [
        0 => 'IGtTransmissionTemplateDemo',
        1 => 'IGtNotificationTemplateDemo',
        2 => 'IGtLinkTemplateDemo',
        3 => 'IGtNotyPopLoadTemplateDemo'
    ];

    /**
     * @param $type
     * @return IGtAndroid|IGtFactory|IGtIOS
     */
    public static function instance($type)
    {
        if($type == 'android') {
            return new IGtAndroid();
        } elseif($type == 'IOS') {
            return new IGtIOS();
        }
        return new self();
    }

    /**
     * @param int $type 推送消息类型，0:透传消息 1:点击打开应用消息 2:点击打开网页消息 3:点击下载 默认0
     * @param array $template
     * @throws Exception
     */
    public function pushMessage($type=0,$template=[])
    {
        if(in_array($type, $this->validate_type)) {
            $this->current_igt_type = $type;
            $this->setTemplate($template);
        }else {
            throw new Exception('推送消息类型错误');
        }
    }

    /**
     * 一般消息推送(应用群组)
     * @param int $type
     * @param array $template
     */
    public function pushMessageToApp($type=0, $template=[])
    {
        // TODO: Implement pushMessage() method.
        $this->pushMessage($type, $template);

        $igt = new IGeTui(HOST, AD_APPKEY, AD_MASTERSECRET);
        //定义透传模板，设置透传内容和收到消息是否立即启动应用
        $method = $this->demo_name[$type];
        $igt_template = $this->$method();

        $message = new IGtAppMessage();
        $message->set_isOffline(true); //是否支持离线发送
        $message->set_offlineExpireTime(10*60*1000); //离线消息有效期,时间单位毫秒
        $message->set_data($igt_template);

        $message->set_pushNetWorkType(0); //设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送

        $appIdList = array(AD_APPID); //发送目标app列表
        $phoneTypeList = array('ANDROID', 'IOS');
        //$provinceList = $this->province_list;
        //$tagList = array('hello');

        $cdt = new AppConditions();
        $cdt->addCondition2(AppConditions::PHONE_TYPE, $phoneTypeList);
        //$cdt->addCondition2(AppConditions::REGION, $provinceList);
        //$cdt->addCondition2(AppConditions::TAG, $tagList);

        $message->set_appIdList($appIdList);
        $message->set_conditions($cdt->getCondition());
        $response = $igt->pushMessageToApp($message);
        if(isset($response['result']) && $response['result'] == 'ok'){
            echo '推送成功';
        } else {
            var_dump($response);
        }
    }

    /**
     * 为模板赋值
     * @param $ori_template
     */
    public function setValue($ori_template)
    {
        foreach($ori_template as $key=>$value) {
            if(isset($this->current_template[$key])) {
                $this->current_template[$key] = $value;
            }
        }
    }

    /**
     * 获取当前通知模板
     * @return array
     */
    public function getCurrentTemplate()
    {
        return $this->current_template;
    }

    /**
     * 获取当前通知类型
     * @return int
     */
    public function getCurrentType()
    {
        return $this->current_igt_type;
    }
}