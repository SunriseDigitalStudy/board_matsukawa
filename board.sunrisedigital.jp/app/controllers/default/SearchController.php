<?php

class SearchController extends Sdx_Controller_Action_Http {

  public function listAction() {


    /*
     * Sdx_Formで入力フォームを作成
     */

    //genreリストの取得
    $t_genre = Bd_Orm_Main_Genre::createTable();
    $select = $t_genre->getSelect();
    $select->order('sequence DESC');
    $genre_list = $t_genre->fetchAll($select);

    //tagリストの取得
    $t_tag = Bd_Orm_Main_Tag::createTable();
    $select = $t_tag->getSelect();
    $select->order('name DESC');
    $tag_list = $t_tag->fetchAll($select);

    //リストデータをaddChildren()の引数に渡すためにデータを配列に変換
    //キーがValueで値がLabelの連想配列
    //取得したジャンルの全リストデータ($genre_list)を$genre_ids_namesに代入
    $genre_ids_names = array();
    foreach ($genre_list as $genre) {
      $genre_ids_names[$genre->getId()] = $genre->getName();
    }
    //何も選択しない項目のラジオボタンのlabelとvalueを追加
    $genre_ids_names[""] = "何も選択しない";

    //取得したタグの全リストデータ($tag_list)を$tag_ids_namesに代入
    $tag_ids_names = array();
    foreach ($tag_list as $tag) {
      $tag_ids_names[$tag->getId()] = $tag->getName();
    }


    $form = new Sdx_Form();
    $form
            ->setActionCurrentPage() //Action先を現在のURLに指定
            ->setMethodToPost();  //メソッドをPOSTに変更
    //各フォームにエレメントをセット
    //ジャンル選択ラジオボタン
    $elems = new Sdx_Form_Element_Group_Radio();
    $elems->setName('genre_id')->addChildren($genre_ids_names);
    $form->setElement($elems);

    //タグ選択チェックボックス
    $elems = new Sdx_Form_Element_Group_Checkbox();
    $elems->setName('tag_ids[]')->addChildren($tag_ids_names);
    $form->setElement($elems);

    //選択された値(チェック)がsubmit後のページに反映されるようにする処理
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
              ->add('genre_id', $genre_id)
              ->group('thread.id');
    }

    //sql発行
    $thread_list = $t_thread->fetchAll($select_th);
    //テンプレにアサイン
    $this->view->assign('thread_list', $thread_list);
  }

}
