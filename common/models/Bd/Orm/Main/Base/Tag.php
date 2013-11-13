<?php

require_once 'Bd/Db/Record.php';
require_once 'Bd/Orm/Main/Table/Tag.php';
require_once 'Bd/Orm/Main/Const/Tag.php';

abstract class Bd_Orm_Main_Base_Tag extends Bd_Db_Record
{

    private static $_table_class = null;

    /**
     * @return Bd_Orm_Main_Table_Tag
     */
    public static function getTable()
    {
        if(!self::$_table_class)
        {
            self::$_table_class = new Bd_Orm_Main_Table_Tag();
            self::$_table_class->lock();
        }
        
        
        return self::$_table_class;
    }

    /**
     * @return Bd_Orm_Main_Table_Tag
     */
    protected function _getTable()
    {
        return self::getTable();
    }

    /**
     * @return Bd_Orm_Main_Table_Tag
     */
    public static function createTable()
    {
        return new Bd_Orm_Main_Table_Tag();
    }

    /**
     * @return Bd_Orm_Main_Form_Tag
     */
    public static function createForm(array $except = array(), Sdx_Db_Record $record = null)
    {
        return new Bd_Orm_Main_Form_Tag('', array(), $except, $record);
    }

    public function getId()
    {
        return $this->_get('id');
    }

    /**
     * @return Bd_Orm_Main_Tag
     */
    public function setId($value)
    {
        return $this->_set('id', $value);
    }

    public function getName()
    {
        return $this->_get('name');
    }

    /**
     * @return Bd_Orm_Main_Tag
     */
    public function setName($value)
    {
        return $this->_set('name', $value);
    }


}

