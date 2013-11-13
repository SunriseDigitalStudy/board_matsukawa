<?php

require_once 'Bd/Orm/Main/Base/Account.php';

class Bd_Orm_Main_Account extends Bd_Orm_Main_Base_Account
{
    public static function hashPassword($raw_password){
        $salt = 'NoG70PKuxcY6t6c0jgR+675F0y5N5a/aDcjp16R65kI=2eOnXGUJZiaZgnNz7BP'
                . 'lesy5uSr86MGhEuJPD7UP/uE=lMppqWLCZOJYMNtM9w0EAvSGJFDdcTH6Q50By7JFXsE=';
        $value = '';
        for($i = 0; $i < 10000; ++$i ){
            $value = hash('sha256', $value . $raw_password . $salt);
        }
        return $value;
    }
    
    public function setRawPassword($raw_password){
        $this->setPassword(self::hashPassword($raw_password));
        return $this;
    }

}

