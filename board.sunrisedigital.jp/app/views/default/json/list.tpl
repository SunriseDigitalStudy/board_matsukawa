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

    <div id="content"></div>



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
        var formVal = $form.serialize();
        formVal += '&pid=' + page;

        $.ajax({
          type: "GET",
          url: "/json/search",
          data: formVal,
          dataType: "json",
          success: function(json)
          {
            for (var i = 0; i < json.length; i++) {
              $("#content").append(json[i].title + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + ((json[i].updated) ? json[i].updated : 'コメントはありません') + '<br>');
            }
            //出力したHTMLにクリックイベントを実装
            initPagingEvent();
          },
          error: function(XMLHttpRequest, textStatus, errorThrown)
          {
            alert('Error : ' + errorThrown);
          }
        });

      }

      function initPagingEvent() {

        var nextPage = Number($("#page").data('next-page'));
        var prevPage = Number($("#page").data('prev-page'));

        //次の件数を表示
        if (nextPage) {
          $('#next').click(function() {
            ajax(nextPage);
          });
        } else {
          $('#next').hide();
        }

        //前の件数を表示
        if (prevPage) {
          $('#back').click(function() {
            ajax(prevPage);
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
{/block}
