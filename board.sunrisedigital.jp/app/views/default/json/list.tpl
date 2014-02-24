{*default/json/list.tpl*}

{extends file='default/base.tpl'}
{block title append} Jsonリスト{/block}
{block main_contents}
  <div class="row">

    <div class="col-sm-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3>検索フォーム</h3>
        </div>
        <div class="panel-body point-delete">
          {$form->renderStartTag() nofilter}
          <h3><span class="label label-default">ジャンル選択</span></h3>
          {$form.genre_id->setDefaultEmptyChild('何も選択しない')->render() nofilter}
          <h3><span class="label label-default">タグ選択</span></h3>
          {$form.tag_ids->render() nofilter}
          <h3><span class="label label-default">キーワード検索</span></h3>
          {$form.word1->addClass('form-control')->render() nofilter}<br/>
          <p style="color:red">↑キーワードを含むコメントがあるスレッドを検索します</p>
          <br/>
          <button type="submit" class="btn btn-success" id="search"><b>検索</b><i class="glyphicon glyphicon-hand-left"></i></button>
          <input class="btn btn-danger clearForm" type="button" value="リセット">
          </form>
          <br/>
        </div>
      </div>
    </div>

    <div class="col-sm-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3>検索結果(スレッド一覧)</h3>
        </div>
        <div class="panel-body">
          <ul class="pager" id="pager">
            <li class="previous"><a id="back">&larr; 前の5件</a></li>
            <li class="next"><a id="next">次の5件 &rarr;</a></li>
          </ul>
          <table class="table">
            <thead>
            <tr>
              <th>スレッドタイトル</th>
              <th>更新日時</th>
              <th id="comment">コメント数</th>
            </tr>
            </thead>
            <tbody>
              {*ここにajaxでスレッドリストデータを生成*}
            </tbody>
          </table>
        </div>
      </div>
      <p style="font-size:200%"><img src="/img/20110224223407740.png" alt="やる夫2">自由に書き込んだらいいお</p>
    </div>



  </div>
{/block}
{block js}
  <script src="/js/search.js"></script>
  
  {*--------------------HTMLテンプレート----------------------*}
  
  {*スレッド一覧を表示するテンプレート*}
  <script type="text/html" id="search_criteria_ture">
    <tr>
      <td><a href="/entry3/%id%/list">%title%</a></td>
      <td>%updated%</td>
      <td>%comment_count%</td>
    </tr>
  </script>

  <script type="text/html" id="search_criteria_false">
    <p style="font-size:200%">検索条件に一致するスレッドはありません</p><br/>
    <p><img src="/img/20081221231807.jpg" alt="やる夫3"></p>
  </script>

{/block}
