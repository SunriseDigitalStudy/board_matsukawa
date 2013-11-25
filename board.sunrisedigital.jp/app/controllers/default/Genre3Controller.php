<?php

class Genre3Controller extends Sdx_Controller_Action_Http {

    public function indexAction() {
        $this->_disableViewRenderer();
    }

    //リスト表示
    public function listAction() {

        $t_genre = Bd_Orm_Main_Genre::createTable();

        //Selectの取得
        $select = $t_genre->getSelect();
        $select->order('sequence DESC');

        //SQLを発行
        $list = $t_genre->fetchAll($select);

        $this->view->assign('genre_list', $list);
    }

}
