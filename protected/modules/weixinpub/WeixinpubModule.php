<?php

class WeixinpubModule extends CWebModule {
    
    //公众号ID
    public $weixinpubId = 'myzdtest';
    
    //微信二维码存取路径
    public $qrcodePath = 'qrcode';
    
    
    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'weixinpub.models.*',
            'weixinpub.components.*',
            'weixinpub.lib.*',
        ));
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }

}
