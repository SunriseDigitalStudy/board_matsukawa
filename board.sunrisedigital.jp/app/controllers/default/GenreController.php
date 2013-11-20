<?php

class GenreController extends Sdx_Controller_Action_Http {

    //入力フォーム
    public function insertAction() {

        $form = new Sdx_Form();
        $form
                ->setActionCurrentPage() //アクション先を現在のURLに設定
                ->setMethodToPost();     //メソッドをポストに変更
        //name
        $elem = new Sdx_Form_Element_Text();
        $elem->setName('name ASC');
        $form->setElement($elem);

        //sequence
        $elem = new Sdx_Form_Element_Text();
        $elem->setName('sequence');
        $form->setElement($elem);

        $this->view->assign('form', $form);


        if ($this->_getParam('submit')) {
            //Validateを実行するためにformに値をセット
            //エラーが有った時各エレメントに値を戻す処理も兼ねてます
//            $form->bind($this->_getAllParams());

            $genre = new Bd_Orm_Main_Genre();
            $db = $genre->updateConnection();

            $db->beginTransaction();
            try {

                $genre
                        ->setSequence($this->_getParam('sequence'))
                        ->setName($this->_getParam('name'));

                $genre->save();

                $db->commit();

                $this->redirectAfterSave('/genre/insert');
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }
    }

    //リスト表示
    public function listAction() {

        $t_genre = Bd_Orm_Main_Genre::createTable();

        //Selectの取得
        $select = $t_genre->getSelect();
        //selectにWHERE句を追加　※idの値は適宜書き換えて下さい
//        $select->add('id', array(4,5));
        //SQLを発行
        $list = $t_genre->fetchAll($select);
//         Sdx_Debug::dump($list, 'method');


        $this->view->assign('genre_list', $list);
    }

}
