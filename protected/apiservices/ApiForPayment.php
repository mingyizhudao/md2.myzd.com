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
        //$header = array("Content-Type:text/html;charset=UTF-8");

        //curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $result = $this->executeByTimes($curl, 1);
        return json_decode($result);
    }

    public function uploadRemoteFile($path, $ledger_no, $file_type) {
        //header('content-type:text/html;charset=utf8');
        $ch = curl_init();
        $curlPost = array('file' => new \CURLFile(realpath($path)), 'ledgerno' => $ledger_no, 'filetype' => $file_type);
        curl_setopt($ch, CURLOPT_URL, $this->activate_url);
        //curl_setopt($ch, CURLOPT_HEADER, 1);
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
        if(isset($result->code) && $result->code == 1) {
            $bank->is_active = 1;
            $bank->custom_number = $result->customnumber;
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
            'ledgerno' => ''
        ];
        $bank = DoctorBankCard::model()->getByAttributes(['user_id' => $user_id, 'is_active' => 1]);
        if($bank) {
            $arg['ledgerno'] = $bank->custom_number;
        } else{
            return;
        }

        $file_info = $this->HttpGet($this->file_url, $user_id);
        $result = json_decode($file_info);
        if($result->status == 'ok' && isset($result->results->files) && !empty($result->results->files)) {
            foreach($result->results->files as $key=>$item) {
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
                }
                $result = $this->uploadRemoteFile($path, $arg['ledgerno'], $type);
                if(isset($result->code) && $result->code == 1) {
                    $bank->is_active = 2;
                    $bank->save();
                }else {
                    $bank->is_active = 3;
                    $bank->save();
                }
            }
        }
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