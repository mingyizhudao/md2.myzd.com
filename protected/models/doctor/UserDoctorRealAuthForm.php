<?php

/**
 * Created by PhpStorm.
 * User: pengcheng
 * Date: 2016/10/13
 * Time: 11:20
 */
class UserDoctorRealAuthForm extends EFormModel
{
    public $user_id;
    public $uid;
    public $cert_type;
    public $file_type;
    public $file_name;
    public $file_ext;
    public $file_url;
    public $file_size;
    public $mime_type;
    public $has_remote;
    public $remote_domain;
    public $remote_file_key;

    public function rules() {
        return array(
            array('user_id, uid, cert_type, file_name, file_url, file_ext, file_size, has_remote, remote_file_key, remote_domain, mime_type', 'required'),
            array('user_id, cert_type, file_size, has_remote', 'numerical', 'integerOnly' => true),
            array('uid', 'length', 'max' => 32),
            array('remote_file_key', 'length', 'max' => 40),
            array('remote_domain', 'length', 'max' => 225),
            array('file_ext', 'length', 'max' => 10),
            array('mime_type', 'length', 'max' => 20),
        );
    }

    public function initModel() {
        $this->has_remote = StatCode::HAS_NOT_REMOTE;
        if(! strIsEmpty($this->remote_domain)) {
            $this->has_remote = StatCode::HAS_REMOTE;
        }
    }
}