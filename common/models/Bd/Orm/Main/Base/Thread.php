<?php

require_once 'Bd/Db/Record.php';
require_once 'Bd/Orm/Main/Table/Thread.php';
require_once 'Bd/Orm/Main/Const/Thread.php';

abstract class Bd_Orm_Main_Base_Thread extends Bd_Db_Record
{

    private static $_table_class = null;

    /**
     * @return Bd_Orm_Main_Table_Thread
     */
    public static function getTable()
    {
        if(!self::$_table_class)
        {
            self::$_table_class = new Bd_Orm_Main_Table_Thread();
            self::$_table_class->lock();
        }
        
        
        return self::$_table_class;
    }

    /**
     * @return Bd_Orm_Main_Table_Thread
     */
    protected function _getTable()
    {
        return self::getTable();
    }

    /**
     * @return Bd_Orm_Main_Table_Thread
     */
    public static function createTable()
    {
        return new Bd_Orm_Main_Table_Thread();
    }

    /**
     * @return Bd_Orm_Main_Form_Thread
     */
    public static function createForm(array $except = array(), Sdx_Db_Record $record = null)
    {
        return new Bd_Orm_Main_Form_Thread('', array(), $except, $record);
    }

    public function getId()
    {
        return $this->_get('id');
    }

    /**
     * @return Bd_Orm_Main_Thread
     */
    public function setId($value)
    {
        return $this->_set('id', $value);
    }

    public function getTitle()
    {
        return $this->_get('title');
    }

    /**
     * @return Bd_Orm_Main_Thread
     */
    public function setTitle($value)
    {
        return $this->_set('title', $value);
    }

    public function getCreatedAt()
    {
        return $this->_get('created_at');
    }

    /**
     * @return Bd_Orm_Main_Thread
     */
    public function setCreatedAt($value)
    {
        return $this->_set('created_at', $value);
    }


}

