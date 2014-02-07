{*default/json/list.tpl*}

{extends file='default/base.tpl'}
{block title append} Ajaxリスト{/block}
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
            {*ajaxでページングボタンを生成*}
          </ul>
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
      
      var firstPage = 1; //表示するページのナンバー
      ajax(firstPage);
      
      /*
       *検索ボタンクリックアクション
       */
      $('#form1').submit(function(event) {
        //submitイベントを無効化
        event.preventDefault();
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
        }).done(function(json){
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
                html += tpl_html_copy;
              });
              $("#content").html(html);
            }else{
              var tpl_html = $("#search_criteria_false").text();
              $("#content").html(tpl_html);
            }
            
            //ページングボタンを表示
            var paging = $("#paging_button").text();
            $("#pager").html(paging);
            
            //ページングボタンにクリックイベントを実装
            var page = json['page'];
            initPagingEvent(page);
            
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
          alert('Error : ' + errorThrown);
        });
        
      }
      
      function initPagingEvent(page) {
        
        //次の件数を表示
        if (page['nextPage']) {
          $('#next').click(function() { 
            ajax(page['nextPage']);
          });
        } else {
          $('#next').hide();
        }
        
        //前の件数を表示
        if (page['prevPage']) {
          $('#back').click(function() {
            ajax(page['prevPage']);
          });
        } else {
          $('#back').hide();
        }

      }
      
      /*
       * 選択されたラジオボタン、チェックボックスのチェックをリセットする処理
       */
      $(".clearForm").bind("click", function() {
        $(this.form).find(":checked").prop("checked", false);
      });
      
    });
    
  </script>



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
  
  <script type="text/html" id="paging_button">
    <li class="previous"><a id="back">&larr; 前の5件</a></li>
    <li class="next"><a id="next">次の5件 &rarr;</a></li>
  </script>
  

{/block}
