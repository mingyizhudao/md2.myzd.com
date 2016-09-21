<?php

class AdminBookingManager
{
    public function setDockingCase($dockingCase ,$bkId, $refNo)
    {
        return Yii::app()->db->createCommand()->update('admin_booking', array('docking_case' => $dockingCase), 'booking_id = :bookingId and ref_no = :refNo', array('bookingId' => $bkId, 'refNo' => $refNo));
    }
        
}