<?php

require_once 'Bd/Db/Record.php';
require_once 'Bd/Orm/Main/Table/ThreadTag.php';
require_once 'Bd/Orm/Main/Const/ThreadTag.php';

abstract class Bd_Orm_Main_Base_ThreadTag extends Bd_Db_Record
{

    private static $_table_class = null;

    /**
     * @return Bd_Orm_Main_Table_ThreadTag
     */
    public static function getTable()
    {
        if(!self::$_table_class)
        {
            self::$_table_class = new Bd_Orm_Main_Table_ThreadTag();
            self::$_table_class->lock();
        }
        
        
        return self::$_table_class;
    }

    /**
     * @return Bd_Orm_Main_Table_ThreadTag
     */
    protected function _getTable()
    {
        return self::getTable();
    }

    /**
     * @return Bd_Orm_Main_Table_ThreadTag
     */
    public static function createTable()
    {
        return new Bd_Orm_Main_Table_ThreadTag();
    }

    /**
     * @return Bd_Orm_Main_Form_ThreadTag
     */
    public static function createForm(array $except = array(), Sdx_Db_Record $record = null)
    {
        return new Bd_Orm_Main_Form_ThreadTag('', array(), $except, $record);
    }

    public function getThreadId()
    {
        return $this->_get('thread_id');
    }

    /**
     * @return Bd_Orm_Main_ThreadTag
     */
    public function setThreadId($value)
    {
        return $this->_set('thread_id', $value);
    }

    public function getTagId()
    {
        return $this->_get('tag_id');
    }

    /**
     * @return Bd_Orm_Main_ThreadTag
     */
    public function setTagId($value)
    {
        return $this->_set('tag_id', $value);
    }


}

