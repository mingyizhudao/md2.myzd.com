<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author shuming
 */
class UserbankController extends MobiledoctorController {

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST requestf           
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
                'actions' => array(),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('viewInputKey', 'verifyKey', 'viewSetKey', 'ajaxSetKey',
                    'smsCode', 'ajaxVerifyCode', 'cardList', 'create', 'update', 'ajaxCreate', 'ajaxDelete', 'identify', 'ajaxAuth', 'myAccount', 'accountDetail', 'drawCash'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    //进入输入密码页面
    public function actionViewInputKey() {
        $user = $this->getCurrentUser();
        if (strIsEmpty($user->getUserKey())) {
            $this->redirect(array('viewSetKey'));
        }
        $this->render("viewInputKey");
        //获取加密参数
    }

    //ajax异步验证密码
    public function actionVerifyKey() {
        $output = array("status" => "no");
        $post = $this->decryptInput();
        if (isset($post['bank'])) {
            $user = $this->getCurrentUser();
            $values = $post['bank'];
            $userKey = $user->encryptUserKey($values['userkey']);
            if ($userKey === $user->getUserKey()) {
                $output['status'] = 'ok';
            } else {
                $output['errors'] = '密码输入错误!';
            }
        } else {
            $output['errors'] = 'miss data...';
        }
        $this->renderJsonOutput($output);
    }

    //进入设置密码页面
    public function actionViewSetKey() {
        $this->render("viewSetKey");
    }

    //用户银行账户密码设置
    public function actionAjaxSetKey() {
        $output = array("status" => "no");
        $post = $this->decryptInput();
        if (isset($post['bank'])) {
            $user = $this->getCurrentUser();
            $values = $post['bank'];
            $user->setUserKey($values['userkey']);
            $user->setUserKeyRaw($values['userkey']);
            if ($user->update(array('user_key', 'user_key_raw'))) {
                $output['status'] = 'ok';
            } else {
                $output['errors'] = $user->getErrors();
            }
        } else {
            $output['errors'] = 'miss data...';
        }
        $this->renderJsonOutput($output);
    }

    //进入验证码确认页面
    public function actionSmsCode() {
        $user = $this->getCurrentUser();
        $this->render("smsCode", array("mobile" => $user->getMobile()));
    }

    //异步验证验证码输入是否正确
    public function actionAjaxVerifyCode($code) {
        $output = array("status" => "no");
        $user = $this->getCurrentUser();
        $authMgr = new AuthManager();
        $authSmsVerify = $authMgr->verifyCodeForBank($user->getMobile(), $code, null);
        if ($authSmsVerify->isValid()) {
            $output['status'] = 'ok';
        } else {
            $output['errors'] = $authSmsVerify->getError('code');
        }
        $this->renderJsonOutput($output);
    }

    public function actionCardList() {
        $userId = $this->getCurrentUserId();
        $apiService = new ApiViewBankCardList($userId);
        $output = $apiService->loadApiViewData();
        $this->render('cardList', array('data' => $output));
    }

    //新增
    public function actionCreate() {
        $userId = $this->getCurrentUserId();
        $userDoctorProfile = new UserDoctorProfile();
        $result = $userDoctorProfile->getByUserId($userId);

        $form = new DoctorBankCardForm();
        $this->render('create', array(
            'model' => $form, 'name' => $result->name)
        );
    }
    
    //认证
    public function actionIdentify($card_id)
    {
        if(is_numeric($card_id) && $card_id > 0) {
            $doctorBankCard = new DoctorBankCard();
            $result = $doctorBankCard->findByPk($card_id);
            $cardType = '';
            if(!is_null($result)) {
                $cardType = $result->card_type;
            }
            $form = new DoctorBankCardAuthForm();
            $this->render('identify', array(
                'model' => $form, 'cardType' => $cardType)
            );
        }
    }
    
    //异步银行卡认证
    public function actionAjaxAuth()
    {
        $output = array("status" => "no");
        $mobile = $_GET['phone'];
        $code = $_GET['code'];
        
        //认证是否是银行卡预留手机号
        
        //认证验证码
        $user = $this->getCurrentUser();
        $authMgr = new AuthManager();
        $authSmsVerify = $authMgr->verifyCodeForBank($mobile, $code, null);
        if ($authSmsVerify->isValid()) {
            $output['status'] = 'ok';
        } else {
            $output['errors'] = $authSmsVerify->getError('code');
        }
        
        $this->renderJsonOutput($output);
    }

    //修改
    public function actionUpdate($id) {
        $userId = $this->getCurrentUserId();
        $userMgr = new UserManager();
        $card = $userMgr->loadCardByUserIdAndId($userId, $id);
        $form = new DoctorBankCardForm();
        $form->initModel($card);
        $this->render('update', array(
            'model' => $form));
    }

    //异步提交新增或保存信息
    public function actionAjaxCreate() {
        $output = array("status" => "no");
        $post = $this->decryptInput();
        if (isset($post['card'])) {
            $values = $post['card'];
            $values['user_id'] = $this->getCurrentUserId();
            $userMgr = new UserManager();
            $output = $userMgr->createCard($values);
        } else {
            $output['errors'] = 'miss data...';
        }
        $this->renderJsonOutput($output);
    }

    //删除银行卡信息
    public function actionAjaxDelete() 
    {
        $id = Yii::app()->getRequest()->getQuery('id');
        $userId = $this->getCurrentUserId();
        $output = array("status" => "no");

        if (is_null($id)) {
            //批删
            if (isset($_POST['ids'])) {
                if (count($_POST['ids']) > 0) {
                    $cardManager = new CardManager();
                    $result = $cardManager->deleteCardsByIds($userId, $_POST['ids']);
                    if ($result === true) {
                        $output['status'] = 'ok';
                    }
                    $result === true ? $output['status'] = 'ok' : $output['errors'] = '银行卡解绑失败!';
                }
            }
        } else {
            //单删
            $userMgr = new UserManager();
            $card = $userMgr->loadCardByUserIdAndId($userId, $id);
            if (isset($card)) {
                $card->delete();
                $output['status'] = 'ok';
            } else {
                $output['errors'] = '无权限操作!';
            }
        }

        $this->renderJsonOutput($output);
    }

    //我的账户页
    public function actionMyAccount() {
        $user = $this->getCurrentUser();
        $userMgr = new UserManager();
        $realAuthModel = $userMgr->loadUserRealNameAuthByUserId($user->id);
        $isRealAuth = arrayNotEmpty($realAuthModel) ? 1 : 0;

        $this->render('myAccount', array('count' => '', 'cash' => '', 'isRealAuth' => $isRealAuth));
    }
    
    //我的账户详细页
    public function actionAccountDetail() {
        $total = [];
        for($i = 2;$i<13;$i++) {
            $output = new \stdClass();
            $output->money = 5000;
            $output->date= date("Y年m月", strtotime('-'.$i.'months'));
            $total[] = $output;
        }
        $withdraw = [];
        for($i = 2;$i<13;$i++) {
            $output = new \stdClass();
            $output->money = 5000;
            $output->date= date("Y年m月d H:i:s", strtotime('-'.$i.'days'));
            $withdraw[] = $output;
        }

        $this->render('accountDetail', array(
                'total' => $total,
                'withdraw' => $withdraw)
        );
    }
    
    //提现页
    public function actionDrawCash() {
        $this->render('drawCash', array()
        );
    }


}
