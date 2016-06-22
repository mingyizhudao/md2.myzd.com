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
                    'smsCode', 'ajaxVerifyCode', 'cardList', 'ajaxCardList', 'create', 'update', 'ajaxCreate'),
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
        $this->render('cardList');
    }

    //银行卡列表
    public function actionAjaxCardList() {
        $userId = $this->getCurrentUserId();
        $apiService = new ApiViewBankCardList($userId);
        $output = $apiService->loadApiViewData();
        $this->renderJsonOutput($output);
    }

    //新增
    public function actionCreate() {
        $form = new DoctorBankCardForm();
        $this->render('create', array(
            'model' => $form)
        );
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

}
