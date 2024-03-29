<?php

class PatientController extends MobiledoctorController {

    private $patient;

    /**
     * @param CFilterChain $filterChain
     * @throws CHttpException
     */
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
     * 检查用户是否已登录
     * @param CFilterChain $filterChain
     */
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
            'postOnly + delete', // we only allow deletion via POST request
            'userContext + create',
            'userDoctorContext + create ajaxCreate createPatientMR ajaxCreatePatientMR',
            'patientContext + createPatientMR updatePatientMR',
            'userContext + list'
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
                'actions' => array('list', 'create'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('ajaxTask', 'ajaxDrTask', 'view', 'createPatientMR', 'updatePatientMR', 'createBooking', 'ajaxCreate',
                    'ajaxCreatePatientMR', 'ajaxUploadMRFile', 'delectPatientMRFile', 'patientMRFiles', 'uploadMRFile', 'searchView',
                    'ajaxSearch', 'uploadDAFile', 'viewDaFile', 'ajaxDeleteDoctorPatient', 'chooseList'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * 病人用户创建
     */
    public function actionAjaxCreate() {
        $post = $this->decryptInput();
        $output = array('status' => 'no');
        if (isset($post['patient'])) {
            $values = $post['patient'];
            $form = new PatientInfoForm();
            $form->setAttributes($values, true);
            $form->creator_id = $this->getCurrentUserId();
            $form->country_id = 1;  // default country is China.
            if ($form->validate()) {
                $patientMgr = new PatientManager();
                $patient = $patientMgr->loadPatientInfoById($form->id);
                if (isset($patient) === false) {
                    $patient = new PatientInfo();
                }
                $patient->setAttributes($form->attributes, true);
                $patient->setAge();
                $regionState = RegionState::model()->getById($patient->state_id);
                $patient->state_name = $regionState->getName();
                $regionCity = RegionCity::model()->getById($patient->city_id);
                $patient->city_name = $regionCity->getName();
                if ($patient->save()) {
                    $output['status'] = 'ok';
                    $output['patient']['id'] = $patient->getId();
                    Yii::app()->session['addPatientId'] = $output['patient']['id'];
                } else {
                    $output['errors'] = $patient->getErrors();
                }
            } else {
                $output['errors'] = $form->getErrors();
            }
        } else {
            $output['error'] = 'data errors';
        }
        $this->renderJsonOutput($output);
    }

    /**
     * 病人信息创建界面
     */
    public function actionCreate() {
        $returnUrl = $this->getReturnUrl();
        if(!empty($returnUrl)) {
            Yii::app()->session['mobileDoctor_patientCreate_returnUrl'] = $returnUrl;
        }
        $user = $this->loadUser();
        $doctorProfile = $user->getUserDoctorProfile();
        $teamDoctor = 0;
        if (isset($doctorProfile)) {
            if ($doctorProfile->isVerified()) {
                if ($doctorProfile->isTermsDoctor() === false) {
                    $teamDoctor = 1;
                }
            }
        }
        $form = new PatientInfoForm();
        $form->initModel();
        $this->render("createPatient", array(
            'model' => $form,
            'teamDoctor' => $teamDoctor,
            'returnUrl' => $returnUrl
        ));
    }

    /**
     * 病人疾病信息更新加载
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdatePatientMR($id) {
        $patient = $this->loadPatientInfoById($id);
        $form = new PatientInfoForm();
        $form->initModel($patient);
        $this->render('updatePatientMR', array(
            'model' => $form
        ));
    }

    /**
     * 病人用户补全图片 type为是创建还是修改 返回不同的页面
     * @param $id
     */
    public function actionUploadMRFile($id) {
        $returnUrl = $this->getReturnUrl($this->createUrl('patientbooking/create'));
        $url = 'updateMRFile';
        if ($this->isUserAgentIOS()) {
            $url .= 'Ios';
        } else {
            $url .= 'Android';
        }
        $this->render($url, array(
            'output' => array('id' => $id, 'returnUrl' => $returnUrl)
        ));
    }

    /**
     * 进入上传出院小结页面
     * @param int $id
     */
    public function actionUploadDAFile($id) {
        $returnUrl = $this->getReturnUrl($this->createUrl('order/orderView'));
        $url = 'updateDAFile';
        if ($this->isUserAgentIOS()) {
            $url .= 'Ios';
        } else {
            $url .= 'Android';
        }
        $this->render($url, array(
            'output' => array('id' => $id, 'returnUrl' => $returnUrl)
        ));
    }

    /**
     * 展示上传小结页面
     */
    public function actionViewDaFile() {
        $this->render('viewDaFile');
    }

    /**
     * 通过病人id获取病人实例
     * @param $id
     * @return array|mixed|null
     * @throws CHttpException
     */
    private function loadPatientInfoById($id) {
        if ($this->patient === null) {
            $this->patient = PatientInfo::model()->getById($id);
            if ($this->patient === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->patient;
    }

    /**
     * 通过病人id和医生id获取病人实例
     * @param int $id patient_id
     * @param int $creatorId doctor_id
     * @return PatientInfo
     * @throws CHttpException
     */
    private function loadPatientInfoByIdAndCreatorId($id, $creatorId) {
        if (is_null($this->patient)) {
            $this->patient = PatientInfo::model()->getByIdAndCreatorId($id, $creatorId);
            if (is_null($this->patient)) {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        }
        return $this->patient;
    }

    /**
     * 异步删除患者病历图片
     * @param int $id 患者id
     */
    public function actionDelectPatientMRFile($id) {
        $userId = $this->getCurrentUserId();
        $userMgr = new UserManager();
        $output = $userMgr->delectPatientMRFileByIdAndCreatorId($id, $userId);
        $this->renderJsonOutput($output);
    }

//    /**
//     * 异步加载病人病历图片
//     * @param int $id patient_id
//     */
//    public function actionPatientMRFiles($id) {
//        $userId = $this->getCurrentUserId();
//        $apisvc = new ApiViewFilesOfPatient($id, $userId);
//        $output = $apisvc->loadApiViewData(true);
//        $this->renderJsonOutput($output);
//    }

    /**
     * 我的患者列表信息查询
     * @param int $page
     */
    public function actionList($page = 1) {
        $user = $this->loadUser();
        $userId = $user->getId();
        $doctorProfile = $user->getUserDoctorProfile();
        $teamDoctor = 0;
        if (isset($doctorProfile)) {
            if ($doctorProfile->isVerified()) {
                if ($doctorProfile->isTermsDoctor() === false) {
                    $teamDoctor = 1;
                }
            }
        }
        $page_size = 100;
        //service层
        $apiSvc = new ApiViewDoctorPatientList($userId, $page_size, $page);
        //调用父类方法将数据返回
        $output = $apiSvc->loadApiViewData();

        $dataCount = $apiSvc->loadCount();
        $this->render('list', array(
            'data' => $output, 'dataCount' => $dataCount, 'teamDoctor' => $teamDoctor
        ));
    }

    /**
     * 根据ids逻辑删除患者
     */
    public function actionAjaxDeleteDoctorPatient()
    {
        $patientIds = $_POST['patient_ids'];
        $apiSvc = new ApiViewDeletePatientByIds($patientIds);
        $output = $apiSvc->loadApiViewData();
        $this->renderJsonOutput($output);
    }
    
    /**
     * 进入搜索页面
     */
    public function actionSearchView() {
        $this->render('searchView');
    }

    /**
     * ajax查询
     * @param $name
     */
    public function actionAjaxSearch($name) {
        $userId = $this->getCurrentUserId();
        $apiSvc = new ApiViewPatientSearch($userId, $name, 3);
        $output = $apiSvc->loadApiViewData(true);
        $this->renderJsonOutput($output);
    }

    /**
     * 我的患者详情
     * @param $id
     */
    public function actionView($id) {
        $userId = $this->getCurrentUserId();
        //service层
        $apiSvc = new ApiViewDoctorPatientInfo($id, $userId);
        //调用父类方法将数据返回
        $output = $apiSvc->loadApiViewData();
        $this->render('view', array(
            'data' => $output
        ));
    }

    /**
     * 修改已创建的患者图片信息添加任务提示
     * @param $id
     */
    public function actionAjaxTask($id) {
        $apiRequest = new ApiRequestUrl();
        $remote_url = $apiRequest->getUrlPatientMrTask() . "?id={$id}";
        $this->send_get($remote_url);
    }

    /**
     * 上传出院小结完成生成task
     * @param $id
     */
    public function actionAjaxDrTask($id) {
        //微信推送
//         $wxMgr = new WeixinManager();
//         $wxMgr->uploadDr($id);
        //crm生成task
        $apiRequest = new ApiRequestUrl();
        $remote_url = $apiRequest->getUrlDaTask() . "?id={$id}";
        $this->send_get($remote_url);
    }

    /**
     * 填写预约单选择患者页
     */
    public function actionChooseList($page = 1)
    {
        $user = $this->loadUser();
        $userId = $user->getId();
        $doctorProfile = $user->getUserDoctorProfile();
        $teamDoctor = 0;
        if (isset($doctorProfile)) {
            if ($doctorProfile->isVerified()) {
                if ($doctorProfile->isTermsDoctor() === false) {
                    $teamDoctor = 1;
                }
            }
        }
        $page_size = 100;
        //service层
        $apiSvc = new ApiViewDoctorPatientList($userId, $page_size, $page);
        //调用父类方法将数据返回
        $output = $apiSvc->loadApiViewData();
        
        $dataCount = $apiSvc->loadCount();
        $this->render('chooseList', array(
            'data' => $output, 'dataCount' => $dataCount, 'teamDoctor' => $teamDoctor
        ));
    }
}
