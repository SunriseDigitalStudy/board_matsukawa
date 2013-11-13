<?php

class EntryController extends Sdx_Controller_Action_Http {

    //入力フォーム
    public function insertAction() {

        $form = new Sdx_Form();
        $form
                ->setActionCurrentPage() //アクション先を現在のURLに設定
                ->setMethodToPost();     //メソッドをポストに変更
        //thread_id
        $elem = new Sdx_Form_Element_Text();
        $elem->setName('thread_id');
        $form->setElement($elem);

        //account_id
        $elem = new Sdx_Form_Element_Text();
        $elem->setName('account_id');
        $form->setElement($elem);

        //body
        $elem = new Sdx_Form_Element_Text();
        $elem->setName('body');
        $form->setElement($elem);

        $this->view->assign('form', $form);


        if ($this->_getParam('submit')) {
            //Validateを実行するためにformに値をセット
            //エラーが有った時各エレメントに値を戻す処理も兼ねてます
//            $form->bind($this->_getAllParams());

            $entry = new Bd_Orm_Main_Entry();
            $db = $entry->updateConnection();

            $db->beginTransaction();
            try {

                $entry
                        ->setThreadId($this->_getParam('thread_id'))
                        ->setAccountId($this->_getParam('account_id'))
                        ->setBody($this->_getParam('body'));

                $entry->save();

                $db->commit();

                $this->redirectAfterSave('/entry/insert');
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }
    }

    public function listAction() {


        $t_entry = Bd_Orm_Main_Entry::createTable();

        //Selectの取得
        $select = $t_entry->getSelect();
        //selectにWHERE句を追加　※idの値は適宜書き換えて下さい
//        $select->add('id', array(4,5));
        //SQLを発行
        $list = $t_entry->fetchAll($select);
        Sdx_Debug::dump($list, 'method');

        $this->view->assign('entry_list', $list);
    }

    public function alistAction() {
        
        

        //簡単なJOIN
        //JOIN対象のテーブルを全て生成
        $t_account = Bd_Orm_Main_Account::createTable();
        $t_entry = Bd_Orm_Main_Entry::createTable();

        //INNER JOIN
        $t_entry->addJoinInner($t_account);

        //selectを取得するメソッドがgetSelectWithJoinなので注意
        $select = $t_entry->getSelectWithJoin();

        //この結果はまだentryにレコードがないのでSQLだけ確認して下さい。
        $list = $t_entry->fetchAll($select);
        
        $this->view->assign('entry_list', $list);
    }

}
