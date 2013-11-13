<?php

abstract class Bd_Orm_Main_Base_Form_Tag extends Sdx_Form
{

    private $_except_list = array();

    private $_record = null;

    public function __construct($name = "", array $attributes = array(), array $except_list = array(), Sdx_Db_Record $record = null)
    {
        $this->_except_list = $except_list;
        $this->_record = $record;
        parent::__construct($name, $attributes);
    }

    /**
     * @return Sdx_Form_Element
     */
    public static function createIdElement(Sdx_Db_Record $record = null)
    {
        return new Sdx_Form_Element_Hidden(array('name'=>'id'));
    }

    public static function createIdValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
        
    }

    /**
     * @return Sdx_Form_Element
     */
    public static function createNameElement(Sdx_Db_Record $record = null)
    {
        return new Sdx_Form_Element_Text(array('name'=>'name'));
    }

    public static function createNameValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
        $element->addValidator(new Sdx_Validate_NotEmpty());$element->addValidator(new Sdx_Validate_StringLength(array('max'=>45)));
    }

    protected function _init()
    {
        $this->setName('tag');
        $this->setAttribute('method', 'POST');
        if(!in_array('id', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_Tag', 'createIdElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_Tag', 'createIdValidator'), $element, $this->_record);
        }
        
        if(!in_array('name', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_Tag', 'createNameElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_Tag', 'createNameValidator'), $element, $this->_record);
        }
    }

    /**
     * @return Bd_Orm_Main_Table_Tag
     */
    public function getTable()
    {
        return call_user_func(array('Bd_Orm_Main_Tag', 'getTable'));
    }

    /**
     * @return Bd_Orm_Main_Table_Tag
     */
    public function createTable()
    {
        return call_user_func(array('Bd_Orm_Main_Tag', 'createTable'));
    }

    /**
     * @return Bd_Orm_Main_Tag
     */
    public function createRecord()
    {
        return new Bd_Orm_Main_Tag();
    }


}

