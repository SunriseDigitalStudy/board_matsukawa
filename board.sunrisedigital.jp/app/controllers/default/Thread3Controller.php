<?php

class Thread3Controller extends Sdx_Controller_Action_Http {

    //各ジャンルのスレッド一覧表示
    public function listAction() {
     
        /*
         * 最新の投稿があった順スレッドタイトルを並べて表示 step4
         */
        
        //スレッドのテーブルクラス取得
        $t_thread = Bd_Orm_Main_Thread::createTable();
        //セレクトクラス取得
        $select_th = $t_thread->getSelect();
        
        //エントリーのテーブルクラスを取得
        $t_entry = Bd_Orm_Main_Entry::createTable();
        $select_en = $t_entry->getSelect();
        
        //サブクエリ作成
        //SELECT thread_id, Max(updated_at) AS updated  FROM entry GROUP BY thread_id
        $select_en->resetColumns()
                  ->columns('thread_id')
                  ->columns('Max(updated_at) AS updated')
                  ->group('thread_id');
                      
        //組み立てたSQL文を文字列として取り出し、$select_thに組み込む。        
        //Zend_Db_Exprを使ってサブクエリのクォートを無効化
        $sub_Query = $select_th->expr('('.$select_en->assemble().')');       
      
        //joinInner 第一引数・結合するテーブル名　第二引数・結合のための条件文       
        $select_th->joinLeft(array('max_updated'=>$sub_Query),'thread.id = max_updated.thread_id')
                  ->add('genre_id' , $this->_getParam('genre_id'))
                  ->order('updated DESC');   
        
        //SQLを発行
        $threadt_list = $t_thread->fetchAll($select_th);
        
        $this->view->assign('threadt_list', $threadt_list);
        
    }

}
