<?php

abstract class Bd_Orm_Main_Base_Form_Thread extends Sdx_Form
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
    public static function createTitleElement(Sdx_Db_Record $record = null)
    {
        return new Sdx_Form_Element_Text(array('name'=>'title'));
    }

    public static function createTitleValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
        $element->addValidator(new Sdx_Validate_NotEmpty());$element->addValidator(new Sdx_Validate_StringLength(array('max'=>80)));
    }

    /**
     * @return Sdx_Form_Element
     */
    public static function createGenreIdElement(Sdx_Db_Record $record = null)
    {
        $elem = new Sdx_Form_Element_Group_Select(array('name'=>'genre_id'));
        $select = Bd_Orm_Main_Genre::getTable()->getSelect()
        ->resetColumns()
        ->columns(array('id', 'name'))
        ->order('sequence ASC');
        $elem->setChildren($select->fetchPairs());
        $elem->setDefaultEmptyChild('ジャンルを選択して下さい');
        return $elem;
    }

    public static function createGenreIdValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
        $element->addValidator(new Sdx_Validate_NotEmpty());
    }

    /**
     * @return Sdx_Form_Element
     */
    public static function createMMTagIdElement(Sdx_Db_Record $record = null)
    {
        $elem = new Sdx_Form_Element_Group_Checkbox(array('name'=>'Tag__id'));
        $select = Bd_Orm_Main_Tag::getTable()->getSelect()
            ->resetColumns()
            ->columns(array('id', 'name'))
            ->order('id ASC');
        $elem->setChildren($select->fetchPairs());
        return $elem;
    }

    public static function createMMTagIdValidator(Sdx_Form_Element $element, Sdx_Db_Record $record = null)
    {
        
    }

    protected function _init()
    {
        $this->setName('thread');
        $this->setAttribute('method', 'POST');
        if(!in_array('id', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_Thread', 'createIdElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_Thread', 'createIdValidator'), $element, $this->_record);
        }
        
        if(!in_array('title', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_Thread', 'createTitleElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_Thread', 'createTitleValidator'), $element, $this->_record);
        }
        
        
        
        if(!in_array('genre_id', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_Thread', 'createGenreIdElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_Thread', 'createGenreIdValidator'), $element, $this->_record);
        }
        
        if(!in_array('Tag__id', $this->_except_list))
        {
        	$element = call_user_func(array('Bd_Orm_Main_Form_Thread', 'createMMTagIdElement'), $this->_record);
        	$this->setElement($element);
        	call_user_func(array('Bd_Orm_Main_Form_Thread', 'createMMTagIdValidator'), $element, $this->_record);
        }
    }

    /**
     * @return Bd_Orm_Main_Table_Thread
     */
    public function getTable()
    {
        return call_user_func(array('Bd_Orm_Main_Thread', 'getTable'));
    }

    /**
     * @return Bd_Orm_Main_Table_Thread
     */
    public function createTable()
    {
        return call_user_func(array('Bd_Orm_Main_Thread', 'createTable'));
    }

    /**
     * @return Bd_Orm_Main_Thread
     */
    public function createRecord()
    {
        return new Bd_Orm_Main_Thread();
    }


}

