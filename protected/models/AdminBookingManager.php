<?php

class AdminBooingManager
{
    public function setDockingCase($dockingCase ,$bkId, $refNo)
    {
        return Yii::app()->db->createCommand()->update('admin_booking', array('docking_case' => $dockingCase), array('booking_id' => $bkId, 'ref_no' => $refNo));
    }
        
}