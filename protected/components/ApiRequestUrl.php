<?php

class ApiRequestUrl {

    private $hostArray = array("http://md.mingyizhudao.com" => "http://crm560.mingyizd.com", "http://md.dev.mingyizd.com" => "http://crm.dev.mingyizd.com",
        "http://mdapi.mingyizhudao.com" => "http://crm560.mingyizd.com");
    private $admin_salesbooking_create = '/api/adminbooking';
    private $doctor_task = '/api/taskuserdoctor';
    private $patientMr_task = '/api/taskpatientmr';
    private $doctor_accept = '/api/doctoraccept';
    private $pay = '/api/tasksalseorder';
    private $da_task = '/api/taskpatientda';
    private $finished = '/api/operationfinished';

    private function getHostInfo() {
        $hostInfo = getHostInfo();
        if (isset($this->hostArray[$hostInfo])) {
            return $this->hostArray[$hostInfo];
        } else {
            return "http://crm.dev.mingyizd.com";
        }
    }

    public function getUrl($url) {
        return $this->getHostInfo() . $url;
    }

    public function getUrlAdminSalesBookingCreate() {
        return $this->getUrl($this->admin_salesbooking_create);
    }

    public function getUrlDoctorInfoTask() {
        return $this->getUrl($this->doctor_task);
    }

    public function getUrlPatientMrTask() {
        return $this->getUrl($this->patientMr_task);
    }

    public function getUrlDoctorAccept() {
        return $this->getUrl($this->doctor_accept);
    }

    public function getUrlDaTask() {
        return $this->getUrl($this->da_task);
    }

    public function getUrlPay() {
        return $this->getUrl($this->pay);
    }

    public function getUrlFinished() {
        return $this->getUrl($this->finished);
    }

    //微信接口
    public function paySuccess() {
        return getHostInfo() . '/weixinpub/Sendtempmessage/Paysuccess';
    }

    public function unPaid() {
        return getHostInfo() . '/weixinpub/Sendtempmessage/Unpaid';
    }

    public function updatestatus() {
        return getHostInfo() . '/weixinpub/Sendtempmessage/Updatestatus';
    }

    public function orderNotice() {
        return getHostInfo() . '/weixinpub/Sendtempmessage/Ordernotice';
    }

    public function reviewNotice() {
        return getHostInfo() . '/weixinpub/Sendtempmessage/Reviewnotice';
    }

    //模拟发送get请求
    public function send_get($url) {
        $result = file_get_contents($url, false);
        return json_decode($result, true);
    }

    //模拟发送post请求
    public static function send_post($url, $post_data = '', $timeout = 600) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($post_data != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

}
