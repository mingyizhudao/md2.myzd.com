
<?php

class DoctorController extends MobiledoctorController {

    public $defaultAction = 'view';
    private $model; // Doctor model
    private $patient;   // PatientInfo model
    private $patientMR; // PatientMR model

    public function filterUserDoctorProfileContext($filterChain) {
        $user = $this->loadUser();
        $user->userDoctorProfile = $user->getUserDoctorProfile();
        if (isset($user->userDoctorProfile) === false) {
            $redirectUrl = $this->createUrl('profile', array('addBackBtn' => 1));
            $currentUrl = $this->getCurrentRequestUrl();
            $redirectUrl.='?returnUrl=' . $currentUrl;
            $this->redirect($redirectUrl);
        }
        $filterChain->run();
    }

    public function filterPatientContext($filterChain) {
        $patientId = null;
        if (isset($_GET['id'])) {
            $patientId = $_GET['id'];
        } else if (isset($_POST['patient']['id'])) {
            $patientId = $_POST['patient']['id'];
        }

        $this->loadPatientInfoById($patientId);

        //complete the running of other filters and execute the requested action.
        $filterChain->run();
    }

    /**
     * @NOTE call this method after filterUserDoctorContext.
     * @param type $filterChain
     */
    public function filterPatientCreatorContext($filterChain) {
        $patientId = null;
        if (isset($_GET['pid'])) {
            $patientId = $_GET['pid'];
        } elseif (isset($_GET['id'])) {
            $patientId = $_GET['id'];
        } else if (isset($_POST['patient']['id'])) {
            $patientId = $_POST['patient']['id'];
        }
        $creator = $this->loadUser();

        $this->loadPatientInfoByIdAndCreatorId($patientId, $creator->getId());
        $filterChain->run();
    }

    /**
     * @NOTE call this method after filterUserDoctorContext.
     * @param type $filterChain
     */
    public function filterPatientMRCreatorContext($filterChain) {
        $mrid = null;
        if (isset($_GET['mrid'])) {
            $mrid = $_GET['mrid'];
        } elseif (isset($_POST['patientbooking']['mrid'])) {
            $mrid = $_POST['patientbooking']['mrid'];
        }
        $user = $this->loadUser();
        $this->loadPatientMRByIdAndCreatorId($mrid, $user->getId());
        $filterChain->run();
    }

    /**
     * �޸�ҽ����Ϣ
     * @param type $filterChain
     */
    public function filterUserDoctorVerified($filterChain) {
        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        if (isset($doctorProfile)) {
            if ($doctorProfile->isVerified()) {
                $output = array('status' => 'no', 'error' => '����ͨ��ʵ����֤,��Ϣ���������޸ġ�');
                if (isset($_POST['plugin'])) {
                    echo CJSON::encode($output);
                    Yii::app()->end(200, true); //���� ����200
                } else {
                    $this->renderJsonOutput($output);
                }
            }
        }
        $filterChain->run();
    }

