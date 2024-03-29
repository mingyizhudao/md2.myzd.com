<?php

class UserDoctorRealAuth extends EFileModel {

    public $file_upload_field = 'file'; // $_FILE['file'].   

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'user_doctor_real_auth';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('user_id, uid, file_ext, file_name, file_url', 'required'),
            array('user_id, cert_type, file_size, display_order, has_remote', 'numerical', 'integerOnly' => true),
            array('uid', 'length', 'max' => 32),
            array('file_ext', 'length', 'max' => 10),
            array('mime_type', 'length', 'max' => 20),
            array('file_name, thumbnail_name, remote_file_key', 'length', 'max' => 40),
            array('file_url, thumbnail_url, base_url, remote_domain', 'length', 'max' => 255),
            array('has_remote, remote_file_key, remote_domain, date_updated, date_deleted', 'safe'),
            array('id, user_id, cert_type, uid, file_ext, mime_type, file_name, file_url, file_size, thumbnail_name, thumbnail_url, base_url, display_order, date_created, date_updated, date_deleted', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'udcUser' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'cert_type' => 'Cert Type',
            'uid' => 'Uid',
            'file_ext' => 'File Ext',
            'mime_type' => 'Mime Type',
            'file_name' => 'File Name',
            'file_url' => 'File Url',
            'file_size' => 'File Size',
            'thumbnail_name' => 'Thumbnail Name',
            'thumbnail_url' => 'Thumbnail Url',
            'base_url' => 'Base Url',
            'display_order' => 'Display Order',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'date_deleted' => 'Date Deleted',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserDoctorCert the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function initModel($ownerId, $file) {
        $this->setUserId($ownerId);
        $this->setFileAttributes($file);
    }

    public function saveModel() {
        if ($this->validate()) {    // validates model attributes before saving file.
            try {
                $fileSysDir = $this->getFileSystemUploadPath();
                createDirectory($fileSysDir);
                //Thumbnail.
                $thumbImage = Yii::app()->image->load($this->file->getTempName());
                // $image->resize(400, 100)->rotate(-45)->quality(75)->sharpen(20);
                $thumbImage->resize($this->thumbnail_width, $this->thumbnail_height);
                if ($thumbImage->save($fileSysDir . '/' . $this->getThumbnailName()) === false) {
                    throw new CException('Error saving file thumbnail.');
                }
                if ($this->file->saveAs($fileSysDir . '/' . $this->getFileName()) === false) {
                    throw new CException('Error saving file.');
                }

                return $this->save();
            } catch (CException $e) {
                $this->addError('file', $e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取医生实名认证文件
     * @param $userId
     * @param null $attrbutes
     * @param null $with
     * @return type
     */
    public function getRealAuthFilesByUserId($userId, $attrbutes = null, $with = null) {
        return $this->getAllByAttributes(array('t.user_id' => $userId), $with);
    }


    public function getFileUploadRootPath() {
        return Yii::app()->params['doctorRealAuthPath'];
    }

    public function getFileSystemUploadPath($folderName = null) {
        return parent::getFileSystemUploadPath($folderName);
    }

    public function getFileUploadPath($folderName = null) {
        return parent::getFileUploadPath($folderName);
    }

    public function deleteModel($absolute = true) {
        return parent::deleteModel($absolute);
    }

    /*     * ****** Accessors ****** */

    public function getUser() {
        return $this->udcUser;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($v) {
        $this->user_id = $v;
    }

}
