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
    private $register_url = 'http://crm.dev.mingyizd.com/financial/yee/register'; //测试注册
    //private $register_url = 'http://crm560.mingyizd.com/financial/yee/register'; //正式注册
    //激活url
    private $activate_url = 'http://crm.dev.mingyizd.com/financial/yee/activation';  //测试激活
    //private $activate_url = 'http://crm560.mingyizd.com/financial/yee/activation';//正式激活
    //转账url
    private $giro_url = 'http://crm560.mingyizd.com/financial/yee/transfer';
    //资质文件url
    //private $file_url = 'http://file.mingyizhudao.com/api/loadrealauth?userId=';  //正式服务器
    private $file_url = 'http://121.40.127.64:8089/api/loadrealauth?userId='; //测试服务器

    private $base_dir = '.\\protected\\doc\\pic\\';

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
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $result = $this->executeByTimes($curl, 1);
        return json_decode($result);
    }

    public function uploadRemoteFile($path, $ledger_no, $file_type) {
        $ch = curl_init();
        if (class_exists('\CURLFile')) {
            $curlPost = array('file' => new \CURLFile(realpath($path)), 'ledgerno' => $ledger_no, 'filetype' => $file_type);
        } else {
            $curlPost = array('file' => '@' . realpath($path), 'ledgerno' => $ledger_no, 'filetype' => $file_type);
        }
        curl_setopt($ch, CURLOPT_URL, $this->activate_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1); //POST提交
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data);
    }

    public function getRemoteFile($url) {
        $count = preg_match("/com\/(.*?)\?/", $url, $match);
        $filename = '';
        if($count > 0) {
            $filename = $match[1];
        } else {
            return $filename;
        }
        $ch=curl_init();
        $timeout = 6;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $file = curl_exec($ch);
        curl_close($ch);

        if($file == false) {
            return false;
        }
        if(!@file_exists($this->base_dir) && !@mkdir($this->base_dir,0777,true)){
            return array('file_name'=>'','save_path'=>'','error'=>5);
        }
        $fp2=@fopen($this->base_dir.$filename,'w');
        fwrite($fp2,$file);
        fclose($fp2);
        unset($file,$url);

        return $this->base_dir.$filename;
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
                $arg['accountname'] = $bank->name;
                $arg['bankprovince'] = $bank->state_name;
                $arg['bankcity'] = $bank->city_name;
            } else {
                return ['code' => 1, 'msg' => '银行卡信息不完整', 'result' => []];
            }
        } else {
            return ['code' => 1, 'msg' => '未找到用户', 'result' => []];
        }

        $result = $this->HttpPost($this->register_url, $arg);
        if(isset($result->code) && $result->code == 1) {
            $bank->is_active = 1;
            $bank->ledger_no = $result->ledgerno;
            $bank->save();
        }

        if(isset($result->errcode)) {
            return ['code' => $result->errcode, 'msg' => $result->errmsg];
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
            'ledgerno' => ''
        ];
        $bank = DoctorBankCard::model()->getByAttributes(['user_id' => $user_id, 'is_active' => 1]);
        if($bank) {
            $arg['ledgerno'] = $bank->ledger_no;
        } else{
            return ['code' => 1, 'msg' => '信息不全'];
        }
        if($bank->is_active == 2) {
            return ['code' => 1, 'msg' => '账户已激活，无需再次激活！'];
        }
        if($bank->is_active == 3) {
            return ['code' => 1, 'msg' => '账户被拒接，请联系管理员！'];
        }
        //身份证照片获取
        $file_info = $this->HttpGet($this->file_url, $user_id);
        //银行卡照片信息获取
        $card_info = $this->HttpGet($this->file_url, $user_id.'&type=4');
        $file = [];
        $result = json_decode($file_info);
        $card_result = json_decode($card_info);
        if($result->status == 'ok' && isset($result->results->files) && !empty($result->results->files)) {
            $file += $result->results->files;
        }
        if($card_result->status == 'ok' && isset($card_result->results->files) && !empty($card_result->results->files)) {
            $file += $card_result->results->files;
        }

        //$ret = $this->uploadRemoteFile($this->base_dir.'265525841654736346.jpg', $arg['ledgerno'], 'BANK_CARD_FRONT');
        foreach($file as $key=>$item) {
            $path = $this->getRemoteFile($item->absFileUrl);
            if($path == '') {
                continue;
            }
            $type = '';
            if($item->certType == 2) {
                $type = 'ID_CARD_FRONT';
            } elseif($item->certType == 3) {
                $type = 'ID_CARD_BACK';
            } elseif($item->certType == 1) {
                $type = 'PERSON_PHOTO';
            } elseif($item->certType == 4) {
                $type = 'BANK_CARD_FRONT';
            }
            $result = $this->uploadRemoteFile($path, $arg['ledgerno'], $type);
            if(isset($result->code) && $result->code == 1) {
                $bank->is_active = 2;
                $bank->save();
            }else {
                $bank->is_active = 3;
                $bank->save();
            }

            if(isset($result->errcode)) {
                return ['code' => $result->errcode, 'msg' => $result->errmsg];
            }
        }

        return ['code' => 0, 'msg' => '激活成功!'];
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
     * @param $user_id
     * @param $amount
     * @return array
     */
    public function giroAccount($user_id, $amount) {
        $arg = [
            'ledgerno' => '', //子账户商户编号
            'amount' => '', //转账金额
        ];
        $output = [
            'code' => 0,
            'msg' => ''
        ];
        /**
         * @var $user User
         */
        $user = User::model()->getById($user_id);
        $bank = $user->getDoctorBank();
        $arg['ledgerno'] = $bank->ledger_no;
        $arg['amount'] = $amount;
        //插入提现记录
        $history = new UserAccountHistory();
        $history->user_id = $user_id;
        $history->requestid = '';
        $history->ledgerno = $bank->ledger_no;
        $history->amount = $amount;
        $history->save();
        $result = $this->HttpPost($this->giro_url, $arg);
        if(isset($result->code)){
            $output['code'] = $result->code;
            $output['msg'] = $result->msg;
            if($result->code == 1) {
                //更新状态
                $history->status = 1;
                $history->save();
            } else {
                $history->status = 2;
                $history->save();
            }
        }

        if(isset($result->errcode)) {
            $history->status = 2;
            $history->save();
            $output['code'] = $result->errcode;
            $output['msg'] = $result->errmsg;
        }
        return $output;
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