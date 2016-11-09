<?php
/**
 * Created by PhpStorm.
 * User: pengcheng
 * Date: 2016/11/3
 * Time: 13:53
 */
class ApiViewAccount extends EApiViewService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function loadData()
    {
        // TODO: Implement loadData() method.
    }

    public function createOutput()
    {
        $this->output = array(
            'status' => self::RESPONSE_OK,
            'errorCode' => 0,
            'errorMsg' => 'success',
            'results' => $this->results,
        );
    }


    public function loadAccountCenter($user_id){
        $bank = DoctorBankCard::model()->getByAttributes(['user_id' => $user_id]);
        $history = UserAccountHistory::model()->findAllByAttributes(['user_id' => $user_id]);
        $withdraw = 0;
        foreach($history as $item) {
            $withdraw += $item->amount;
        }
        $output = new \stdClass();

        if($bank) {
            $output->total = $bank->balance;
            $output->withdraw = $withdraw.'.00';
            $output->date_update= date("Y年m月d日", time());
            $output->cardbind = 1;
            $output->card_no = $bank->card_no;
            $output->card_name = $bank->bank;
            $output->is_first = $bank->is_first;
        } else {
            $output->total = 0;
            $output->withdraw = 0;
            $output->date_update= date("Y年m月d日", time());
            $output->cardbind = 0;
            $output->card_no = '';
            $output->card_name = '';
            $output->is_first = 0;
        }

        $this->results = $output;
        return $this->loadApiViewData();
    }

    public function loadAccountDetailTotal($user) {
        $list = [];
        //每月统计详情
        $account_total = Yii::app()->db->createCommand()
            ->select('SUM(`amount`) as money, `create_date`')
            ->from('doctor_withdrawal')
            ->where('phone = :phone', array('phone' => $user->username))
            ->group('DATE_FORMAT(`create_date`, \'%y%m\')')
            ->queryAll();

        foreach($account_total as $item) {
            $output = new \stdClass();
            $output->money = $item['money'];
            $output->date= date("Y年m月", strtotime($item['create_date']));
            $list[] = $output;
        }
        $this->results = $list;
        return $this->loadApiViewData();
    }


    public function loadAccountDetailWithdraw($user_id) {
        $list = [];
        //提现详情
        $withdraw_history = UserAccountHistory::model()->getAllByAttributes(['user_id' => $user_id]);
        foreach($withdraw_history as $item) {
            $output = new \stdClass();
            $output->money = $item->amount;
            $output->date= date("Y年m月d H:i:s", strtotime($item->date_created));
            $list[] = $output;
        }
        $this->results = $list;
        return $this->loadApiViewData();
    }

    public function loadWithDrawDetail($user_id){
        $bank = DoctorBankCard::model()->getByAttributes(['user_id' => $user_id]);
        $output = new \stdClass();
        if($bank) {
            $output->bankinfo = $bank->bank.'('. substr($bank->card_no, -4) .')';
            $output->enable_money = $bank->balance > 0 ? ltrim($bank->balance, 0) : '0.00';
        } else {
            $output->bankinfo = '';
            $output->enable_money = 0;
        }

        $this->results = $output;
        return $this->loadApiViewData();
    }

    public function loadWithDraw($user_id, $money){
        $pay = new ApiForPayment();
        $this->results = $pay->giroAccount($user_id, $money);
        return $this->loadApiViewData();
    }
}