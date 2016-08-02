<?php

class DoctorController extends WebsiteController {

    //基本信息页面
    public function actionBasicView() {
        $url = 'basicView';
        if ($this->isUserAgentIOS()) {
            $url .= 'Ios';
        } else {
            $url .= 'Android';
        }
        $form = new BasicInfoForm();
        $this->render($url, array('model' => $form));
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
                if ($model->update(array('hospital_id', 'hospital_name', 'sub_cat_id', 'sub_cat_name', 'clinic_title', 'academic_title'))) {
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
