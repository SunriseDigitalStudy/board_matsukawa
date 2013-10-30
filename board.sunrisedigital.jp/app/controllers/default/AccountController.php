<?php

/*
 * ＯＲＭとSmarty
 */

class AccountController extends Sdx_Controller_Action_Http {

    public function listAction() {
        $t_account = Bd_Orm_Main_Account::createTable();
        $t_entry = Bd_Orm_Main_Entry::createTable();
        $t_thread = Bd_Orm_Main_Thread::createTable();

        //ＪＯＩＮ
        $t_account->addJoinLeft($t_entry);
        $t_entry->addJoinLeft($t_thread);

        //selectを生成
        $select = $t_account->getSelectWithJoin();
        $select->order('id DESC');

        //$listはSdx_Db_Record_Listのインスタンス
        $list = $t_account->fetchAll($select);

        //テンプレートにレコードリストのままアサイン
        $this->view->assign('account_list', $list);

        $select = $t_account->getSelectWithJoin();
        $select->order('id DESC');

        //$listはSdx_Db_Record_Listのインスタンス
        $list = $t_account->fetchAll($select);

        //テンプレートにレコードリストのままアサイン
        $this->view->assign('account_list', $list);
        
        
    }
    
    public function createAction(){
        $form = new Sdx_Form();
        $form1 = $form
           ->setActionCurrentPage();//アクション先を現在のURLに設定
           Sdx_Debug::dump($form1, 'class');
        $form1->setMethodToPost();//メソッドをポストに変更
        
        //各エレメントをフォームにセット
        //login_id
        $elem = new Sdx_Form_Element_Text();
        $elem->setName('lobin_id');
        $form->setElement($elem);
        
        //password
        $elem = new  Sdx_Form_Element_Password();
        $elem->setName('password');
        $form->setElement($elem);
        
        //name
        $elem = new  Sdx_Form_Element_Text();
        $elem->setName('name');
        $form->setElement($elem);
        
        $this->view->assign('form', $form);
    }

}
