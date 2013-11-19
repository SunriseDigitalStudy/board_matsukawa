<?php

class Thread3Controller extends Sdx_Controller_Action_Http {


    //各ジャンルのスレッド一覧表示
     public function listAction() {
         
        $t_Threadt = Bd_Orm_Main_Thread::createTable();
        
        //Selectの取得        
        $select = $t_Threadt->getSelect();
        //URLのgenre_id部分の変数の値を取得
        $genre_id = $this->_getParam('genre_id');
        //selectにWHERE句を追加　
        $select->add('genre_id', array($genre_id));
        //SQLを発行
        $list = $t_Threadt->fetchAll($select);
         
        $this->view->assign('threadt_list', $list);
    
    }

}
