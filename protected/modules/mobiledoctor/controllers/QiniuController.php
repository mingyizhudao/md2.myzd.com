<?php

class QiniuController extends MobiledoctorController {

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
        $url = 'http://file.mingyizhudao.com/api/token/tokenpatientmr';
        $data = $this->send_get($url);
        $output = array('uptoken' => $data['results']['uploadToken']);
        $this->renderJsonOutput($output);
    }

    //保存医生证明信息
    public function actionAjaxDrCert() {
        $output = array('status' => 'no');
        if (isset($_POST['cert'])) {
            $values = $_POST['cert'];
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

    //保存医生证明信息
    public function actionAjaxPatienMr() {
        $output = array('status' => 'no');
        if (isset($_POST['patient'])) {
            $values = $_POST['patient'];
            $form = new PatientMRFileForm();
            $form->setAttributes($values, true);
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
