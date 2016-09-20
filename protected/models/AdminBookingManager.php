<?php

class AdminBooingManager
{
    public function setDockingCase()
    {
        Yii::app()->db->createCommand()->update('admin_booking', array('docking_case' => 1), array());
    }
        
}