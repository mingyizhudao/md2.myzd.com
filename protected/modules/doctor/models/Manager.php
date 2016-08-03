<?php

class Manager {

    public function saveMajor($values) {
        $output = array("status" => 'no');
        $form = new MajorForm();
        $form->setAttributes($values, true);
        if (arrayNotEmpty($form->diseaseList) && arrayNotEmpty($form->surgeryList)) {
            $doctorId = $form->id;
            $dbTran = Yii::app()->db->beginTransaction();
            try {
                $diseaseList = $form->diseaseList;
                foreach ($diseaseList as $v) {
                    $diseaseDoctorJoin = new NewDiseaseDoctorJoin();
                    $diseaseDoctorJoin->disease_id = $v;
                    $diseaseDoctorJoin->doctor_id = $doctorId;
                    $diseaseDoctorJoin->save();
                }
                $surgeryList = $form->surgeryList;
                foreach ($surgeryList as $values) {
                    $surjoin = new SurgeryDoctorJoin();
                    $surjoin->doctor_id = $doctorId;
                    $surjoin->surgery_id = $values['id'];
                    $surjoin->surgery_type = $values['type'];
                    $surjoin->surgery_count_min = $values['min'];
                    $surjoin->surgery_count_max = $values['max'];
                    $surjoin->save();
                }
                $dbTran->commit();
                $output['status'] = 'ok';
                $output['id'] = $doctorId;
            } catch (CDbException $e) {
                $output['errors'] = '网络连接异常';
                $dbTran->rollback();
                throw new CHttpException($e->getMessage());
            } catch (CException $e) {
                $output['errors'] = '网络连接异常';
                $dbTran->rollback();
                Yii::log("database table new_disease_doctor_join jdbc: " . $e->getMessage(), CLogger::LEVEL_ERROR, __METHOD__);
                throw new CHttpException($e->getMessage());
            }
        } else {
            $output['errors'] = 'miss data..';
        }
        return $output;
    }

    public function searchSubcat($id, $name) {
        $doctor = NewDoctor::model()->getById($id, array('diseaseCategory'));
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('t.name', $name);
        if (arrayNotEmpty($doctor->diseaseCategory)) {
            $sublist = $this->getSubIdForList($doctor->diseaseCategory);
            $criteria->addInCondition('t.category_id', $sublist);
        }
        return NewDisease::model()->findAll($criteria);
    }

    public function searchSurgery($id, $name) {
        $doctor = NewDoctor::model()->getById($id, array('diseaseCategory'));
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('t.name', $name);
        if (arrayNotEmpty($doctor->diseaseCategory)) {
            $sublist = $this->getSubIdForList($doctor->diseaseCategory);
            $criteria->addInCondition('t.category_id', $sublist);
        }
        return Surgery::model()->findAll($criteria);
    }

    public function getSubIdForList($list) {
        $output = array();
        foreach ($list as $value) {
            $output[] = $value->sub_cat_id;
        }
        return $output;
    }

}
