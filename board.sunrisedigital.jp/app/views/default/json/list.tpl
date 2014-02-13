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
          <button type="submit" class="btn btn-success"><b>検索</b><i class="glyphicon glyphicon-hand-left"></i></button>
          <input class="btn btn-danger clearForm" type="button" value="リセット">
          </form>
          <br/>
          <br/>
          <form id="vague">
          <input type="text" class="form-control" id="vague1" name="word1"><br/>
          <label class="radio-inline">
            <input type="radio" name="and_or" value="and" checked> AND
          </label>
          <label class="radio-inline">
            <input type="radio" name="and_or" value="or"> OR
          </label>
          <input type="text" class="form-control" id="vague2" name="word2"><br/>
          <input type="button" class="btn btn-primary search" value="コメント内容検索">
          </form>
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
          <p id="headline"></p>
          <ul id="content">
            {*ajaxでスレッドリストデータを生成*}
          </ul>
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
                  value = 'コメントはありません';
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


      $('.search').on('click', function() {

        $('#next').hide();
        $('#back').hide();

        //入力フォームの値を取得
        var form = $("#vague");
        var query = form.serialize();
        var word1 = $("#vague1").val();
        var word2 = $("#vague2").val();
        
        //headlineに表示する単語(word)
        var word = " ";
        var and_or = $("input:radio[name='and_or']:checked").val();
        if(and_or == 'and'){
          word = word1 + '<font color="#000000">と</font>' + word2;
        }else{
          word = word1 + '<font color="#000000">または</font>' + word2;
        }

        $.ajax({
          type: 'GET',
          url: '/json/wordsearch',
          data: query,

        }).done(function(json) {
          //表題を出力
          $("#headline").show(); //ラジオボタン、チェックボックスでの検索時に非表示にしたものを表示する。
          var headline_tpl = $("#headline_tpl").text().split('keyword').join(word);
          $("#headline").html(headline_tpl);
          //スレッドタイトルとコメントカウント数を出力
          var tpl_html = $("#words").text();
          var html = "";
          $.each(json, function() {
            var tpl_html_copy = tpl_html;  //tpl_html_copyを毎回初期化。tpl_htmlの値はいじりたくない
            $.each(this, function(key, value) {
              tpl_html_copy = tpl_html_copy.split("%" + key + "%").join(value);
            });
            html += tpl_html_copy;
          });
          $("#content").html(html);
        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
          alert('Error : ' + errorThrown);
        });
      });

    });

  </script>


  {*HTMLテンプレート*}
  <script type="text/html" id="search_criteria_ture">
    <li>
      <span style="font-size:130%" class="entry_list"><a href="/entry3/%id%/list">%title%</a></span>
      &nbsp;
      %updated%
    </li>
  </script>

  <script type="text/html" id="search_criteria_false">
    <p style="font-size:200%">検索条件に一致するスレッドはありません</p><br/>
    <p><img src="/img/20081221231807.jpg" alt="やる夫3"></p>
  </script>

  <script type="text/html" id="headline_tpl">
    <p style="font-size:150%"><b>キーワード「<font color="#ff0000">keyword</font>」を含んだコメントのあるスレッド一覧</b></p>
  </script>

  <script type="text/html" id="words">
    <li>
      <span style="font-size:130%" class="entry_list"><a href="/entry3/%id%/list">%title%</a></span>
      &nbsp;
      <span>キーワードが含まれているコメント数-----「%count(entry.body)%」</span>
    </li>
  </script>

{/block}
