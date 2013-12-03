<?php

class Entry3Controller extends Sdx_Controller_Action_Http {

    public function indexAction() {
        $this->_disableViewRenderer();
    }

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
        //selectにWHERE句を追加 //idの昇順で並べる
        $select->add('thread_id', array($thread_id))->order('id ASC');

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
        $this->view->assign('thread', $thread);


        /*
         * 入力フォーム
         */
        $form = new Sdx_Form();
        $form
                ->setActionCurrentPage() //アクション先を現在のURLに設定
                ->setMethodToPost();     //メソッドをポストに変更
        //エレメントをフォームにセット
        $elem = new Sdx_Form_Element_Textarea();
        $elem
                ->setName('body')
                ->addValidator(new Sdx_Validate_NotEmpty)
                ->addValidator(new Sdx_Validate_StringLength(array('max' => 5000)));
        $form->setElement($elem);

        //smartyにアサイン           
        $this->view->assign('form', $form);      
        

        /*
         * submit時のデータ処理 
         */
        if ($this->_getParam('submit')) {
            
            //ログインユーザー以外からのPOSTの場合
            if(Sdx_Context::getInstance()->getUser()->hasId() == null){
                $this->forward404();
            }
            
            //Validateを実行するためにformに値をセット
            //エラーが有った時各エレメントに値を戻す処理も兼ねてます
            $form->bind($this->_getAllParams());

            //Validateを実行
            if ($form->execValidate()) {

                //全てのエラーチェックを通過
                //URLからthread_idを取得
                $thread_id = $this->_getParam('thread_id');

                //Sdx_Contextからログイン時のアカウントIDの取得
                $sdx_context = Sdx_Context::getInstance();
                $signed_account = $sdx_context->getVar('signed_account')->getId();

                $entry = new Bd_Orm_Main_Entry();
                $db = $entry->updateConnection();

                //entryに値をセット
                $entry
                        //スレッドIDをセット
                        ->setThreadId($thread_id)
                        //ログイン時のアカウントIDをセット
                        ->setAccountId($signed_account)
                        //入力フォームの内容をセット
                        ->setBody($this->_getParam('body'));

                $db->beginTransaction();

                try {

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

}
