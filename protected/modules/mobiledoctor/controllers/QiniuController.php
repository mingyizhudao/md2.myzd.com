<?php

class QiniuController extends MobiledoctorController {

    const REAL_AUTH_PIC_NUM = 3;
    /**
     * 安卓获取医生头像七牛上传权限
     */
    public function actionAjaxDrToken() {
        $url = 'http://file.mingyizhudao.com/api/tokendrcert';
        $data = $this->send_get($url);
        $output = array('uptoken' => $data['results']['uploadToken']);
        $this->renderJsonOutput($output);
    }

    /**
     * 安卓获取病人病历七牛上传权限
     */
    public function actionAjaxPatientToken() {
        $url = 'http://file.mingyizhudao.com/api/tokenpatientmr';
        //$url = 'http://121.40.127.64:8089/file.myzd.com/api/tokenpatientmr';
        $data = $this->send_get($url);
        $output = array('uptoken' => $data['results']['uploadToken']);
        $this->renderJsonOutput($output);
    }

    //保存医生证明信息
    public function actionAjaxDrCert() {
        $post = $this->decryptInput();
        $output = array('status' => 'no');
        if (isset($post['cert'])) {
            $values = $post['cert'];
            $form = new UserDoctorCertForm();
            $form->setAttributes($values, true);
            $form->user_id = $this->getCurrentUserId();
            $form->initModel();
            if ($form->validate()) {
                $file = new UserDoctorCert();
                $file->setAttributes($form->attributes, true);
                if ($file->save()) {
                    $output['status'] = 'ok';
                    $output['fileId'] = $file->getId();
                } else {
                    $output['errors'] = $file->getErrors();
                }
            }
        } else {
            $output['errors'] = 'no data....';
        }
        $this->renderJsonOutput($output);
    }

    //保存病人的信息
    public function actionAjaxPatienMr() {
        $post = $this->decryptInput();
        $output = array('status' => 'no');
        if (isset($post['patient'])) {
            $values = $post['patient'];
            $form = new PatientMRFileForm();
            if ($this->isUserAgentIOS()) {
                $agent = PatientMRFile::WX_IOS;
            } else {
                $agent = PatientMRFile::WX_ANDROID;
            }
            $form->setAttributes($values, true);
            $form->file_agent = $agent;
            $form->creator_id = $this->getCurrentUserId();
            $form->initModel();
            if ($form->validate()) {
                $file = new PatientMRFile();
                $file->setAttributes($form->attributes, true);
                if ($file->save()) {
                    $output['status'] = 'ok';
                    $output['fileId'] = $file->getId();
                } else {
                    $output['errors'] = $file->getErrors();
                }
            }
        } else {
            $output['errors'] = 'no data....';
        }
        $this->renderJsonOutput($output);
    }

    public function actionAjaxDoctorRealAuth() {
        $post = $this->decryptInput(false);
        $output = array('status' => 'no');
        //三张照片一起上传
        if (isset($post['auth_file']) && is_array($post['auth_file']) && count($post['auth_file']) == self::REAL_AUTH_PIC_NUM) {
            foreach($post['auth_file'] as $key=> $value) {
                $form = new UserDoctorRealAuthForm();
                $form->setAttributes($value, true);
                $form->user_id = $this->getCurrentUserId();
                $form->cert_type = $key;
                $form->initModel();
                if ($form->validate()) {
                    $file = new UserDoctorRealAuth();
                    $file->setAttributes($form->attributes, true);
                    if ($file->save()) {
                        $output['status'] = 'ok';
                        $output['fileId'][$key] = $file->getId();
                    } else {
                        $output['errors'] = $file->getErrors();
                        break;
                    }
                }
            }
        } else {
            $output['errors'] = 'no data....';
        }
        $this->renderJsonOutput($output);
    }
}
