<?php

class ApiViewDeletePatientByIds extends EApiViewService 
{
    private $patientIds = array();
    private $errorMessage = null;
    
    public function __construct(Array $patientIds) {
        parent::__construct();
        $this->patientIds = $patientIds;
    }
    
    protected function loadData() 
    {
        $result = $this->deletePatient();
        $this->setOutput($result);
    }

    //返回的参数
    protected function createOutput() {}
    
    private function deletePatient()
    {
        $isOk = true;
        
        if (arrayNotEmpty($this->patientIds)) {
            $transaction = PatientInfo::model()->dbConnection->beginTransaction();

            foreach ($this->patientIds as $id) {
                try{
                    PatientInfo::model()->setId((int)$id);
                    $result = PatientInfo::model()->delete(false);
                    $result == 0 && $isOk = false;
                }
                catch (CDbExpression $e) {
                    $isOk = false;
                    $transaction->rollBack();
                    $this->errorMessage = $e->getMessage();
                }
                
                if ($isOk === false) break;
            }

            $isOk === false ? $transaction->rollBack() : $transaction->commit();

            return $isOk;
        }
        
        return false;
    }
    
    private function setOutput($result)
    {
        $this->output = new stdClass();
        
        if ($result === false) {
            $this->output->status = self::RESPONSE_NO;
            $this->output->errorCode = 500;
            is_null($this->errorMessage) && $this->output->errorMsg = 'failed';
        } else {
            $this->output->status = self::RESPONSE_OK;
            $this->output->errorCode = 0;
            $this->output->errorMsg = 'success';
        }
    }
}
