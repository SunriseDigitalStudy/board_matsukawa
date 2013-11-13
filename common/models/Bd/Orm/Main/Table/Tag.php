<?php

require_once 'Bd/Orm/Main/Base/Table/Tag.php';

class Bd_Orm_Main_Table_Tag extends Bd_Orm_Main_Base_Table_Tag
{
    public function fetchAllNewOrdered(Sdx_Db_Select $select = null){
        if($select === null){
            $select = $this->getSelect();
        }
        
        $select->order('id DESC');
        
        return $this->fetchAll($select);
    }

}

