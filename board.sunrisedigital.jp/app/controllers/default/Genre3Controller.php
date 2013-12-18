<?php

class Genre3Controller extends Sdx_Controller_Action_Http {

    public function indexAction() {
        $this->_disableViewRenderer();
    }

    //リスト表示
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
        
        
        
        
    }

}
