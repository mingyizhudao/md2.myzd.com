<?php

class Autoload
{
//     function __construct() {
//         $this->_autoloadRegister();
//     }
    
//     private function _autoloadRegister() {
//         spl_autoload_register(array($this, 'baseVendorLoad'));
//         spl_autoload_register(array($this, 'commonload'));
//         spl_autoload_register(array($this, 'moduleLoad'));
//     }
    
//     public function commonload($class) {
//         $class = str_replace('\\', '/', $class);
//         $file = BASEPATH . $class . '.php';
//         is_file($file) && include $file;
//     }
    
//     public function moduleLoad($class) {
//         $class = str_replace('\\', '/', $class);
//         $file = MODULEPATH . $class . '.php';
//         is_file($file) && include $file;
//     }
    
//     public function baseVendorLoad($class) {
//         $class = str_replace('\\', '/', $class);
//         $file = BASEVENDOR . $class . '.php';
//         is_file($file) && include $file;
//     }
}