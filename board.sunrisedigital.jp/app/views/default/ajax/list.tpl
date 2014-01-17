
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

    <div id="content">
      <div class="col-sm-8">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3>検索結果(スレッド一覧)</h3>
          </div>
          <div class="panel-body">
            {if get_class($thread_list->getFirstRecord()) == Sdx_Null }
              <p style="font-size:200%">検索条件に一致するスレッドはありません</p><br/>
              <p><img src="/img/20081221231807.jpg" alt="やる夫3"></p>
              {else}
              <span id="back" class="glyphicon glyphicon-circle-arrow-left sample4"></span><span id="number"></span><span id="next" class="glyphicon glyphicon-circle-arrow-right samlpe4"></span>
              <ul id="aaa">
                {foreach $thread_list as $thread}
                  <li>
                    <span class="entry_list" style="font-size:130%"><a href="/entry3/{$thread->getId()}/list">{$thread->getTitle()}</a></span>
                    &nbsp;
                  {if $thread->get('updated')}{$thread->getZendDate('updated')->get('yyyy年MM月dd日(E) HH時mm分ss秒')}{else}コメントは一件もありません{/if}
                </li>
              {/foreach}
            </ul>
          {/if}
        </div>
      </div>
      <p style="font-size:200%"><img src="/img/20110224223407740.png" alt="やる夫2">自由に書き込んだらいいお</p>
    </div>
  </div>



</div>
{/block}
{block js}
  <script>
    $(function() {

      paging();      

      /*
       * 複数条件検索処理
       */
      $('#form1').submit(function(event) {
        //submitイベントを無効化
        event.preventDefault();
        //送る値ををクエリ文字列に変換
        var $form = $("#form1");
        var formVal = $form.serialize();

        $.ajax({
          type: "GET",
          url: "/ajax/list",
          data: formVal,
          success: function(data)
          {
            //検索結果を出力している箇所のみを読み込んで出力する
            $("#content").html($('#content', data).html());
            //出力したHTMLのリスト部分をページングする
            paging();
          },
          error: function(XMLHttpRequest, textStatus, errorThrown)
          {
            alert('Error : ' + errorThrown);
          }
        });
      });

      //リストをページングするメソッド
      function paging() {
        var number = 5; //ここでは定数的な扱い　IEではconstがサポートされていないのでconstは使わない方向
        var page = 1; //ページ数
        var limit = number; //1ページあたりに表示する件数

        draw();

        //前の件数を表示
        $('#back').click(function() {
          if (page > 1) {
            page--;
            limit -= number;
            draw();
          }
        });

        //次の件数を表示
        $('#next').click(function() {
          if (limit < $('ul#aaa > li').length) {
            page++;
            limit += number;
            draw();
          }
        });

        //リストを表示する処理
        function draw() {
          $('ul#aaa > li').hide();
          $('#number').html(page + 'ページ目').css("font-weight", "bold").css("font-size", "150%");
          //pageの値を変えたくないので、値を一時的にtemporaryに入れる
          var temporary = page; 
          var count = (temporary - 1) * number;
          for (count; count < limit; count++) {
            $('ul#aaa > li').eq(count).show();
          }

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
{/block}
