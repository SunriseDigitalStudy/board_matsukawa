<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AjaxController
 *
 * @author Matsukawa
 */
class AjaxController extends Sdx_Controller_Action_Http{
  
  public function indexAction() {
    $this->_disableViewRenderer();
  }

 public function listAction() {


    /*
     * Sdx_Formで入力フォームを作成
     */

    $form = new Sdx_Form();
    $form
            ->setAction("javascript:void(0);") //Action先を現在のURLに指定
            ->setMethodToPost()  //メソッドをPOSTに変更
            ->setId('form1');
            
    //各フォームにエレメントをセット
    //ジャンル選択ラジオボタン
    $elems = new Sdx_Form_Element_Group_Radio();
    $genre_list = Bd_Orm_Main_Genre::createTable()
            ->getSelect()
            ->setColumns(array('id', 'name'))
            ->fetchPairs();
    $elems->setName('genre_id')->addChildren($genre_list);
    $form->setElement($elems);

    //タグ選択チェックボックス
    $elems = new Sdx_Form_Element_Group_Checkbox();
    $tag_list = Bd_Orm_Main_Tag::createTable()
            ->getselect()
            ->setColumns(array('id', 'name'))
            ->fetchPairs();
    $elems->setName('tag_ids')->addChildren($tag_list);
    $form->setElement($elems);
    
    
    /*
     * 選択された値(チェック)が他ページから遷移してきた時に反映されるようにする処理
     * search/listから飛んで来た場合とentry3/listから飛んで来た場合で条件分岐
     */
    $session = new Zend_Session_Namespace('SearchController_listAction');

    if ($this->_getParam('submit')) {
      $session->params = $this->_getAllParams();
    } else if ($session->params) {
      foreach ($session->params as $key => $value) {
        $this->_setParam($key, $value);
      }
    }

    $form->bind($this->_getAllParams());

    $this->view->assign('form', $form);



    /* -------------------------------------------------------------------------------
     *  複合条件検索のSQL文
      SELECT *
      FROM thread
      INNER JOIN thread_tag ON thread.id = thread_tag.thread_id
      INNER JOIN tag ON thread_tag.tag_id = tag.id
      WHERE
      thread_tag.tag_id IN (11, 10)
      AND
      genre_id = 4
      GROUP BY
      thread.id HAVING COUNT(tag_id) = 2; //2はチェックボックスのチェックされた数
     * ------------------------------------------------------------------------------
     */

    /*
     * 複合条件検索のSQLをORMで作成
     */

    //絞り込み条件の値(パラメータ)を取得
    $genre_id = $this->_getParam('genre_id');
    $tag_ids = $this->_getParam('tag_ids');

    //並び順用サブクエリの作成
    //SELECT thread_id, Max(updated_at) AS updated  FROM entry GROUP BY thread_id
    $t_entry = Bd_Orm_Main_Entry::createTable();
    $select_en = $t_entry->getSelect();
    $select_en->resetColumns()
            ->columns('thread_id')
            ->columns('Max(updated_at) AS updated')
            ->group('thread_id');


    //メインクエリ作成
    $t_thread = Bd_Orm_Main_Thread::createTable();

    //タグ条件絞り込み検索のためのテーブル生成＆join
    if ($tag_ids) {
      $t_thread_tag = Bd_Orm_Main_ThreadTag::createTable();
      $t_thread->addJoinLeft($t_thread_tag);
    }

    //全件検索
    $select_th = $t_thread->getSelectWithJoin();
    $sub_Query = $select_th->expr('(' . $select_en->assemble() . ')');
    $select_th
            ->joinLeft(array('max_updated' => $sub_Query), 'thread.id = max_updated.thread_id')
            ->order('(CASE WHEN updated is null THEN 1 ELSE 2 END), updated DESC');
    //タグ条件で絞込み
    if ($tag_ids) {
      $select_th
              ->add('thread_tag.tag_id', $tag_ids)
              ->group('thread.id')
              ->having('COUNT(tag_id) =' . count($tag_ids));
    }
    //ジャンル条件で絞込み
    if ($genre_id) {
      $select_th
              ->add('genre_id', $genre_id);
    }

    //sql発行
    $thread_list = $t_thread->fetchAll($select_th);
    //テンプレにアサイン
    $this->view->assign('thread_list', $thread_list);
    
  }
  
