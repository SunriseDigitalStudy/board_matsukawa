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
        $t_tab = Bd_Orm_Main_Tag::createTable();
        $select = $t_tab->getSelect();
        $select->order('name DESC');
        $tab_list = $t_tab->fetchAll($select);
        $this->view->assign('tab_list', $tab_list);
        
        
        
        
    }

}
