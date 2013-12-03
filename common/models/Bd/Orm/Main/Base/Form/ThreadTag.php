<?php

abstract class Bd_Orm_Main_Base_Form_ThreadTag extends Sdx_Form
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
    public static function createThreadIdElement(Sdx_Db_Record $record = null)
    {
        return new Sdx_Form_Element_Hidden(array('name'=>'thread_id'));
    }

    public static function createThreadIdValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
        
    }

    /**
     * @return Sdx_Form_Element
     */
    public static function createTagIdElement(Sdx_Db_Record $record = null)
    {
        return new Sdx_Form_Element_Hidden(array('name'=>'tag_id'));
    }

    public static function createTagIdValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
      
    }

    protected function _init()
    {
        $this->setName('thread_tag');
        $this->setAttribute('method', 'POST');
        if(!in_array('thread_id', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_ThreadTag', 'createThreadIdElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_ThreadTag', 'createThreadIdValidator'), $element, $this->_record);
        }
        
        if(!in_array('tag_id', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_ThreadTag', 'createTagIdElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_ThreadTag', 'createTagIdValidator'), $element, $this->_record);
        }
    }

    /**
     * @return Bd_Orm_Main_Table_ThreadTag
     */
    public function getTable()
    {
        return call_user_func(array('Bd_Orm_Main_ThreadTag', 'getTable'));
    }

    /**
     * @return Bd_Orm_Main_Table_ThreadTag
     */
    public function createTable()
    {
        return call_user_func(array('Bd_Orm_Main_ThreadTag', 'createTable'));
    }

    /**
     * @return Bd_Orm_Main_ThreadTag
     */
    public function createRecord()
    {
        return new Bd_Orm_Main_ThreadTag();
    }


}

