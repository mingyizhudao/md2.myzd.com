<?php

/**
 * Created by PhpStorm.
 * User: pengcheng
 * Date: 2016/11/3
 * Time: 11:32
 */
class ApiForPayment
{
    private static $_instance;

    public static function instance() {
        if(self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getRequest($data){

    }
}