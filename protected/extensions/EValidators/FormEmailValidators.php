<?php

class EmailModel extends CFormModel {
    public $email;
    public function rules()
    {
        return array( array('email', 'email') );
    }
}
?>