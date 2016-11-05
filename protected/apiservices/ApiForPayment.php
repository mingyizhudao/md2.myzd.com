<?php

/**
 * Created by PhpStorm.
 * User: pengcheng
 * Date: 2016/11/3
 * Time: 11:32
 */
class ApiForPayment
{
    private static $_instance;

    //注册url
    private $register_url = 'http://crm.dev.mingyizd.com/financial/yee/register';
    //激活url
    private $activate_url = 'http://crm.dev.mingyizd.com/financial/yee/activation';
    //转账url
    private $giro_url = '';
    //资质文件url
    //private $file_url = 'http://file.mingyizhudao.com/api/loadrealauth?userId=';  //正式服务器
    private $file_url = 'http://121.40.127.64:8089/api/loadrealauth?userId='; //测试服务器

    public static function instance() {
        if(self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Http post请求
     * @param $url
     * @param $data
     * @return mixed
     */
    public function HttpPost($url, $data) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $header = array("Content-Type:text/html;charset=UTF-8");

        curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $result = $this->executeByTimes($curl, 1);
        return $result;
    }

    public function HttpGet($url, $id) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url.$id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $result = $this->executeByTimes($curl, 1);
        return $result;
    }
    /**
     * 失败执行机制
     * @param $curl
     * @param $times
     * @return mixed
     */
    public function executeByTimes($curl, $times) {
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        $code = $info["http_code"];

        if (curl_errno($curl) != 0 && $code != 200) {
            $times--;
            if ($times > 0) {
                $result = $this->executeByTimes($curl, $times);
            }
        }
        curl_close($curl);

        return $result;
    }

    /**
     * @param $bank DoctorBankCard
     * @return string
     */
    public function checkValid($bank) {
        $error = [
            '1002' => '联系人姓名未填写',
            '1003' => '身份证号未填写',
            '1004' => '银行卡号未填写',
            '1005' => '银行卡开户行未填写',
            '1006' => '银行卡开户名未填写',
            '1007' => '银行卡开户省未填写',
            '1008' => '银行开户市未填写',
        ];
        $ret_msg = '';
        if($bank->identification_card == ''){
            $ret_msg = $error['1003'];
        }
        if($bank->name == '') {
            $ret_msg = $error['1002'];
        }
        if($bank->card_no == '') {
            $ret_msg = $error['1004'];
        }
        if($bank->bank == '') {
            $ret_msg = $error['1005'];
        }
        if($bank->state_name == '') {
            $ret_msg = $error['1007'];
        }
        if($bank->city_name == '') {
            $ret_msg = $error['1008'];
        }

        return $ret_msg;
    }
    /**
     * 账户查询接口
     * @param $user_id
     */
    public function accountCheck($user_id) {

    }

    /**
     * 子账户注册接口
     * @param $user_id
     * @return array
     */
    public function registerAccount($user_id){
        $arg = [
            'bindmobile' => '',  //绑定手机号
            'linkman' => '',     //联系人姓名
            'idcard' => '',      //个人身份证号
            'bankaccountnumber' => '', //银行卡号
            'bankname' => '',   //银行卡开户行
            'accountname' => '', //银行卡开户名
            'bankprovince' => '', //银行开户省
            'bankcity' => '', //银行卡开户市

        ];

        /**
         * @var $user User
         */
        $user = User::model()->getById($user_id);
        $bank = '';
        if($user) {
            $arg['bindmobile'] = $user->getMobile();
            $profile = $user->getUserDoctorProfile();
            $username = '';
            if($profile) {
                $username = $profile->getName();
            } else {
                return ['code' => 1, 'msg' => '用户信息基本不完整', 'result' => []];
            }

            if($username == '') {
                return ['code' => 1, 'msg' => '用户手机未填写', 'result' => []];
            }

            $arg['linkman'] = $username;
            $bank = DoctorBankCard::model()->getByAttributes(['user_id' => $user_id, 'is_active' => 0]);
            $msg = $this->checkValid($bank);
            if($bank && $msg == '') {
                $arg['idcard'] = $bank->identification_card;
                $arg['bankaccountnumber'] = $bank->card_no;
                $arg['bankname'] = $bank->bank;
                $arg['accountname'] = $username;
                $arg['bankprovince'] = $bank->state_name;
                $arg['bankcity'] = $bank->city_name;
            } else {
                return ['code' => 2, 'msg' => '银行卡信息不完整', 'result' => []];
            }
        } else {
            return ['code' => 1, 'msg' => '未找到用户', 'result' => []];
        }

        $result = $this->HttpPost($this->register_url, $arg);
        if(isset($result['resultLocale']) && $result['resultLocale']['code'] == 1) {
            $bank->is_active = 1;
            $bank->ledgerno = $result['resultLocale']['ledgerno'];
            $bank->save();
        }
        return ['code' => 0, 'msg' => '', 'result' => $result];
    }

    /**
     * 激活账户
     * @param $user_id
     * @return array
     */
    public function activateAccount($user_id) {
        $arg = [
            'file' => [],
        ];
        $file = [];
        $file_info = $this->HttpGet($this->file_url, $user_id);
        $result = json_decode($file_info);
        if($result->status == 'ok' && isset($result->results->files) && !empty($result->results->files)) {
            foreach($result->results->files as $item) {
                $file = ['type' => $item->certType, 'url' => '@'.$item->absFileUrl];
                break;
            }
        }
        if(empty($file)) {
            return ['code' => 1, 'msg' => '用户照片未上传', 'result' => []];
        }
        $arg['file'] = $file;
//        $bank = DoctorBankCard::model()->getByAttributes(['user_id' => $user_id, 'is_active' => 0]);
//        if($bank) {
//            $arg['ledgerno'] = $bank->ledgerno;
//        } else {
//            return ['code' => 2, 'msg' => '用户未添加银行卡', 'result' => []];
//        }
        $result = $this->HttpPost($this->activate_url, $arg);
//        if(isset($result['resultLocale']) && $result['resultLocale']['code'] == 1) {
//            $bank->is_active = 1;
//            $bank->ledgerno = $result['resultLocale']['ledgerno'];
//            $bank->save();
//        }
        return ['code' => 0, 'msg' => '', 'result' => $result];
    }

    /**
     * 账户信息修改接口
     * @param $data
     */
    public function updateAccount($data) {
        $arg = [
            'bankaccountnumber' => '', //银行卡号
            'bankname' => '', //开户行
            'accountname' => '', //开户名
            'bankprovince' => '', //开户省
            'bankcity' => '', //开户市
            'callbackurl' => '', //后台回调地址
            'bindmobile' => '', //绑定手机号
        ];
    }

    /**
     * 转账接口
     */
    public function giroAccount() {
        $file_info =
        $arg = [
            'ledgerno' => '', //子账户商户编号
            'amount' => '', //转账金额
        ];
        $result = $this->HttpPost($this->activate_url, $arg);
    }


    /**
     * 转账查询接口
     */
    public function giroHistory() {

    }

    /**
     * 账户余额接口
     */
    public function accountBalance() {
        $ledgerno = '';
    }
}