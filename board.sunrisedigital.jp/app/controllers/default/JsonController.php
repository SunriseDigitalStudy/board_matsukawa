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
class JsonController extends Sdx_Controller_Action_Http {

  //ページング処理に使う値
  const PER_PAGE = 5; //1ページあたりの表示件数

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
    
    //テキストボックス
    $elem = new Sdx_Form_Element_Text();
    $elem->setName('word1');
    $form->setElement($elem);

    $this->view->assign('form', $form);
  }

  public function searchAction() {

    $this->_disableViewRenderer();

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
    $word1 = $this->_getParam('word1');

    //並び順用サブクエリの作成
    //SELECT thread_id, Max(updated_at) AS updated  FROM entry GROUP BY thread_id
    $t_entry = Bd_Orm_Main_Entry::createTable();
    $select_en = $t_entry->getSelect();
    $select_en->resetColumns()
            ->columns('thread_id')
            ->columns('Max(updated_at) AS updated')
            ->columns('count(entry.body) AS count')
            ->group('thread_id');
    if($word1){
      $word1 = mb_convert_kana($word1,'s');
      $keyword1 = trim($word1);
      $select_en->like('entry.body','%'.$keyword1.'%');
    }


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
    //$word1があった時は、キーワードが存在したコメントのあるスレッドのみを表示したいので、INNER　JOINにする。
    if($word1){
      $select_th->joinInner(array('max_updated' => $sub_Query), 'thread.id = max_updated.thread_id');
    }else{
      $select_th->joinLeft(array('max_updated' => $sub_Query), 'thread.id = max_updated.thread_id');
    }
    $select_th
            ->setColumns(array('thread.id','title','max_updated.updated', 'max_updated.count'))
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

    
    /*
     * ページング処理
     */
    $count = $select_th->countRow(); //総レコード数
    //コンストラクタ内でURLのクエリ文字列から直に、表示するページナンバーを取得している
    $sdx_pager = new Sdx_Pager(self::PER_PAGE, $count); 
    $select_th->limitPager($sdx_pager);

    //sql発行
    $thread_list = $t_thread->fetchAll($select_th);
    
    /*
     * json出力
     */
    $json_data = array(
                  'thread_list' => $thread_list->toArray(), //データを配列に変換
                  'page' => array(  //ページングデータ
                              'next_page' => $sdx_pager->getNextPageId(), 
                              'prev_page' => $sdx_pager->getPrevPageId())
    );
    
    $this->jsonResponse($json_data);
  }
  
  
}
