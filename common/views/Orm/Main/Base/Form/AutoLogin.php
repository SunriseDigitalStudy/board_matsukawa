<?php

abstract class Bd_Orm_Main_Base_Form_AutoLogin extends Sdx_Form
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
    public static function createHashElement(Sdx_Db_Record $record = null)
    {
        return new Sdx_Form_Element_Hidden(array('name'=>'hash'));
    }

    public static function createHashValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
        $element->addValidator(new Sdx_Validate_StringLength(array('max'=>190)));
    }

    /**
     * @return Sdx_Form_Element
     */
    public static function createAccountIdElement(Sdx_Db_Record $record = null)
    {
        return new Sdx_Form_Element_Text(array('name'=>'account_id'));
    }

    public static function createAccountIdValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
        $element->addValidator(new Sdx_Validate_NotEmpty());
    }

    /**
     * @return Sdx_Form_Element
     */
    public static function createExpireDateElement(Sdx_Db_Record $record = null)
    {
        return new Sdx_Form_Element_Text(array('name'=>'expire_date'));
    }

    public static function createExpireDateValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
        $element->addValidator(new Sdx_Validate_NotEmpty());
    }

    protected function _init()
    {
        $this->setName('auto_login');
        $this->setAttribute('method', 'POST');
        if(!in_array('hash', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_AutoLogin', 'createHashElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_AutoLogin', 'createHashValidator'), $element, $this->_record);
        }
        
        if(!in_array('account_id', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_AutoLogin', 'createAccountIdElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_AutoLogin', 'createAccountIdValidator'), $element, $this->_record);
        }
        
        if(!in_array('expire_date', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_AutoLogin', 'createExpireDateElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_AutoLogin', 'createExpireDateValidator'), $element, $this->_record);
        }
    }

    /**
     * @return Bd_Orm_Main_Table_AutoLogin
     */
    public function getTable()
    {
        return call_user_func(array('Bd_Orm_Main_AutoLogin', 'getTable'));
    }

    /**
     * @return Bd_Orm_Main_Table_AutoLogin
     */
    public function createTable()
    {
        return call_user_func(array('Bd_Orm_Main_AutoLogin', 'createTable'));
    }

    /**
     * @return Bd_Orm_Main_AutoLogin
     */
    public function createRecord()
    {
        return new Bd_Orm_Main_AutoLogin();
    }


}

