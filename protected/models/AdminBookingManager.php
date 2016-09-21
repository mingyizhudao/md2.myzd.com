<?php

class AdminBookingManager
{
    public function setDockingCase($dockingCase ,$bkId, $refNo)
    {
        return Yii::app()->db->createCommand()->update('admin_booking', array('docking_case' => $dockingCase), 'booking_id = :bookingId and ref_no = :refNo', array(':bookingId' => $bkId, ':refNo' => $refNo));
    }
    
    public function getAdminBookingByBookingId($bookingId)
    {
        return Yii::app()->db->createCommand()->from('admin_booking')->select(array('date_invalid'))->where('booking_id = :bookingId and booking_type = 2', array(':bookingId' => $bookingId))->queryRow();
    }
       
    public function getAdminBookingByBookingRefNo($refNo)
    {
        return Yii::app()->db->createCommand()->from('admin_booking')->select(array('date_invalid'))->where('ref_no = :refNo', array(':refNo' => $refNo))->queryRow();
    }
}