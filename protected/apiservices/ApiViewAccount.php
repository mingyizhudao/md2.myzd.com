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


    public function loadAccountCenter(){
        $output = new \stdClass();
        $output->total = 5000;
        $output->withdraw = 1000;
        $output->date_update= date("Y年m月d日", time());
        $output->cardbind = 1;
        $output->card_no = '123456799888888888';
        $output->card_name = '中国银行';
        $this->results = $output;
        return $this->loadApiViewData();
    }

    public function loadAccountDetailTotal() {
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


    public function loadAccountDetailWithdraw() {
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

    public function loadWithDrawDetail(){
        $output = new \stdClass();
        $output->bankinfo = '浦发银行(1234)';
        $output->enable_money = 5000;
        $this->results = $output;
        return $this->loadApiViewData();
    }

    public function loadWithDraw($money){
        return $this->loadApiViewData();
    }
}