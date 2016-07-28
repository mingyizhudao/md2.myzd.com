<?php

class QiniuController extends MobiledoctorController {

    /**
     * 安卓获取医生头像七牛上传权限
     */
    public function actionAjaxDrToken() {
        $url = 'http://121.40.127.64:8089/api/tokendrcert';
        $data = $this->send_get($url);
        $output = array('uptoken' => $data['results']['uploadToken']);
        $this->renderJsonOutput($output);
    }

    /**
     * 安卓获取病人病历七牛上传权限
     */
    public function actionAjaxPatientToken() {
        $url = 'http://121.40.127.64:8089/api/tokenpatientmr';
        //$url = 'http://192.168.31.119/file.myzd.com/api/tokenpatientmr';
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

}
