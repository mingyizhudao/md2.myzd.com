<?php

class CardManager
{
    public function deleteCardsByIds($userId, Array $ids)
    {
        $userMgr = new UserManager();
        $transaction = Yii::app()->db->beginTransaction();
        $isOk = false;
        
        foreach($ids as $id) {
            $card = $userMgr->loadCardByUserIdAndId($userId, $id);
            if (isset($card)) {
                $card->delete() === true && $isOk = true; 
            }
            
            if ($isOk === false) break;
        }
        
        $isOk === true ? $transaction->commit() : $transaction->rollback();

        return $isOk;
    }
}