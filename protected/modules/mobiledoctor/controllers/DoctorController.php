
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
     * @param CFilterChain $filterChain
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
     * @param CFilterChain $filterChain
     */
    public function filterPatientMRCreatorContext($filterChain) {
        $mr_id = null;
        if (isset($_GET['mrid'])) {
            $mr_id = $_GET['mrid'];
        } elseif (isset($_POST['patientbooking']['mrid'])) {
            $mr_id = $_POST['patientbooking']['mrid'];
        }
        $user = $this->loadUser();
        $this->loadPatientMRByIdAndCreatorId($mr_id, $user->getId());
        $filterChain->run();
    }

    /**
     * 修改医生信息
     * @param CFilterChain $filterChain
     */
    public function filterUserDoctorVerified($filterChain) {
        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        if (isset($doctorProfile)) {
            if ($doctorProfile->isVerified()) {
                $output = array('status' => 'no', 'error' => '您已通过实名认证,信息不可以再修改。');
                if (isset($_POST['plugin'])) {
                    echo CJSON::encode($output);
                    Yii::app()->end(200, true); //结束 返回200
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
            'userContext + viewContractDoctors viewCommonweal'
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
                'actions' => array('register', 'ajaxRegister', 'mobileLogin', 'forgetPassword', 'ajaxForgetPassword', 'getCaptcha',
                    'valiCaptcha', 'viewContractDoctors', 'ajaxLogin', 'viewCommonweal', 'wxlogin'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('logout', 'changePassword', 'createPatientBooking', 'ajaxContractDoctor', 'ajaxStateList', 'ajaxDeptList', 'viewDoctor',
                    'addPatient', 'view',
                    'profile', 'ajaxProfile', 'ajaxUploadCert', 'doctorInfo', 'doctorCerts', 'account', 'delectDoctorCert', 'uploadCert',
                    'updateDoctor', 'toSuccess', 'contract', 'ajaxContract', 'sendEmailForCert', 'ajaxViewDoctorZz', 'createDoctorZz', 'ajaxDoctorZz',
                    'ajaxViewDoctorHz', 'createDoctorHz', 'ajaxDoctorHz', 'drView', 'ajaxDoctorTerms', 'doctorTerms', 'ajaxJoinCommonweal', 'commonwealList', 'userView', 'savepatientdisease', 'searchDisease', 'diseaseCategoryToSub', 'diseaseByCategoryId', 'ajaxSearchDoctor', 'diseaseSearch', 'diseaseResult', 'doctorList', 'inputDoctorInfo', 'addDisease',
                    'questionnaire', 'ajaxQuestionnaire'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * 公益视图
     */
    public function actionViewCommonweal() {
        $user = $this->getCurrentUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $isCommonweal = false;
        $profile = false;
        if (isset($doctorProfile)) {
            $profile = true;
            $isCommonweal = $doctorProfile->isCommonweal();
        }
        $apiService = new ApiViewCommonwealDoctors();
        $output = $apiService->loadApiViewData();
        $this->render("viewCommonweal", array(
            'data' => $output, 'profile' => $profile, 'isCommonweal' => $isCommonweal,
        ));
    }

    /**
     * 公益列表
     */
    public function actionCommonwealList() {
        $apiService = new ApiViewCommonwealDoctors();
        $output = $apiService->loadApiViewData();
        $this->render("commonwealList", array(
            'data' => $output
        ));
    }

    /**
     * 加入名医公益
     * @throws CDbException
     */
    public function actionAjaxJoinCommonweal() {
        $output = array('status' => 'no');
        $user = $this->getCurrentUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $doctorProfile->is_commonweal = date('Y-m-d H:i:s');
        if ($doctorProfile->update(array('is_commonweal'))) {
            //调用远程接口
            $apiUrl = new ApiRequestUrl();
            $url = $apiUrl->getUrlCommonweal() . "?userid=" . $user->getId();
            $apiUrl->send_get($url);
            $output['status'] = 'ok';
        } else {
            $output['errors'] = $doctorProfile->getErrors();
        }
        $this->renderJsonOutput($output);
    }

    /**
     * 签约医生列表
     */
    public function actionViewContractDoctors() {
        $this->render("viewContractDoctors");
    }

    /**
     * 获取签约医生
     */
    public function actionAjaxContractDoctor() {
        $values = $_GET;
        $apiService = new ApiViewDoctorSearch($values);
        $output = $apiService->loadApiViewData();
        $this->renderJsonOutput($output);
    }

    /**
     * 获取城市列表
     */
    public function actionAjaxStateList() {
        $city = new ApiViewState();
        $output = $city->loadApiViewData();
        $this->renderJsonOutput($output);
    }

    /**
     * 获取科室分类
     */
    public function actionAjaxDeptList() {
        $apiService = new ApiViewDiseaseCategory();
        $apiService->loadDiseaseCategory();
        $output = $apiService->loadApiViewData();
        $this->renderJsonOutput($output);
    }

    /**
     * 获取医生信息
     * @param $id
     */
    public function actionViewDoctor($id) {
        $apiService = new ApiViewDoctor($id);
        $output = $apiService->loadApiViewData();
        $this->render("viewDoctor", array(
            'data' => $output
        ));
    }

    /**
     * 添加患者
     * @param $id
     */
    public function actionAddPatient($id) {
        $apiService = new ApiViewDoctor($id);
        $doctor = $apiService->loadApiViewData();
        //查看患者列表
        $userId = $this->getCurrentUserId();
        $apisvc = new ApiViewDoctorPatientList($userId, 100, 1);
        //调用父类方法将数据返回
        $patientList = $apisvc->loadApiViewData();
        $this->render("addPatient", array(
            'doctorInfo' => $doctor,
            'patientList' => $patientList
        ));
    }

    /**
     * 跳转至就诊意向页面
     * @param $doctorId
     * @param $patientId
     */
    public function actionCreatePatientBooking($doctorId, $patientId) {
        $userId = $this->getCurrentUserId();
        $apiService = new ApiViewBookingContractDoctor($patientId, $userId, $doctorId);
        $output = $apiService->loadApiViewData();
        $form = new PatientBookingForm();
        $this->render("addPatientBooking", array(
            'model' => $form,
            'data' => $output,
        ));
    }

    /**
     * 进入专家协议页面
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
     * 专家协议同意
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

    /**
     * 进入医生问卷调查页面
     */
    public function actionContract() {
        $this->render("contract");
    }

    /**
     * doctorView
     */
    public function actionDrView() {
        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $isContracted = empty($doctorProfile->date_contracted) ? false : true;
        $isContracted === false && $this->redirect(array('doctor/contract'));
        
        $this->render("drView");
    }

    /**
     * 医生查看自己能接受病人的转诊信息
     */
    public function actionAjaxViewDoctorZz() {
        $userId = $this->getCurrentUserId();
        $apiSvc = new ApiViewDoctorZz($userId);
        $output = $apiSvc->loadApiViewData(true);
        $this->renderJsonOutput($output);
    }

    /**
     * 进入保存或修改医生转诊信息的页面
     */
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

    /**
     * 保存或修改医生接受病人转诊信息
     */
    public function actionAjaxDoctorZz() {
        $post = $this->decryptInput();
        $output = array('status' => 'no');
        $userId = $this->getCurrentUserId();

        if (isset($post['DoctorZhuanzhenForm'])) {
            $values = $post['DoctorZhuanzhenForm'];
            $values['user_id'] = $userId;
            $doctorMgr = new MDDoctorManager();
            $output = $doctorMgr->createOrUpdateDoctorZhuanzhen($values);
            //专家签约
            $user = $this->loadUser();
            $doctorProfile = $user->getUserDoctorProfile();
            $doctorMgr->doctorContract($doctorProfile);
        } elseif (isset($post['form']['disjoin']) && $post['form']['disjoin'] == UserDoctorZhuanzhen::ISNOT_JOIN) {
            $doctorMgr = new MDDoctorManager();
            $output = $doctorMgr->disJoinZhuanzhen($userId);
        }
        //$this->renderJsonOutput($output);

        return $output;
    }

    /**
     * 医生查看自己接受的会诊信息
     */
    public function actionAjaxViewDoctorHz() {
        $userId = $this->getCurrentUserId();
        $apiSvc = new ApiViewDoctorHz($userId);
        $output = $apiSvc->loadApiViewData(true);
        //若该用户未填写则进入填写页面
        $this->renderJsonOutput($output);
    }

    /**
     * 进入保存或修改医生会诊 信息的页面
     */
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

    /**
     * 保存或修改医生会诊信息
     */
    public function actionAjaxDoctorHz() {
        $post = $this->decryptInput();
        $userId = $this->getCurrentUserId();
        $output = array('status' => 'no');
        if (isset($post['DoctorHuizhenForm'])) {
            $values = $post['DoctorHuizhenForm'];
            $values['user_id'] = $userId;
            $doctorMgr = new MDDoctorManager();
            $output = $doctorMgr->createOrUpdateDoctorHuizhen($values);
            //专家签约
            $user = $this->loadUser();
            $doctorProfile = $user->getUserDoctorProfile();
            $doctorMgr->doctorContract($doctorProfile);
        } elseif (isset($post['form']['disjoin']) && $post['form']['disjoin'] == UserDoctorZhuanzhen::ISNOT_JOIN) {
            $doctorMgr = new MDDoctorManager();
            $output = $doctorMgr->disJoinHuizhen($userId);
        }
        //$this->renderJsonOutput($output);
        return $output;
    }

    /**
     * 账户
     */
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
        $this->render('account', array(
            'userDoctorProfile' => $userDoctorProfile,
            'verified' => $verified,
            'doctorCerts' => $doctorCerts
        ));
    }

    /**
     * 医生信息查询
     */
    public function actionDoctorInfo() {
        $userId = $this->getCurrentUserId();
        $api_svc = new ApiViewDoctorInfo($userId);
        $output = $api_svc->loadApiViewData();
        $this->render('doctorInfo', array(
            'data' => $output
        ));
    }

    /**
     * 医生搜索
     * @param $name
     * @param $islike
     */
    public function actionAjaxSearchDoctor($name, $islike)
    {
        $api = new ApiViewSearchDoctor($name, $islike);
        $output = $api->loadApiViewData();
        $this->renderJsonOutput($output);
    }

    /**
     * 个人中心
     */
    public function actionView() {
        // var_dump(Yii::app()->user->id);exit;
        $user = $this->loadUser();  // User model
        $svc = new ApiViewUserInfo($user);
        $output = $svc->loadApiViewData();
        $this->render('view', array(
            'user' => $output
        ));
    }

    /**
     * 个人中心
     */
    public function actionUserView() {
        // var_dump(Yii::app()->user->id);exit;
        $user = $this->loadUser();  // User model
        $svc = new ApiViewUserInfo($user);
        $output = $svc->loadApiViewData();
        $this->render('view', array(
            'user' => $output
        ));
    }

    /**
     * 预约
     */
    public function actionAjaxContract() {
        $post = $this->$this->decryptInput();
        //需要发送电邮的数据
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
                    //判断信息是修改还是保存 发送电邮
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

    /**
     * 上传成功页面跳转
     */
    public function actionToSuccess() {
        $this->render('_success');
    }

    /**
     * 医生上传认证全部成功 添加任务
     */
    public function actionSendEmailForCert() {
        $userId = $this->getCurrentUserId();
        $type = StatCode::TASK_DOCTOR_CERT;
        $apiUrl = new ApiRequestUrl();
        $url = $apiUrl->getUrlDoctorInfoTask() . "?userid={$userId}&type={$type}";
        $this->send_get($url);
    }

    /**
     * 简介接口
     */
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
            $is_update = true;
            if (is_null($doctorProfile)) {
                $doctorProfile = new UserDoctorProfile();
                $is_update = false;
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
                if ($is_update) {
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

    /**
     * 修改医生认证信息添加task
     * @param $userId
     */
    public function createTaskProfile($userId) {
        $type = StatCode::TASK_DOCTOR_PROFILE_UPDATE;
        $apiRequest = new ApiRequestUrl();
        $remote_url = $apiRequest->getUrlDoctorInfoTask() . "?userid={$userId}&type={$type}";
        $this->send_get($remote_url);
    }

    /**
     * 简介
     * 1 为注册页面跳转 2为名医公益跳转
     * @param int $register
     */
    public function actionProfile($register = 0) {
        $returnUrl = $this->getReturnUrl($this->createUrl('doctor/view'));
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
        if ($register == 2) {
            $returnUrl = $this->createUrl('doctor/viewCommonweal');
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

    /**
     * 退出登录
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        //$this->redirect(Yii::app()->user->loginUrl);
        $this->redirect(Yii::app()->request->hostInfo . '/mobiledoctor/doctor/mobileLogin?loginType=sms&registerFlag=1');
    }

    /**
     * 手机用户登录
     */
    public function actionMobileLogin($loginType = 'sms', $registerFlag = 0) {
        $user = $this->getCurrentUser();
        //已登陆 跳转至主页
        if (isset($user)) {
            $this->redirect(array('view'));
        }
        $sms_form = new UserDoctorMobileLoginForm();
        $paw_form = new UserLoginForm();
        $sms_form->role = StatCode::USER_ROLE_DOCTOR;
        $paw_form->role = StatCode::USER_ROLE_DOCTOR;
        $returnUrl = $this->getReturnUrl($this->createUrl('doctor/view'));
        //失败 则返回登录页面
        $this->render("mobileLogin", array(
            'model' => $sms_form,
            'pawModel' => $paw_form,
            'returnUrl' => $returnUrl,
            'loginType' => $loginType,
            'registerFlag' => $registerFlag
        ));
    }

    /**
     * 添加患者病例
     */
    public function actionAddDisease()
    {
        $this->render('addDisease', array(
            'id' => Yii::app()->session['addPatientId'],
            'returnUrl' => Yii::app()->session['mobileDoctor_patientCreate_returnUrl']
        ));
    }

    /**
     * 病例搜索页
     */
    public function actionDiseaseSearch()
    {
        $this->render('diseaseSearch');
    }

    /**
     * 病例搜索结果页
     */
    public function actionDiseaseResult()
    {
        $this->render('diseaseResult');
    }

    /**
     * 医生搜索列表页
     */
    public function actionDoctorList()
    {
        $this->render('doctorList');
    }

    /**
     * 填写专家信息
     */
    public function actionInputDoctorInfo()
    {
        $this->render('inputDoctorInfo');
    }

    /**
     * 异步登陆
     */
    public function actionAjaxLogin() {
        $post = $this->decryptInput();
        $output = array('status' => 'no');
        $sms_form = $paw_form = '';
        if (isset($post['UserDoctorMobileLoginForm'])) {
            $loginType = 'sms';
            $sms_form = new UserDoctorMobileLoginForm();
            $values = $post['UserDoctorMobileLoginForm'];
            $sms_form->setAttributes($values, true);
            $sms_form->role = StatCode::USER_ROLE_DOCTOR;
            $sms_form->autoRegister = false;
            $userMgr = new UserManager();
            $isSuccess = $userMgr->mobileLogin($sms_form);
        } else if (isset($post['UserLoginForm'])) {
            $loginType = 'paw';
            $paw_form = new UserLoginForm();
            $values = $post['UserLoginForm'];
            $paw_form->setAttributes($values, true);
            $paw_form->role = StatCode::USER_ROLE_DOCTOR;
            $paw_form->rememberMe = true;
            $userMgr = new UserManager();
            $isSuccess = $userMgr->doLogin($paw_form);
        } else {
            $loginType = '';
            $output['errors'] = 'no data..';
            $isSuccess = false;
        }
        if ($isSuccess) {
            $output['status'] = 'ok';
        } else {
            if ($loginType == 'sms') {
                $output['errors'] = $sms_form->getErrors();
            } else {
                $output['errors'] = $paw_form->getErrors();
            }
            $output['loginType'] = $loginType;
        }
        $this->renderJsonOutput($output);
    }

    /**
     * 医生补全图片
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
     * 主页进入修改医生信息页面
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

    /**
     * 医生注册并自动登录
     */
    public function actionRegister() {
        $userRole = User::ROLE_DOCTOR;
        $form = new UserRegisterForm();
        $form->role = $userRole;
        $form->terms = 1;
        $this->render('register', array(
            'model' => $form,
        ));
    }

    /**
     * 医生注册
     */
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

    /**
     * 进入忘记密码页面
     */
    public function actionForgetPassword() {
        $form = new ForgetPasswordForm();
        $this->render('forgetPassword', array(
            'model' => $form,
        ));
    }

    /**
     * 忘记密码功能
     */
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
                        $output['errors']['errorInfo'] = '密码修改失败!';
                    }
                } else {
                    $output['errors']['username'] = '用户不存在';
                }
            } else {
                $output['errors'] = $form->getErrors();
            }
        }

        $this->renderJsonOutput($output);
    }

    /**
     * 保存患者疾病信息
     */
    public function actionSavePatientDisease()
    {
        $output = array('status' => 'no');
        if (isset($_POST['patient'])) {
            $values = $_POST['patient'];
            $patientDisease = new PatientManager();
            $output = $patientDisease->apiSaveDiseaseByPatient($values);
        } else {
            $output['errors'] = 'miss data...';
        }
    
        $this->renderJsonOutput($output);
    }

    /**
     * 根据关键字查询疾病
     * @param $name
     * @param $islike
     */
    public function actionSearchDisease($name, $islike)
    {
        $apiService = new ApiViewDisease();
        $output = $apiService->getDiseaseByName($name, $islike);
        $this->renderJsonOutput($output);
    }

    /**
     * 二级疾病类型列表
     */
    public function actionDiseaseCategoryToSub()
    {
        $apiService = new ApiViewDiseaseCategory();
        $apiService->getDiseaseCategoryToSub();
        $output = $apiService->loadApiViewData();
        $this->renderJsonOutput($output);
    }

    /**
     * 根据类型id获得疾病列表
     * @param int $categoryid
     */
    public function actionDiseaseByCategoryId($categoryid)
    {
        $apiService = new ApiViewDisease();
        $apiService->getDiseaseByCategoryId($categoryid);
        $output = $apiService->loadApiViewData();
        $this->renderJsonOutput($output);
    }

    /**
     * 微信调用接口
     * @param $userid
     * @param $returnUrl
     */
    public function actionWxlogin($userid, $returnUrl) {
        $userMgr = new UserManager();
        $output = $userMgr->wxlogin($userid);
        header('Location:' . $returnUrl);
        Yii::app()->end();
        //$this->renderJsonOutput($output);
    }

    /**
     * 修改密码
     */
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
                    $this->setFlashMessage('user.password', '密码修改成功！');
                }
            }
        }
        $this->render('changePassword', array(
            'model' => $form
        ));
    }

    /**
     * 验证
     * @param User $model
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 问卷调查页面
     */
    public function actionQuestionnaire()
    {
        $user_id = $this->getCurrentUserId();
        $doctorMgr = new MDDoctorManager();
        $hz_model = $doctorMgr->loadUserDoctorHuizhenByUserId($user_id);
        $hz_form = new DoctorHuizhenForm();
        $hz_form->initModel($hz_model);

        $zz_model = $doctorMgr->loadUserDoctorZhuanzhenByUserId($user_id);
        $zz_form = new DoctorZhuanzhenForm();
        $zz_form->initModel($zz_model);
        $this->render("questionnaire", array(
            'hz_model' => $hz_form,
            'zz_model' => $zz_form
        ));
    }

    /**
     * 问卷提交和修改
     */
    public function actionAjaxQuestionnaire()
    {
        $hzResutl = $this->actionAjaxDoctorHz();
        $zzResutl = $this->actionAjaxDoctorZz();
        $output = new stdClass();
        $output->status = 'ok';
        $output->errorMsg = 'success';
        $output->errorCode = 0;
        
        if ($hzResutl['status'] == 'no') {
            if (isset($hzResutl['errorMsg'])) {
                $output->status = 'no';
                $output->errorMsg = $hzResutl['errorMsg'];
            }
        }
        elseif ($zzResutl['status'] == 'no') {
            if (isset($zzResutl['errorMsg'])) {
                $output->status = 'no';
                $output->errorMsg = $zzResutl['errorMsg'];
            }
        }

        $this->renderJsonOutput($output);
    }
}
