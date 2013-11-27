<?php

require_once 'Bd/Orm/Main/Base/Table/Genre.php';

class Bd_Orm_Main_Table_Genre extends Bd_Orm_Main_Base_Table_Genre
{
    public function fetchAllNewerOrdered(Sdx_Db_Select $select = null) {
        if ($select === null) {
            $select = $this->getSelect();
        }

        $select->order('id DESC');

        return $this->fetchAll($select);
    }


}

