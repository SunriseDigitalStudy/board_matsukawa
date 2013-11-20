<?php

class Entry3Controller extends Sdx_Controller_Action_Http {

    //エントリーの一覧表示
    public function listAction() {

        //簡単なJOIN
        //JOIN対象のテーブルを全て生成
        $t_account = Bd_Orm_Main_Account::createTable();
        $t_entry = Bd_Orm_Main_Entry::createTable();

        //INNER JOIN
        $t_entry->addJoinInner($t_account);

        //selectを取得するメソッドがgetSelectWithJoinなので注意
        $select = $t_entry->getSelectWithJoin();
        //URLからthread_idを取得
        $thread_id = $this->_getParam('thread_id');
        //selectにWHERE句を追加
        $select->add('thread_id', array($thread_id));
        //idの昇順で並べる
        $select->order('id ASC');

        $list = $t_entry->fetchAll($select);

        $this->view->assign('entry_list', $list);


        /*
         * Threadテーブルの各IDのレコードを取得
         */
        //テーブルクラスの取得
        $t_thread = Bd_Orm_Main_Thread::createTable();
        //主キーが１のレコードを取得
        $thread = $t_thread->findByPkey($thread_id);

        //テンプレートにアサイン
        $this->view->assign('thread_list', $thread);


        /*
         * 入力フォーム
         */
        $form = new Sdx_Form();
        $form
                ->setActionCurrentPage() //アクション先を現在のURLに設定
                ->setMethodToPost();     //メソッドをポストに変更
        //エレメントをフォームにセット
        $elem = new Sdx_Form_Element_Textarea();
        $elem->setName('entry');
        $form->setElement($elem);

        //smartyにアサイン           
        $this->view->assign('form', $form);

        //アカウント情報を取得。nullの場合はログインしていない。
        $sdx_context = Sdx_Context::getInstance();
        $login_id = $sdx_context->getVar('signed_account');     
        //smartyにアサイン           
        $this->view->assign('login_id', $login_id);
        



        /*
         * submit時のデータ処理 
         */
        if ($this->_getParam('submit')) {
            
            //URLからthread_idを取得
            $thread_id = $this->_getParam('thread_id');
            
            //Sdx_Contextからログイン時のアカウントIDの取得
            $sdx_context = Sdx_Context::getInstance();
            $login_id = $sdx_context->getVar('signed_account')->getId();
            
            $entry = new Bd_Orm_Main_Entry();
            $db = $entry->updateConnection();


            $db->beginTransaction();

            try {

                $entry
                        //スレッドIDをセット
                        ->setThreadId($thread_id)
                        //ログイン時のアカウントIDをセット
                        ->setAccountId($login_id)
                        //入力フォームの内容をセット
                        ->setBody($this->_getParam('entry'));

                $entry->save();

                $db->commit();

                $this->redirectAfterSave('/entry3/' . $thread_id . '/list');
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }
    }

}
