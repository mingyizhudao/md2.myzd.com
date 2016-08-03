<?php

class Manager {

    public function saveMajor($values) {
        $form = new MajorForm();
        $form->setAttributes($values, true);
        if ($form->validate()) {
            $model = NewDoctor::model()->getById($form->id);
            $attributes = $form->getSafeAttributes();
        }
    }

}
