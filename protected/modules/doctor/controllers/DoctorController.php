<?php

class DoctorController extends WebsiteController {

    //基本信息页面
    public function actionBasicView() {
        $form = new BasicInfoForm();
        $this->render('basicView', array('model' => $form));
    }

    //获取医生头像七牛上传权限
    public function actionAjaxDrToken() {
        $url = 'http://121.40.127.64:8089/api/tokendrcert';
        $data = $this->send_get($url);
        $output = array('uptoken' => $data['results']['uploadToken']);
        $this->renderJsonOutput($output);
    }

    //保存医生头像信息
    public function actionAjaxAvAtar($domain, $key) {
        $output = array('status' => 'no');
        if (strIsEmpty($domain) && strIsEmpty($key)) {
            $output['errors'] = 'miss data...';
        } else {
            $model = new NewDoctor();
            $model->remote_domain = $domain;
            $model->remote_file_key = $key;
            if ($model->save()) {
                $output['status'] = 'ok';
                $output['id'] = $model->getId();
            } else {
                $output['errors'] = $model->getErrors();
            }
        }
        $this->renderJsonOutput($output);
    }

    public function actionAjaxBasic() {
        $output = array('status' => 'no');
        if (isset($_POST['BasicForm'])) {
            $values = $_POST['BasicForm'];
            $form = new BasicInfoForm();
            $form->setAttributes($values, true);
            if ($form->validate()) {
                $model = NewDoctor::model()->getById($form->id);
                $attributes = $form->getSafeAttributes();
                $model->setAttributes($attributes, true);
                if ($model->update(array('name', 'gender', 'birthday', 'mobile', 'email'))) {
                    $output['status'] = 'ok';
                    $output['id'] = $model->getId();
                } else {
                    $output['errors'] = $model->getErrors();
                }
            } else {
                $output['errors'] = $form->getErrors();
            }
        } else {
            $output['errors'] = 'miss data...';
        }
        $this->renderJsonOutput($output);
    }

    //医生信息页面
    public function actionDoctrView($id) {
        $form = new IndustrialInfoForm();
        $form->id = $id;
        $form->loadOptions();
        $this->render('doctorView', array('model' => $form));
    }

    public function actionAjaxDoctor() {
        $output = array('status' => 'no');
        if (isset($_POST['DoctorForm'])) {
            $values = $_POST['DoctorForm'];
            $form = new IndustrialInfoForm();
            $form->setAttributes($values, true);
            if ($form->validate()) {
                $model = NewDoctor::model()->getById($form->id);
                $attributes = $form->getSafeAttributes();
                $model->setAttributes($attributes, true);
                if ($model->update(array('hospital_id', 'hospital_name', 'category_id', 'cat_name', 'clinic_title', 'academic_title'))) {
                    $output['status'] = 'ok';
                    $output['id'] = $model->getId();
                } else {
                    $output['errors'] = $model->getErrors();
                }
            } else {
                $output['errors'] = $form->getErrors();
            }
        } else {
            $output['errors'] = 'miss data...';
        }
        $this->renderJsonOutput($output);
    }

    //根据名字查询医院
    public function actionAjaxSearchHospital() {
        $input = $_GET;
        $apiview = new ApiViewHospitalSearch($input);
        $output = $apiview->loadApiViewData();
        $this->renderJsonOutput($output);
    }

}
