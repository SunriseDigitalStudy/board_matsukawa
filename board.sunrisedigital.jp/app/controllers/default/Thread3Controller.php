<?php

class Thread3Controller extends Sdx_Controller_Action_Http {

    //各ジャンルのスレッド一覧表示
    public function listAction() {

//        /*
//         * すべてのスレッドタイトル表示
//         */
//        
//        $t_Threadt = Bd_Orm_Main_Thread::createTable();
//
//        //Selectの取得        
//        $select = $t_Threadt->getSelect();
//        //URLのgenre_id部分の変数の値を取得
//        $genre_id = $this->_getParam('genre_id');
//        //selectにWHERE句を追加　
//        $select->add('genre_id', array($genre_id))->order('title ASC');
//        //SQLを発行
//        $list = $t_Threadt->fetchAll($select);
//
//        $this->view->assign('threadt_list', $list);
        
        
//        /*
//         * 最新の投稿があった順スレッドタイトルを並べて表示 step3
//         */
//         
//        //エントリーのテーブルクラス取得
//        $t_thread = Bd_Orm_Main_Thread::createTable();
//        //セレクトクラス取得
//        $select_th = $t_thread->getSelect();
//        //Zend_Db_Exprを使ってサブクエリのクォートを無効化
//        //↓$sub_Query = new Zend_Db_Expr('(SELECT thread_id, Max(updated_at) AS updated  FROM entry GROUP BY thread_id)'); と同じ処理       
//        $sub_Query = $select_th->expr('(SELECT thread_id, Max(updated_at) AS updated  FROM entry GROUP BY thread_id)');
//          
//        //join 第一引数・結合するテーブル名　第二引数・結合のための条件文        
//        $select_th->joinInner($sub_Query,'thread.id = t.thread_id')
//                  ->order('updated DESC');
//        
//        //組み立てたSQL文を確認
//        var_dump($select_th->assemble());
//        
//        //SQLを発行
//        $list_DESC = $t_thread->fetchAll($select_th);
        
        
        
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
        $select_en->resetColumns()->columns('thread_id')->columns('Max(updated_at) AS updated')->group('thread_id');
        
        //作成したsqlを文字列で取得
        $select = $select_en->assemble();
        
        //組み立てたSQL文を文字列として取り出し、$select_thに組み込む。
        $subQuery = '('.$select_en->assemble().')';
        
        //Zend_Db_Exprを使ってサブクエリのクォートを無効化
        $sub_Query = $select_th->expr($subQuery);       
      
        //joinInner 第一引数・結合するテーブル名　第二引数・結合のための条件文       
        $select_th->joinLeft($sub_Query,'thread.id = t.thread_id')
                  ->add(genre_id , $this->_getParam('genre_id'))
                  ->order('updated DESC');   
        
        //SQLを発行
        $threadt_list = $t_thread->fetchAll($select_th);
        
        $this->view->assign('threadt_list', $threadt_list);
        
        
    }

}
