<?php

require_once 'Bd/Db/Record.php';
require_once 'Bd/Orm/Main/Table/AutoLogin.php';
require_once 'Bd/Orm/Main/Const/AutoLogin.php';

abstract class Bd_Orm_Main_Base_AutoLogin extends Bd_Db_Record
{

    private static $_table_class = null;

    /**
     * @return Bd_Orm_Main_Table_AutoLogin
     */
    public static function getTable()
    {
        if(!self::$_table_class)
        {
            self::$_table_class = new Bd_Orm_Main_Table_AutoLogin();
            self::$_table_class->lock();
        }
        
        
        return self::$_table_class;
    }

    /**
     * @return Bd_Orm_Main_Table_AutoLogin
     */
    protected function _getTable()
    {
        return self::getTable();
    }

    /**
     * @return Bd_Orm_Main_Table_AutoLogin
     */
    public static function createTable()
    {
        return new Bd_Orm_Main_Table_AutoLogin();
    }

    /**
     * @return Bd_Orm_Main_Form_AutoLogin
     */
    public static function createForm(array $except = array(), Sdx_Db_Record $record = null)
    {
        return new Bd_Orm_Main_Form_AutoLogin('', array(), $except, $record);
    }

    public function getHash()
    {
        return $this->_get('hash');
    }

    /**
     * @return Bd_Orm_Main_AutoLogin
     */
    public function setHash($value)
    {
        return $this->_set('hash', $value);
    }

    public function getAccountId()
    {
        return $this->_get('account_id');
    }

    /**
     * @return Bd_Orm_Main_AutoLogin
     */
    public function setAccountId($value)
    {
        return $this->_set('account_id', $value);
    }

    public function getExpireDate()
    {
        return $this->_get('expire_date');
    }

    /**
     * @return Bd_Orm_Main_AutoLogin
     */
    public function setExpireDate($value)
    {
        return $this->_set('expire_date', $value);
    }


}

