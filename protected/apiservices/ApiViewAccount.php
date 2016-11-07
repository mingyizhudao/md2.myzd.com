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
        $output->total = $bank->balance;
        $output->withdraw = $withdraw.'.00';
        $output->date_update= date("Y年m月d日", time());
        if($bank) {
            $output->cardbind = 1;
            $output->card_no = $bank->card_no;
            $output->card_name = $bank->bank;
        } else {
            $output->cardbind = 0;
            $output->card_no = '';
            $output->card_name = '';
        }

        $this->results = $output;
        return $this->loadApiViewData();
    }

    public function loadAccountDetailTotal($user_id) {
        $list = [];
        for($i = 2;$i<13;$i++) {
            $output = new \stdClass();
            $output->money = 5000;
            $output->date= date("Y年m月", strtotime('-'.$i.'months'));
            $list[] = $output;
        }
        $this->results = $list;
        return $this->loadApiViewData();
    }


    public function loadAccountDetailWithdraw($user_id) {
        $list = [];
        for($i = 2;$i<13;$i++) {
            $output = new \stdClass();
            $output->money = 5000;
            $output->date= date("Y年m月d H:i:s", strtotime('-'.$i.'days'));
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
            $output->enable_money = $bank->balance;
        } else {
            $output->bankinfo = '';
            $output->enable_money = 0;
        }

        $this->results = $output;
        return $this->loadApiViewData();
    }

    public function loadWithDraw($user_id, $money){
        return $this->loadApiViewData();
    }
}