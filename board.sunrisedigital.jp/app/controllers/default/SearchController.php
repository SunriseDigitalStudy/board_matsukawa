<?php

class SearchController extends Sdx_Controller_Action_Http {

    public function listAction() {

        /*
         * genreリスト表示
         */
        $t_genre = Bd_Orm_Main_Genre::createTable();

        //Selectの取得
        $select = $t_genre->getSelect();
        $select->order('sequence DESC');

        //SQLを発行
        $genre_list = $t_genre->fetchAll($select);

        $this->view->assign('genre_list', $genre_list);

        /*
         * tagリスト表示
         */
        $t_tag = Bd_Orm_Main_Tag::createTable();
        $select = $t_tag->getSelect();
        $select->order('name DESC');
        $tag_list = $t_tag->fetchAll($select);
        $this->view->assign('tag_list', $tag_list);
        
        //パラメータを取得
        $genre_id = $this->_getParam('genre_id') ;
        $tag_ids = $this->_getParam('tag_ids');
                
        //リダイレクト後のチェックボックスに、入力した検索条件の値を反映させるために
        //テンプレートにアサイン
        $this->view->assign('genre_id', $genre_id);
        $this->view->assign('tag_ids', $tag_ids);
 
        
        /*-------------------------------------------------------------------------------
         *  複合条件検索のSQL文
            SELECT *
            FROM thread
            INNER JOIN thread_tag ON thread.id = thread_tag.thread_id
            INNER JOIN tag ON thread_tag.tag_id = tag.id
            WHERE
            thread_tag.tag_id IN (11, 10)
            AND
            genre_id = 4
            GROUP BY
            thread.id HAVING COUNT(tag_id) = 2; //2はチェックボックスのチェックされた数
         * ------------------------------------------------------------------------------
         */
        
            /*
             * 複合条件検索のSQLをORMで作成
             */    

            //並び順用サブクエリの作成
            //SELECT thread_id, Max(updated_at) AS updated  FROM entry GROUP BY thread_id           
            $t_entry = Bd_Orm_Main_Entry::createTable();
            $select_en = $t_entry->getSelect();
            $select_en->resetColumns()
                    ->columns('thread_id')
                    ->columns('Max(updated_at) AS updated')
                    ->group('thread_id');
            

            //メインクエリ作成
            $t_thread = Bd_Orm_Main_Thread::createTable();
            
            //タグ条件絞り込み検索のためのテーブル生成＆join
            if($tag_ids){
                $t_thread_tag = Bd_Orm_Main_ThreadTag::createTable();
                $t_thread->addJoinLeft($t_thread_tag);
            } 
            
            //全件検索
            $select_th = $t_thread->getSelectWithJoin();
            $sub_Query = $select_th->expr('(' . $select_en->assemble() . ')');
            $select_th
                    ->joinLeft(array('max_updated' => $sub_Query), 'thread.id = max_updated.thread_id')
                    ->order('(CASE WHEN updated is null THEN 1 ELSE 2 END), updated DESC');           
            //タグ条件で絞込み
            if($tag_ids){
                $select_th
                        ->add('thread_tag.tag_id', $tag_ids)
                        ->group('thread.id')
                        ->having('COUNT(tag_id) =' . count($tag_ids));
            }
            //ジャンル条件で絞込み
            if($genre_id){
                $select_th
                        ->add('genre_id', $genre_id)
                        ->group('thread.id');
            }
                       
             //sql発行
            $thread_list = $t_thread->fetchAll($select_th);
            //テンプレにアサイン
            $this->view->assign('thread_list', $thread_list);            

    }

}
