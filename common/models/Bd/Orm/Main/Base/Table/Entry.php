<?php

require_once 'Bd/Db/Table.php';

abstract class Bd_Orm_Main_Base_Table_Entry extends Bd_Db_Table
{

    private static $_columns = array(
        'id',
        'thread_id',
        'account_id',
        'body',
        'updated_at',
        'created_at'
        );

    private static $_dbnames = array(
        'reference' => 'board_slave',
        'update' => 'board_master'
        );

    private static $_reference_connection = null;

    private static $_update_connection = null;

    private static $_name = 'entry';

    private static $_swap_name = null;

    private static $_record_class_name = 'Bd_Orm_Main_Entry';

    protected static $_class_suffix = 'Entry';

    private static $_nullable = array();

    private static $_relations = null;

    protected $_record_list_class = 'Bd_Db_Record_List';

    private static $_pkeys = array('id');

    protected function _initRelations()
    {
        if(is_null(self::$_relations))
        {
        	self::$_relations = array();
        	self::$_relations['Account'] = Sdx_Db_Relation::create(
        		Sdx_Db_Relation::TYPE_MANY_ONE,
        		'Account',
        		'Entry',
        		array('reference'=>'account_id', 'foreign'=>'id'),
        		'Bd_Orm_Main_Account',
        		null
        	);
        }
    }

    public function getPkey()
    {
        return isset(self::$_pkeys[0]) ? self::$_pkeys[0] : null;
    }

    public function getPkeys()
    {
        if(!isset(self::$_pkeys))
        {
        	return array();
        }
        return self::$_pkeys;
    }

    public function getColumns(Array $excepts = array())
    {
        $columns = self::$_columns;
        if($excepts)
        {
        	$columns = array_diff($columns, $excepts);
        }
        
        return $columns;
    }

    public function hasColumn($name)
    {
        return in_array($name, self::$_columns);
    }

    /**
     * @return Sdx_Db_Adapter
     */
    public function referenceConnection()
    {
        if(self::$_reference_connection)
        {
        	return self::$_reference_connection;
        }
        
        return Bd_Db::getConnection(self::$_dbnames['reference']);
    }

    /**
     * @return Bd_Orm_Main_Table_Entry
     */
    public function swapReferenceConnection(Sdx_Db_Adapter $db)
    {
        self::$_reference_connection = $db;
        return $this;
    }

    /**
     * @return Sdx_Db_Adapter
     */
    public function updateConnection()
    {
        if(self::$_update_connection)
        {
        	return self::$_update_connection;
        }
        
        return Bd_Db::getConnection(self::$_dbnames['update']);
    }

    /**
     * @return Bd_Orm_Main_Table_Entry
     */
    public function swapUpdateConnection(Sdx_Db_Adapter $db)
    {
        self::$_update_connection = $db;
        return $this;
    }

    /**
     * @return Bd_Orm_Main_Table_Entry
     */
    public function clearConnections()
    {
        self::$_reference_connection = null;
        self::$_update_connection = null;
        return $this;
    }

    public function getTableName()
    {
        return self::$_swap_name ? self::$_swap_name : self::$_name;
    }

    public static function swapTableName($value)
    {
        self::$_swap_name = $value;
    }

    public static function resetTableName()
    {
        self::$_swap_name = null;
    }

    public function isSwapingTableName()
    {
        return (bool)self::$_swap_name;
    }

    /**
     * @return Bd_Orm_Main_Entry
     */
    public function createRecord()
    {
        Zend_Loader::loadClass(self::$_record_class_name);
        return new self::$_record_class_name;
    }

    public function getRecordClassName()
    {
        return self::$_record_class_name;
    }

    public function getNullableColumns()
    {
        return self::$_nullable;
    }

    /**
     * @return Sdx_Db_Ralation
     */
    public function getRelation($relation_name)
    {
        if(!isset(self::$_relations[$relation_name]))
        {
        	throw new Sdx_Generator_Exception('Not exsit relation setting for '.$relation_name.' in '.get_class($this));
        }
        
        return self::$_relations[$relation_name];
    }

    public function hasRelation($relation_name)
    {
        return isset(self::$_relations[$relation_name]);
    }

    /**
     * @return array
     */
    public function getRelations()
    {
        return self::$_relations;
    }

    public function getClassSuffix()
    {
        return self::$_class_suffix;
    }

    /**
     * @return Bd_Db_Record_List
     */
    public function createRecordList()
    {
        return new Bd_Db_Record_List();
    }


}