    public function filterUserContext($filterChain) {
        $user = $this->loadUser();
        if (is_null($user)) {
            $redirectUrl = $this->createUrl('doctor/mobileLogin');
            $currentUrl = $this->getCurrentRequestUrl();
            $redirectUrl.='?returnUrl=' . $currentUrl;
            $this->redirect($redirectUrl);
        }
        $filterChain->run();
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST requestf           
            'userDoctorContext + profile ajaxProfile createPatient ajaxCreatePatient createPatientMR createBooking account',
            'patientContext + createPatientMR',
            'patientCreatorContext + createBooking',
            'userDoctorProfileContext + contract uploadCert',
            'userDoctorVerified + delectDoctorCert ajaxUploadCert ajaxUploadCert ajaxProfile',
            'userContext + viewContractDoctors'
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('register', 'ajaxRegister', 'mobileLogin', 'forgetPassword', 'ajaxForgetPassword', 'getCaptcha', 'valiCaptcha', 'viewContractDoctors', 'ajaxLogin'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('logout', 'changePassword', 'createPatientBooking', 'ajaxContractDoctor', 'ajaxStateList', 'ajaxDeptList', 'viewDoctor', 'addPatient', 'view', 'profile', 'ajaxProfile', 'ajaxUploadCert', 'doctorInfo', 'doctorCerts', 'account', 'delectDoctorCert', 'uploadCert', 'updateDoctor', 'toSuccess', 'contract', 'ajaxContract', 'sendEmailForCert', 'ajaxViewDoctorZz', 'createDoctorZz', 'ajaxDoctorZz', 'ajaxViewDoctorHz', 'createDoctorHz', 'ajaxDoctorHz', 'drView', 'ajaxDoctorTerms', 'doctorTerms'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    //����鿴ǩԼҽ���Ľ���
    public function actionViewContractDoctors() {
        $this->render("viewContractDoctors");
    }

    //��ȡǩԼҽ��
    public function actionAjaxContractDoctor() {
        $values = $_GET;
        $apiService = new ApiViewDoctorSearch($values);
        $output = $apiService->loadApiViewData(true);
        $this->renderJsonOutput($output);
    }

    //��ȡ�����б�
    public function actionAjaxStateList() {
        $city = new ApiViewState();
        $output = $city->loadApiViewData(true);
        $this->renderJsonOutput($output);
    }

    //��ȡ���ҷ���
    public function actionAjaxDeptList() {
        $apiService = new ApiViewDiseaseCategory();
        $output = $apiService->loadApiViewData(true);
        $this->renderJsonOutput($output);
    }

    //��ȡҽ����Ϣ
    public function actionViewDoctor($id) {
        $apiService = new ApiViewDoctor($id);
        $output = $apiService->loadApiViewData();
        $this->render("viewDoctor", array(
            'data' => $output
        ));
    }

    //��ӻ���
    public function actionAddPatient($id) {
        $apiService = new ApiViewDoctor($id);
        $doctor = $apiService->loadApiViewData();
        //�鿴�����б�
        $userId = $this->getCurrentUserId();
        $apisvc = new ApiViewDoctorPatientList($userId, 100, 1);
        //���ø��෽�������ݷ���
        $patientList = $apisvc->loadApiViewData();
        $this->render("addPatient", array(
            'doctorInfo' => $doctor,
            'patientList' => $patientList
        ));
    }

    //��ת����������ҳ��
    public function actionCreatePatientBooking($doctorId, $patientId) {
        $apiService = new ApiViewDoctor($doctorId);
        $doctor = $apiService->loadApiViewData();
        $userId = $this->getCurrentUserId();
        $apisvc = new ApiViewDoctorPatientInfo($patientId, $userId);
        $patient = $apisvc->loadApiViewData();
        $form = new PatientBookingForm();
        $this->render("addPatientBooking", array(
            'model' => $form,
            'doctorInfo' => $doctor,
            'patientInfo' => $patient
        ));
    }

    /**
     * ����ר��Э��ҳ��
     */
    public function actionDoctorTerms() {
        $user = $this->getCurrentUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $teamDoctor = 0;
        if (isset($doctorProfile)) {
            if ($doctorProfile->isTermsDoctor()) {
                $teamDoctor = 1;
            }
        }
        $returnUrl = $this->getReturnUrl($this->createUrl('doctor/view'));
        $this->render("doctorTerms", array(
            'teamDoctor' => $teamDoctor,
            'returnUrl' => $returnUrl
        ));
    }

    /**
     * ר��Э��ͬ��
     */
    public function actionAjaxDoctorTerms() {
        $output = array('status' => 'no');
        $user = $this->getCurrentUser();
        $doctorProfile = $user->getUserDoctorProfile();
        if (isset($doctorProfile)) {
            $doctorProfile->date_terms_doctor = date('Y-m-d H:i:s');
            if ($doctorProfile->update(array('date_terms_doctor'))) {
                $output['status'] = 'ok';
                $output['id'] = $doctorProfile->getId();
            } else {
                $output['error'] = $doctorProfile->getErrors();
            }
        } else {
            $output['error'] = 'no data..';
        }
        $this->renderJsonOutput($output);
    }

    //����ҽ���ʾ����ҳ��
    public function actionContract() {
        $this->render("contract");
    }

    public function actionDrView() {
        $this->render("drView");
    }

    //ҽ���鿴�Լ��ܽ��ܲ��˵�ת����Ϣ
    public function actionAjaxViewDoctorZz() {
        $userId = $this->getCurrentUserId();
        $apiSvc = new ApiViewDoctorZz($userId);
        $output = $apiSvc->loadApiViewData(true);
        $this->renderJsonOutput($output);
    }

    //���뱣����޸�ҽ��ת����Ϣ��ҳ��
    public function actionCreateDoctorZz() {
        $userId = $this->getCurrentUserId();
        $doctorMgr = new MDDoctorManager();
        $model = $doctorMgr->loadUserDoctorZhuanzhenByUserId($userId);
        $form = new DoctorZhuanzhenForm();
        $form->initModel($model);
        $this->render("createDoctorZz", array(
            'model' => $form
        ));
    }

    //������޸�ҽ�����ܲ���ת����Ϣ
    public function actionAjaxDoctorZz() {
        $post = $this->decryptInput();
        $output = array('status' => 'no');
        $userId = $this->getCurrentUserId();
        if (isset($post['DoctorZhuanzhenForm'])) {
            $values = $post['DoctorZhuanzhenForm'];
            $values['user_id'] = $userId;
            $doctorMgr = new MDDoctorManager();
            $output = $doctorMgr->createOrUpdateDoctorZhuanzhen($values);
            //ר��ǩԼ
            $user = $this->loadUser();
            $doctorProfile = $user->getUserDoctorProfile();
            $doctorMgr->doctorContract($doctorProfile);
        } elseif (isset($post['form']['disjoin']) && $post['form']['disjoin'] == UserDoctorZhuanzhen::ISNOT_JOIN) {
            $doctorMgr = new MDDoctorManager();
            $output = $doctorMgr->disJoinZhuanzhen($userId);
        }
        $this->renderJsonOutput($output);
    }

    //ҽ���鿴�Լ����ܵĻ�����Ϣ
    public function actionAjaxViewDoctorHz() {
        $userId = $this->getCurrentUserId();
        $apiSvc = new ApiViewDoctorHz($userId);
        $output = $apiSvc->loadApiViewData(true);
        //�����û�δ��д�������дҳ��
        $this->renderJsonOutput($output);
    }

    //���뱣����޸�ҽ������ ��Ϣ��ҳ��
    public function actionCreateDoctorHz() {
        $userId = $this->getCurrentUserId();
        $doctorMgr = new MDDoctorManager();
        $model = $doctorMgr->loadUserDoctorHuizhenByUserId($userId);
        $form = new DoctorHuizhenForm();
        $form->initModel($model);
        $this->render("createDoctorHz", array(
            'model' => $form
        ));
    }

    //������޸�ҽ��������Ϣ
    public function actionAjaxDoctorHz() {
        $post = $this->decryptInput();
        $userId = $this->getCurrentUserId();
        $output = array('status' => 'no');
        if (isset($post['DoctorHuizhenForm'])) {
            $values = $post['DoctorHuizhenForm'];
            $values['user_id'] = $userId;
            $doctorMgr = new MDDoctorManager();
            $output = $doctorMgr->createOrUpdateDoctorHuizhen($values);
            //ר��ǩԼ
            $user = $this->loadUser();
            $doctorProfile = $user->getUserDoctorProfile();
            $doctorMgr->doctorContract($doctorProfile);
        } elseif (isset($post['form']['disjoin']) && $post['form']['disjoin'] == UserDoctorZhuanzhen::ISNOT_JOIN) {
            $doctorMgr = new MDDoctorManager();
            $output = $doctorMgr->disJoinHuizhen($userId);
        }
        $this->renderJsonOutput($output);
    }

    public function actionAccount() {
        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $userMgr = new UserManager();
        $models = $userMgr->loadUserDoctorFilesByUserId($user->id);
        $doctorCerts = 0;
        $userDoctorProfile = 0;
        $verified = 0;
        if (arrayNotEmpty($models)) {
            $doctorCerts = 1;
        }
        if (isset($doctorProfile)) {
            $userDoctorProfile = 1;
            if ($doctorProfile->isVerified()) {
                $verified = 1;
            }
        }
        $this->render('account', array('userDoctorProfile' => $userDoctorProfile, 'verified' => $verified, 'doctorCerts' => $doctorCerts));
    }

    //ҽ����Ϣ��ѯ
    public function actionDoctorInfo() {
        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $isVerified = false;
        if (isset($doctorProfile)) {
            $isVerified = $doctorProfile->isVerified();
        }
        $userId = $user->getId();
        $apisvc = new ApiViewDoctorInfo($userId);
        $output = $apisvc->loadApiViewData();

        $this->render('doctorInfo', array(
            'data' => $output, 'isVerified' => $isVerified,
        ));
    }

    //�޸�����
    public function actionChangePassword() {
        $post = $this->$this->decryptInput();
        $user = $this->getCurrentUser();
        $form = new UserPasswordForm('new');
        $form->initModel($user);
        $this->performAjaxValidation($form);
        if (isset($post['UserPasswordForm'])) {
            $form->attributes = $post['UserPasswordForm'];
            $userMgr = new UserManager();
            $success = $userMgr->doChangePassword($form);
            if ($this->isAjaxRequest()) {
                if ($success) {
                    //do anything here
                    echo CJSON::encode(array(
                        'status' => 'true'
                    ));
                    Yii::app()->end();
                } else {
                    $error = CActiveForm::validate($form);
                    if ($error != '[]') {
                        echo $error;
                    }
                    Yii::app()->end();
                }
            } else {
                if ($success) {
                    // $this->redirect(array('user/account'));
                    $this->setFlashMessage('user.password', '�����޸ĳɹ���');
                }
            }
        }
        $this->render('changePassword', array(
            'model' => $form
        ));
    }

    //��������
    public function actionView() {
        // var_dump(Yii::app()->user->id);exit;
        $user = $this->loadUser();  // User model
        $profile = $user->getUserDoctorProfile();   // UserDoctorProfile model
        $data = new stdClass();
        $data->id = $user->getId();
        $data->mobile = $user->getMobile();
        $userMgr = new UserManager();
        $models = $userMgr->loadUserDoctorFilesByUserId($user->id);
        $doctorCerts = false;
        if (arrayNotEmpty($models)) {
            $doctorCerts = true;
        }
        $data->doctorCerts = $doctorCerts;
        if (isset($profile)) {
            $data->isProfile = true;
            $data->name = $profile->getName();
            //�Ƿ���ǩԼҽ��
            $data->verified = $profile->isVerified();
            $data->teamDoctor = $profile->isTermsDoctor();
        } else {
            $data->isProfile = false;
            $data->name = $user->getMobile();
            $data->verified = false;
            $data->teamDoctor = false;
        }
        $this->render('view', array(
            'user' => $data
        ));
    }

    public function actionAjaxContract() {
        $post = $this->$this->decryptInput();
        //��Ҫ���͵��ʵ�����
        $data = new stdClass();
        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $data->oldPreferredPatient = $doctorProfile->preferred_patient;
        $output = array('status' => 'no');
        $form = new DoctorContractForm();
        $form->initModel($doctorProfile);
        $data->scenario = $form->scenario;
        if (isset($post['DoctorContractForm'])) {
            $values = $post['DoctorContractForm'];
            $form->setAttributes($values);
            if ($form->validate()) {
                $doctorProfile->setAttributes($form->attributes);
                if ($doctorProfile->save(true, array('preferred_patient', 'date_contracted', 'date_updated'))) {
                    $data->dateUpdated = date('Y-m-d H:i:s');
                    $data->doctorProfile = $doctorProfile;
                    //�ж���Ϣ���޸Ļ��Ǳ��� ���͵���
                    $emailMgr = new EmailManager();
                    $emailMgr->sendEmailDoctorUpateContract($data);
                    $output['status'] = 'ok';
                    $output['salesOrder']['id'] = $doctorProfile->getId();
                } else {
                    $output['errors'] = $doctorProfile->getErrors();
                }
            } else {
                $output['errors'] = $form->getErrors();
            }
        } else {
            $output['error'] = 'invalid request';
        }
        $this->renderJsonOutput($output);
    }

    //�ϴ��ɹ�ҳ����ת
    public function actionToSuccess() {
        $this->render('_success');
    }

    /**
     * ҽ���ϴ���֤ȫ���ɹ� �������
     */
    public function actionSendEmailForCert() {
        $userId = $this->getCurrentUserId();
        $type = StatCode::TASK_DOCTOR_CERT;
        $apiUrl = new ApiRequestUrl();
        $url = $apiUrl->getUrlDoctorInfoTask() . "?userid={$userId}&type={$type}";
        //���ز������� $remote_url="http://192.168.31.119/admin/api/taskuserdoctor?userid={$userId}&type={$type}";
        $this->send_get($url);
    }

    public function actionAjaxProfile() {
        $post = $this->decryptInput();
        $output = array('status' => 'no');
        if (isset($post['doctor'])) {
            $values = $post['doctor'];
            $form = new UserDoctorProfileForm();
            $form->setAttributes($values, true);
            $form->initModel();
            if ($form->validate() === false) {
                $output['status'] = 'no';
                $output['errors'] = $form->getErrors();
                $this->renderJsonOutput($output);
            }
            $regionMgr = new RegionManager();
            $user = $this->loadUser();
            $userId = $user->getId();
            $doctorProfile = $user->getUserDoctorProfile();
            $isupdate = true;
            if (is_null($doctorProfile)) {
                $doctorProfile = new UserDoctorProfile();
                $isupdate = false;
            }
            $attributes = $form->getSafeAttributes();
            $doctorProfile->setAttributes($attributes, true);
            $doctorProfile->user_id = $userId;
            $doctorProfile->setMobile($user->username);
            $state = $regionMgr->loadRegionStateById($doctorProfile->state_id);
            if (isset($state)) {
                $doctorProfile->state_name = $state->getName();
            }
            $city = $regionMgr->loadRegionCityById($doctorProfile->city_id);
            if (isset($city)) {
                $doctorProfile->city_name = $city->getName();
            }
            if ($doctorProfile->save()) {
                if ($isupdate) {
                    $this->createTaskProfile($userId);
                }
                $output['status'] = 'ok';
                $output['doctor']['id'] = $doctorProfile->getUserId();
                $output['doctor']['profileId'] = $doctorProfile->getId();
                $output['doctor']['teamsDoctor'] = $doctorProfile->isTermsDoctor();
                $output['doctor']['verifiedDoctor'] = $doctorProfile->isVerified();
            } else {
                $output['status'] = 'no';
                $output['errors'] = $doctorProfile->getErrors();
            }
        }
        $this->renderJsonOutput($output);
    }

    //�޸�ҽ����֤��Ϣ���task
    public function createTaskProfile($userId) {
        $type = StatCode::TASK_DOCTOR_PROFILE_UPDATE;
        $apiRequest = new ApiRequestUrl();
        $remote_url = $apiRequest->getUrlAdminSalesBookingCreate() . "?userid={$userId}&type={$type}";
        //���ز������� $remote_url="http://192.168.31.119/admin/api/taskuserdoctor?userid={$userId}&type={$type}";
        $this->send_get($remote_url);
    }

    public function actionProfile($register = 0) {
        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $form = new UserDoctorProfileForm();
        $form->initModel($doctorProfile);
        $form->terms = 1;
        $returnUrl = $this->getReturnUrl($this->createUrl('doctor/doctorInfo'));
        $userMgr = new UserManager();
        $certs = $userMgr->loadUserDoctorFilesByUserId($user->id);
        if (arrayNotEmpty($certs) === false) {
            $returnUrl = $this->createUrl('doctor/uploadCert');
        }
        $this->render('profile', array(
            'model' => $form,
            'returnUrl' => $returnUrl,
            'register' => $register,
        ));
    }

    /**
     * @DELETE
     */
    public function actionCreatePatient() {
        $this->redirect(array('patient/create'));
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->user->loginUrl);
    }

    /**
     * �ֻ��û���¼
     */
    public function actionMobileLogin($loginType = 'sms') {
//         $res = Encryption::model()->findAll();
//         print_r(CJSON::decode(CJSON::encode($res)));exit;
        $user = $this->getCurrentUser();
        //�ѵ�½ ��ת����ҳ
        if (isset($user)) {
            $this->redirect(array('view'));
        }
        $smsform = new UserDoctorMobileLoginForm();
        $pawform = new UserLoginForm();
        $smsform->role = StatCode::USER_ROLE_DOCTOR;
        $pawform->role = StatCode::USER_ROLE_DOCTOR;
        $returnUrl = $this->getReturnUrl($this->createUrl('doctor/view'));
        //ʧ�� �򷵻ص�¼ҳ��
        $this->render("mobileLogin", array(
            'model' => $smsform,
            'pawModel' => $pawform,
            'returnUrl' => $returnUrl,
            'loginType' => $loginType
        ));
    }

    /**
     * �첽��½
     */
    public function actionAjaxLogin() {
        $post = $this->decryptInput();
        $output = array('status' => 'no');
        if (isset($post['UserDoctorMobileLoginForm'])) {
            $loginType = 'sms';
            $smsform = new UserDoctorMobileLoginForm();
            $values = $post['UserDoctorMobileLoginForm'];
            $smsform->setAttributes($values, true);
            $smsform->role = StatCode::USER_ROLE_DOCTOR;
            $smsform->autoRegister = false;
            $userMgr = new UserManager();
            $isSuccess = $userMgr->mobileLogin($smsform);
        } else if (isset($post['UserLoginForm'])) {
            $loginType = 'paw';
            $pawform = new UserLoginForm();
            $values = $post['UserLoginForm'];
            $pawform->setAttributes($values, true);
            $pawform->role = StatCode::USER_ROLE_DOCTOR;
            $pawform->rememberMe = true;
            $userMgr = new UserManager();
            $isSuccess = $userMgr->doLogin($pawform);
        } else {
            $output['errors'] = 'no data..';
        }
        if ($isSuccess) {
            $output['status'] = 'ok';
        } else {
            if ($loginType == 'sms') {
                $output['errors'] = $smsform->getErrors();
            } else {
                $output['errors'] = $pawform->getErrors();
            }
            $output['loginType'] = $loginType;
        }
        $this->renderJsonOutput($output);
    }

    /**
     * ҽ����ȫͼƬ
     */
    public function actionUploadCert() {

        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $isVerified = false;
        if (isset($doctorProfile)) {
            $isVerified = $doctorProfile->isVerified();
        }
        $id = $user->getId();
        $viewFile = 'uploadCert';
        if ($this->isUserAgentIOS()) {
            $viewFile .= 'Ios';
        } else {
            $viewFile .= 'Android';
        }
        $this->render($viewFile, array(
            'output' => array('id' => $id, 'isVerified' => $isVerified)
        ));
    }

    /**
     * ��ҳ�����޸�ҽ����Ϣҳ��
     */
    public function actionUpdateDoctor() {
        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $form = new UserDoctorProfileForm();
        $form->initModel($doctorProfile);
        $form->terms = 1;
        $this->render('updateDoctor', array(
            'model' => $form,
        ));
    }

    //ҽ��ע�Ტ�Զ���¼
    public function actionRegister() {
        $userRole = User::ROLE_DOCTOR;
        $form = new UserRegisterForm();
        $form->role = $userRole;
        $form->terms = 1;
        $this->render('register', array(
            'model' => $form,
        ));
    }

    public function actionAjaxRegister() {
        $post = $this->decryptInput();
        $userRole = User::ROLE_DOCTOR;
        $output = array('status' => 'no');
        if (isset($post['UserRegisterForm'])) {
            $form = new UserRegisterForm();
            $form->attributes = $post['UserRegisterForm'];
            $userMgr = new UserManager();
            $userMgr->registerNewUser($form);
            if ($form->hasErrors() === false) {
                $userMgr->autoLoginUser($form->username, $form->password, $userRole, 1);
                $output['status'] = 'ok';
                $output['register'] = '1';
            } else {
                $output['errors'] = $form->getErrors();
            }
        }
        $this->renderJsonOutput($output);
    }

    //������������ҳ��
    public function actionForgetPassword() {
        $form = new ForgetPasswordForm();
        $this->render('forgetPassword', array(
            'model' => $form,
        ));
    }

    //�������빦��
    public function actionAjaxForgetPassword() {
        $post = $this->decryptInput();
        $output = array('status' => 'no');
        $form = new ForgetPasswordForm();
        if (isset($post['ForgetPasswordForm'])) {
            $form->attributes = $post['ForgetPasswordForm'];
            if ($form->validate()) {
                $userMgr = new UserManager();
                $user = $userMgr->loadUserByUsername($form->username, StatCode::USER_ROLE_DOCTOR);
                if (isset($user)) {
                    $success = $userMgr->doResetPassword($user, null, $form->password_new);
                    if ($success) {
                        $output['status'] = 'ok';
                    } else {
                        $output['errors']['errorInfo'] = '�����޸�ʧ��!';
                    }
                } else {
                    $output['errors']['username'] = '�û�������';
                }
            } else {
                $output['errors'] = $form->getErrors();
            }
        }

        $this->renderJsonOutput($output);
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
