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
          <h3><span class="label label-default">単語検索</span></h3>
          {$form.word1->render() nofilter}<br/>
          <button type="submit" class="btn btn-success"><b>検索</b><i class="glyphicon glyphicon-hand-left"></i></button>
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
              <th>コメント数</th>
            </tr>
            </thead>
            <tbody id="content">
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
  <script>
    $(function() {

      //前の5件を表示するボタンは、1ページ目では使う必要がないので、はじめに非表示にする。
      $('#back').hide();

      var firstPage = 1; //表示するページのナンバー
      ajax(firstPage);

      /*
       *検索ボタンクリックアクション
       */
      $('#form1').submit(function(event) {
        //submitイベントを無効化
        event.preventDefault();
        $("#headline").hide();
        //変数firstPageはレキシカル変数
        ajax(firstPage);
      });


      function ajax(page) {
        //送る値ををクエリ文字列に変換
        var $form = $("#form1");
        var form_val = $form.serialize();
        form_val += '&pid=' + page;

        $.ajax({
          type: "GET",
          url: "/json/search",
          data: form_val,
          dataType: "json"

        }).done(function(json) {

          //ページングデータをdata Attributes(独自データ属性)に格納する
          var pager = json['page'];

          if (pager['next_page']) {
            $('#next').show();
            $("#pager").data("next-page", pager['next_page']);
          } else {
            $('#next').hide();
          }

          if (pager['prev_page']) {
            $('#back').show();
            $("#pager").data("prev-page", pager['prev_page']);
          } else {
            $('#back').hide();
          }

          //取得したjsonデータをHTMLにレンダリングして出力
          if (json['thread_list'].length >= 1) {
            var tpl_html = $("#search_criteria_ture").text();
            var html = "";
            $.each(json['thread_list'], function() {
              var tpl_html_copy = tpl_html;  //tpl_html_copyを毎回初期化。tpl_htmlの値はいじりたくない
              $.each(this, function(key, value) {
                if (!value) {
                  value = '投稿がありません';
                }
                tpl_html_copy = tpl_html_copy.split("%" + key + "%").join(value);
              });
              html += tpl_html_copy; //まとめて出力するために、テンプレートを連結。
            });
            $("#content").html(html);
          } else {
            var tpl_html = $("#search_criteria_false").text();
            $("#content").html(tpl_html);
          }

        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
          alert('Error : ' + errorThrown);
        });

      }

      //次の件数を表示
      $('#next').click(function() {
        var nextPage = Number($("#pager").data("next-page"));
        ajax(nextPage);
      });

      //前の件数を表示
      $('#back').click(function() {
        var prevPage = Number($("#pager").data("prev-page"));
        ajax(prevPage);
      });


      /*
       * 選択されたラジオボタン、チェックボックスのチェックをリセットする処理
       */
      $(".clearForm").bind("click", function() {
        $(this.form).find(":checked").prop("checked", false);
      });
      

    });

  </script>


  {*--------------------HTMLテンプレート----------------------*}
  
  {*スレッド一覧を表示するテンプレート*}
  <script type="text/html" id="search_criteria_ture">
    <tr>
      <td><a href="/entry3/%id%/list">%title%</a></td>
      <td>%updated%</td>
      <td>%count%</td>
    </tr>
  </script>

  <script type="text/html" id="search_criteria_false">
    <p style="font-size:200%">検索条件に一致するスレッドはありません</p><br/>
    <p><img src="/img/20081221231807.jpg" alt="やる夫3"></p>
  </script>


{/block}