  public function searchAction() {


    /*
     * Sdx_Formで入力フォームを作成
     */

    $form = new Sdx_Form();
    $form
            ->setAction("javascript:void(0);") //Action先を現在のURLに指定
            ->setMethodToPost()  //メソッドをPOSTに変更
            ->setId('form1');
            
    //各フォームにエレメントをセット
    //ジャンル選択ラジオボタン
    $elems = new Sdx_Form_Element_Group_Radio();
    $genre_list = Bd_Orm_Main_Genre::createTable()
            ->getSelect()
            ->setColumns(array('id', 'name'))
            ->fetchPairs();
    $elems->setName('genre_id')->addChildren($genre_list);
    $form->setElement($elems);

    //タグ選択チェックボックス
    $elems = new Sdx_Form_Element_Group_Checkbox();
    $tag_list = Bd_Orm_Main_Tag::createTable()
            ->getselect()
            ->setColumns(array('id', 'name'))
            ->fetchPairs();
    $elems->setName('tag_ids')->addChildren($tag_list);
    $form->setElement($elems);
    
    
    /*
     * 選択された値(チェック)が他ページから遷移してきた時に反映されるようにする処理
     * search/listから飛んで来た場合とentry3/listから飛んで来た場合で条件分岐
     */
    $session = new Zend_Session_Namespace('SearchController_listAction');

    if ($this->_getParam('submit')) {
      $session->params = $this->_getAllParams();
    } else if ($session->params) {
      foreach ($session->params as $key => $value) {
        $this->_setParam($key, $value);
      }
    }

    $form->bind($this->_getAllParams());

    $this->view->assign('form', $form);



    /* -------------------------------------------------------------------------------
     *  複合条件検索のSQL文
      SELECT *
      FROM thread
      INNER JOIN thread_tag ON thread.id = thread_tag.thread_id
      INNER JOIN tag ON thread_tag.tag_id = tag.id
      WHERE
      thread_tag.tag_id IN (11, 10)
      AND
      genre_id = 4
      GROUP BY
      thread.id HAVING COUNT(tag_id) = 2; //2はチェックボックスのチェックされた数
     * ------------------------------------------------------------------------------
     */

    /*
     * 複合条件検索のSQLをORMで作成
     */

    //絞り込み条件の値(パラメータ)を取得
    $genre_id = $this->_getParam('genre_id');
    $tag_ids = $this->_getParam('tag_ids');
    $page = $this->_getParam('page');

    //並び順用サブクエリの作成
    //SELECT thread_id, Max(updated_at) AS updated  FROM entry GROUP BY thread_id
    $t_entry = Bd_Orm_Main_Entry::createTable();
    $select_en = $t_entry->getSelect();
    $select_en->resetColumns()
            ->columns('thread_id')
            ->columns('Max(updated_at) AS updated')
            ->group('thread_id');


    //メインクエリ作成
    $t_thread = Bd_Orm_Main_Thread::createTable();

    //タグ条件絞り込み検索のためのテーブル生成＆join
    if ($tag_ids) {
      $t_thread_tag = Bd_Orm_Main_ThreadTag::createTable();
      $t_thread->addJoinLeft($t_thread_tag);
    }

    //全件検索
    $offset = ($page - 1) * 5;  //表示開始ページ
    $select_th = $t_thread->getSelectWithJoin();
    $sub_Query = $select_th->expr('(' . $select_en->assemble() . ')');
    $select_th
            ->joinLeft(array('max_updated' => $sub_Query), 'thread.id = max_updated.thread_id')
            ->order('(CASE WHEN updated is null THEN 1 ELSE 2 END), updated DESC')
            ->limit(5, $offset);
    //タグ条件で絞込み
    if ($tag_ids) {
      $select_th
              ->add('thread_tag.tag_id', $tag_ids)
              ->group('thread.id')
              ->having('COUNT(tag_id) =' . count($tag_ids));
    }
    //ジャンル条件で絞込み
    if ($genre_id) {
      $select_th
              ->add('genre_id', $genre_id);
    }

    //sql発行
    $thread_list = $t_thread->fetchAll($select_th);
    $thread_count = $t_thread->count($select_th); //レコード件数の取得
    //テンプレにアサイン
    $this->view->assign('thread_list', $thread_list);
    //全レコード件数データをHTMLに埋め込み、ajax通信ではHTMLから全レコード件数データを取得
    $this->view->assign('thread_count', $thread_count);
  }
      
}