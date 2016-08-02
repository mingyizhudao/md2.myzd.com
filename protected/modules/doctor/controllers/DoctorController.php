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

}
