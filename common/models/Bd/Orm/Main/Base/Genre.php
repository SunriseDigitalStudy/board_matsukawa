<?php

require_once 'Bd/Db/Record.php';
require_once 'Bd/Orm/Main/Table/Genre.php';
require_once 'Bd/Orm/Main/Const/Genre.php';

abstract class Bd_Orm_Main_Base_Genre extends Bd_Db_Record
{

    private static $_table_class = null;

    /**
     * @return Bd_Orm_Main_Table_Genre
     */
    public static function getTable()
    {
        if(!self::$_table_class)
        {
            self::$_table_class = new Bd_Orm_Main_Table_Genre();
            self::$_table_class->lock();
        }
        
        
        return self::$_table_class;
    }

    /**
     * @return Bd_Orm_Main_Table_Genre
     */
    protected function _getTable()
    {
        return self::getTable();
    }

    /**
     * @return Bd_Orm_Main_Table_Genre
     */
    public static function createTable()
    {
        return new Bd_Orm_Main_Table_Genre();
    }

    /**
     * @return Bd_Orm_Main_Form_Genre
     */
    public static function createForm(array $except = array(), Sdx_Db_Record $record = null)
    {
        return new Bd_Orm_Main_Form_Genre('', array(), $except, $record);
    }

    public function getId()
    {
        return $this->_get('id');
    }

    /**
     * @return Bd_Orm_Main_Genre
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
     * @return Bd_Orm_Main_Genre
     */
    public function setName($value)
    {
        return $this->_set('name', $value);
    }

    public function getSequence()
    {
        return $this->_get('sequence');
    }

    /**
     * @return Bd_Orm_Main_Genre
     */
    public function setSequence($value)
    {
        return $this->_set('sequence', $value);
    }


}

