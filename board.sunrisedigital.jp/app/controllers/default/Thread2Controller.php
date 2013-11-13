<?php

class Thread2Controller extends Sdx_Controller_Action_Http {

    
    //入力フォーム
    public function insertAction() {

        $form = new Sdx_Form();
        $form
                ->setActionCurrentPage() //アクション先を現在のURLに設定
                ->setMethodToPost();     //メソッドをポストに変更
        //genre_id
        $elem = new Sdx_Form_Element_Text();
        $elem->setName('genre_id');
        $form->setElement($elem);

        //title
        $elem = new Sdx_Form_Element_Text();
        $elem->setName('title');
        $form->setElement($elem);

        $this->view->assign('form', $form);


        if ($this->_getParam('submit')) {
            //Validateを実行するためにformに値をセット
            //エラーが有った時各エレメントに値を戻す処理も兼ねてます
//            $form->bind($this->_getAllParams());

            $treadt = new Bd_Orm_Main_Thread();
            $db = $treadt->updateConnection();

            $db->beginTransaction();
            try {

                $treadt
                        ->setGenreId($this->_getParam('genre_id'))
                        ->setTitle($this->_getParam('title'));

                $treadt->save();

                $db->commit();

                $this->redirectAfterSave('/threadt/insert');
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }
    }

    //リスト表示
    public function listAction() {

        $t_Threadt = Bd_Orm_Main_Thread::createTable();

        //Selectの取得
        $select = $t_Threadt->getSelect();
        //selectにWHERE句を追加　※idの値は適宜書き換えて下さい
//        $select->add('id', array(4,5));
        //SQLを発行
        $list = $t_Threadt->fetchAll($select);
        Sdx_Debug::dump($list, 'method');

        $this->view->assign('threadt_list', $list);
    }

}
