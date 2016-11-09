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
            'model' => $form, 'name' => $result->name, 'user_id' => $userId)
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
        $output = array("status" => "ok");
        $post = $this->decryptInput();
        $user = $this->getCurrentUser();
        //$post = $_POST;
        if (isset($post['card'])) {
            $values = $post['card'];
            $values['user_id'] = $user->id;
            $values['name'] = $user->username;
            $userMgr = new UserManager();
            $output = $userMgr->createCard($values);

            if($output['status'] == 'ok') {
                //注册&激活
                //易宝信息认证状态
                $bank = DoctorBankCard::model()->getByAttributes(['user_id' => $values['user_id']]);
                if($bank) {
                    $status = $bank->is_active;
                    if($status == 0 || $status == 1) {
                        $output['code'] = 0;
                        $output['msg'] = '账户信息认证中，请等待，谢谢！';
                        if($status == 0) {
                            try{
                                $paymentSer = new ApiForPayment();
                                $result = $paymentSer->registerAccount($values['user_id']);
                                if($result['code'] == '0') {
                                    $bank = DoctorBankCard::model()->getByAttributes(['user_id' => $values['user_id']]);
                                    if($bank->is_active == 1) {
                                        $result = $paymentSer->activateAccount($values['user_id']);
                                        $code = $result['code'];
                                        $output['status'] = $code ? 'no' : 'ok';
                                        $output['code'] = $code;
                                        $output['msg'] = $result['msg'];
                                    }
                                } else {
                                    $code = $result['code'];
                                    $output['status'] = $code ? 'no' : 'ok';
                                    $output['code'] = $code;
                                    $output['msg'] = $result['msg'];
                                }
                            }catch (Exception $ex) {
                                $output['status'] = 'no';
                                $output['code'] = 1;
                                $output['msg'] = '信息错误！';
                            }
                        }
                    } elseif($status == 3) {
                        $output['status'] = 'no';
                        $output['code'] = 1;
                        $output['msg'] = '账户信息未认证通过，请联系管理员，谢谢！';
                    }
                }
            }
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
        $card = DoctorBankCard::model()->getByAttributes(array('user_id' => $user->id));
        $withdraw = Yii::app()->db->createCommand()
            ->select('SUM(`amount`) as draw')
            ->from('user_account_history')
            ->where('user_id = :user_id', array('user_id' => $user->id))
            ->andWhere('ledgerno = :ledgerno', array('ledgerno' => $card->ledger_no))
            ->queryAll();
        $money = $total = 0;
        if($withdraw) {
            $money = $withdraw[0]['draw'];
        }
        $total = 0;
        $result = Yii::app()->db->createCommand()
            ->select('SUM(`amount`) as total')
            ->from('doctor_withdrawal')
            ->where('phone = :phone', array('phone' => $user->username))
            ->andWhere('is_withdrawal = 1')
            ->queryAll();
        if($result) {
            $total = empty($result[0]['total']) ? '0.00' : ltrim($result[0]['total'], 0);
        }
        $bindCard = 0;
        if($card) {
            $bindCard = 1;
        }
        $this->render('myAccount', array('count' => $total, 'cash' => $money, 'isRealAuth' => $isRealAuth, 'cardBind' => $bindCard, 'date_update' => date("Y年m月d日", time())));
    }
    
    //我的账户详细页
    public function actionAccountDetail() {
        $user = $this->getCurrentUser();
        $total = [];
        //每月统计详情
        $account_total = Yii::app()->db->createCommand()
            ->select('SUM(`amount`) as money, `create_date`')
            ->from('doctor_withdrawal')
            ->where('phone = :phone', array('phone' => $user->username))
            ->group('DATE_FORMAT(`create_date`, \'%y%m\')')
            ->queryAll();

        foreach($account_total as $item) {
            $output = new \stdClass();
            $output->money = empty($item['money']) ? '0.00' : $item['money'];
            $output->date= date("Y年m月", strtotime($item['create_date']));
            $total[] = $output;
        }
        $bank = $user->getDoctorBank();
        //提现详情
        $withdraw_history = Yii::app()->db->createCommand()
            ->select('amount, date_created')
            ->from('user_account_history')
            ->where('user_id = :user_id', array('user_id' => $user->id))
            ->andWhere('ledgerno=:ledgerno', array('ledgerno' => $bank->ledger_no))
            ->order('date_created desc')
            ->queryAll();
        $withdraw = [];
        foreach($withdraw_history as $item) {
            $output = new \stdClass();
            $output->money = $item['amount'];
            $output->date= date("Y年m月d H:i:s", strtotime($item['date_created']));
            $withdraw[] = $output;
        }

        $this->render('accountDetail', array(
                'total' => $total,
                'withdraw' => $withdraw)
        );
    }
    
    //提现页
    public function actionDrawCash() {
        $user = $this->getCurrentUser();
        $bank = $user->getDoctorBank();
        $output = new \stdClass();
        if($bank) {
            $output->bankinfo = $bank->bank.'('. substr($bank->card_no, -4) .')';
            $output->enable_money = $bank->balance > 0 ? ltrim($bank->balance, 0) : '0.00';
        } else {
            $output->bankinfo = '';
            $output->enable_money = 0;
        }

        $this->render('drawCash', array('withdraw' => $output));
    }

    public function actionAjaxWithdraw()
    {
        $user = $this->getCurrentUser();
        $bank = $user->getDoctorBank();
        $output = new \stdClass();
        $output->code = 0;
        $output->msg = '';
        if (!$bank) {
            $output->code = 1;
            $output->msg = '请您先添加银行卡！';
        } else {
            //易宝信息认证状态
            $status = $bank->is_active;
            if ($status == 0 || $status == 1) {
                $output->code = 1;
                $output->msg = '账户信息认证中，请等待，谢谢！';
//                if ($status == 0) {
//                    try {
//                        $paymentSer = new ApiForPayment();
//                        $paymentSer->registerAccount($user->id);
//                        $bank = DoctorBankCard::model()->getByAttributes(['user_id' => $user->id]);
//                        if ($bank->is_active == 1) {
//                            $result = $paymentSer->activateAccount($user->id);
//                            $code = $result['code'];
//                            $output->status = $code ? 'no' : 'ok';
//                            $output->code = $code;
//                            $output->msg = $result['msg'];
//                        }
//                    } catch (Exception $ex) {
//                        $output->status = 'no';
//                        $output->code = 1;
//                        $output->msg = '信息错误！';
//                    }
//                }
            } elseif ($status == 3) {
                $output->code = 1;
                $output->msg = '账户信息未认证通过，请联系管理员，谢谢！';
            }
        }
        $this->renderJsonOutput($output);
    }

    public function actionAjaxDraw() {
        //$post = $this->decryptInput();
        $output = new stdClass();
        $output->code = 0;
        $output->msg = '';
        if(isset($_POST['amount']) && $_POST['amount'] > 0) {
            $user = $this->getCurrentUser();
            $bank = $user->getDoctorBank();
            if($bank) {
                if(empty($bank->ledger_no)) {
                    $output->code = 1;
                    $output->msg = '账户未激活！';
                } else{
                    $pay = new ApiForPayment();
                    $result = $pay->giroAccount($user->id, $_POST['amount']);
                    $output->code = $result['code'];
                    $output->msg = $result['msg'];
                }
            }else{
                $output->code = 1;
                $output->msg = '银行卡未绑定！';
            }
        } else {
            $output->code = 1;
            $output->msg = '请输入取款金额！';
        }
        $this->renderJsonOutput($output);
    }
}
