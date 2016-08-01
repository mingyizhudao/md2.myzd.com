<?php

class MongoManager extends EMongoDocument
{
    public $source;
    public $user_host_ip;
    public $url;
    public $url_referrer;
    public $user_agent;
    public $user_host;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
  
    public function getCollectionName()
    {
        return 'myzd';
    }
    
	public function addInfo() 
	{
        $this->save();
    }
}

?>       
