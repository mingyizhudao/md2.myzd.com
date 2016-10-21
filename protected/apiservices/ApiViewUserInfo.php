<?php

class ApiViewUserInfo extends EApiViewService {

    /**
     * @var $user User
     */
    private $user;
    private $userMgr;

    public function __construct($user) {
        parent::__construct();
        $this->user = $user;
        $this->userMgr = new UserManager();
    }

    protected function createOutput() {
        $this->output = array(
            'status' => self::RESPONSE_OK,
            'errorCode' => 0,
            'errorMsg' => 'success',
            'results' => $this->results,
        );
    }

    protected function loadData() {
        $this->loadUserInfo();
    }

    public function loadUserInfo() {
        $profile = $this->user->getUserDoctorProfile();   // UserDoctorProfile model
        $cert_models = $this->userMgr->loadUserDoctorFilesByUserId($this->user->id);//医师认证信息
        $real_auth_model = $this->userMgr->loadUserRealNameAuthByUserId($this->user->id); //实名认证信息

        $doctorCerts = 0;
        $cert = false;
        $realNameAuth = 0;
        $userDoctorProfile = 0;

        if (arrayNotEmpty($cert_models)) {
            $doctorCerts = 1;
            $cert = true;
        }
        if(arrayNotEmpty($real_auth_model)) {
            $realNameAuth = 1;
        }
        $data = new stdClass();
        $data->hasKey = strIsEmpty($this->user->getUserKey()) ? false : true;
        $data->doctorCerts = $cert;
        if (isset($profile)) {
            $userDoctorProfile = 1;
            $userDoctorProfile = $userDoctorProfile + $profile->getProfileVerifyState();
            $doctorCerts = $doctorCerts == 0 ? 0 : $doctorCerts + $profile->getCertVerifyState();
            $realNameAuth = $realNameAuth ==0 ? 0 : $realNameAuth + $profile->getRealAuthState();

            $data->isProfile = $userDoctorProfile;
            $data->realAuth = $realNameAuth;
            $data->name = $profile->getName();
            //是否是签约医生
            $data->verified = $doctorCerts;
            $data->teamDoctor = $profile->isTermsDoctor();
            $data->isCommonweal = $profile->isCommonweal();
            $data->isContractDoctor = $profile->isContractDoctor();
        } else {
            $data->isProfile = 0;
            $data->name = $this->user->getMobile();
            $data->realAuth = 0;
            $data->verified = 0;
            $data->teamDoctor = false;
            $data->isCommonweal = false;
            $data->isContractDoctor = false;
        }
        $this->results->userInfo = $data;
    }

}
