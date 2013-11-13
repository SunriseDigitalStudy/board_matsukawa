<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FooController
 *
 * @author Matsukawa
 */
class FooController extends Sdx_Controller_Action_Http {

    public function indexAction() {
        $this->_disableViewRenderer();
    }

    public function barAction() {
        $this->view->assign('date', date('Y-m-d H:i:s'));
    }
    
    /*
     * ORMを使わないDB接続
     * accountテーブルにデータ追加
     */
    public function dbAction() {
        $this->_disableViewRenderer();

        //接続を確認
        $db = Bd_Db::getConnection('board_master');
//        Sdx_Debug::dump($db, hensuu);

        //トランザクション開始
        $db->beginTransaction();

        //テーブル名を指定してＩＮＳＥＲＴ文を生成・実行
        $db->insert('account', array(
            'login_id' => 'board-admin',//admin2
            'password' => 'somepassword',//some_password2
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ));

        //コミット
        $db->commit();

        //取得して確認
        Sdx_Debug::dump($db->query("SELECT * FROM account")->fetchAll(), 'title');
    }
    
    /*
     * entryにデータ追加
     */
//    public function entryAction() {
//        $this->_disableViewRenderer();
//
//        //接続を確認
//        $db = Bd_Db::getConnection('board_master');
////        Sdx_Debug::dump($db, hensuu);
//
//        //トランザクション開始
//        $db->beginTransaction();
//
//        //テーブル名を指定してＩＮＳＥＲＴ文を生成・実行
//        $db->insert('entry', array(
//            'thread_id' => 1,//admin2
//            'account_id' => 1,//some_password2
//            'body' => 'こんにちは',
//            'created_at' => date('Y-m-d H:i:s'),
//            'updated_at' => date('Y-m-d H:i:s'),
//        ));
//
//        //コミット
//        $db->commit();
//
//        //取得して確認
//        Sdx_Debug::dump($db->query("SELECT * FROM account")->fetchAll(), 'title');
//    }
    
    
    /*
     * 新規レコード作成
     */
    public function ormNewAction() {
        $this->_disableViewRenderer();

        //レコードクラスの生成
        $account = new Bd_Orm_Main_Account();
       
        

        $account
                ->setLoginId('test')
                ->setPassword('flkdjf0');

        //このレコードがしようする接続を取得
        $db = $account->updateConnection();

        $db->beginTransaction();
        $account->save();
        $db->commit();

        //取得して確認
        Sdx_Debug::dump($db->query("SELECT * FROM account")->fetchAll(), 'title');
    }
    
    /*
     * レコードの取得
     */
    public function ormSelectAction() {
        
        $this->_disableViewRenderer();

        //テーブルクラスの取得
        $t_account = Bd_Orm_Main_Account::createTable();
//         Sdx_Debug::dump($t_account, record);
        //主キー1のレコードを取得
        $account = $t_account->findByPkey(1);

        //toArray()はレコードの配列表現を取得するメソッドです。
        Sdx_Debug::dump($account->toArray(), 'title');

        //Selectの取得
        $select = $t_account->getSelect();
//        Sdx_Debug::dump($select, select);
        //selectにWHERE句を追加　※idの値は適宜書き換えて下さい
        $select->add('id', array(1, 3));
        //SQLを発行
        $list = $t_account->fetchAll($select);

        //fetchAllの返り値は配列ではなくBd_Db_Record_Listのインスタンスです
        Sdx_Debug::dump($list, 'title');

        //Reocrd_Listの配列表現をdump
        Sdx_Debug::dump($list->toArray(), 'title');
        
        /*
         * 簡単なJOIN
         */
        
        //JOIN対象のテーブルを全て生成
        $t_account = Bd_Orm_Main_Account::createTable();
        $t_entry = Bd_Orm_Main_Entry::createTable();

        //INNER JOIN
        $t_account->addJoinInner($t_entry);

        //selectを取得するメソッドがgetSelectWithJoinなので注意
        $select = $t_account->getSelectWithJoin();

        //この結果はまだentryにレコードがないのでSQLだけ確認して下さい。
        $list = $t_account->fetchAll($select);
        Sdx_Debug::dump($list, 'sql');
    }
    
    /*
     * レコードの更新
     */
    public function ormUpdateAction(){
        $this->_disableViewRenderer();
        
        
        //テーブルクラスの取得
        $t_account = Bd_Orm_Main_Account::createTable();
        //主キー１のレコードを取得
        $account = $t_account->findByPkey(1);
        
        $account->setPassword('update_password_'.date('Y-m-d H:i:s'));
        
        $db = $account->updateConnection();
        
        $db->beginTransaction();
        $account->save();
        $db->commit();
        
        //取得して確認
        Sdx_Debug::dump($db->query("SELECT * FROM account")->fetchAll(),'title');
                
    }
    
    /*
     * レコードの削除
     */
    public function ormDeleteAction() {
        $this->_disableViewRenderer();
        
        //テーブルクラスの取得
        $t_account = Bd_Orm_Main_Account::createTable();
        //主キー１のレコードを取得
        $account = $t_account->findByPkey(1);
        
        $db = $account->updateConnection();
        
        $db->beginTransaction();
        $account->delete();
        $db->commit();
        
        //取得して確認
        Sdx_Debug::dump($db->query("SELECT * FROM account")->fetchAll(), 'title');
        
    }
    

}
