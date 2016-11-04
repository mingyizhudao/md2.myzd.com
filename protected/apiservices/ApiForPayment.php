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

    public static function instance() {
        if(self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * 子账户注册接口
     * @param $user_id
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
        if($user) {
            $arg['bindmobile'] = $user->getMobile();
            $arg['linkman'] = $user->getUserDoctorProfile()->getName();
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

    }
}