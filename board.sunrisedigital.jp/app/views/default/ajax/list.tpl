
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

      var page = 1; //表示するページのナンバー
      ajax(page);

      /*
       *検索ボタンクリックアクション
       */
      $('#form1').submit(function(event) {
        //submitイベントを無効化
        event.preventDefault();
        //送る値ををクエリ文字列に変換
        page = 1;
        ajax(page);
      });


      function ajax(page) {
        //送る値ををクエリ文字列に変換
        var $form = $("#form1");
        var formVal = $form.serialize();
        formVal += '&page=' + page;

        $.ajax({
          type: "GET",
          url: "/ajax/search",
          data: formVal,
          success: function(data)
          {
            $("#content").html(data);
            //出力したHTMLにクリックイベントを実装
            click();
          },
          error: function(XMLHttpRequest, textStatus, errorThrown)
          {
            alert('Error : ' + errorThrown);
          }
        });
      }

      function click() {
        var number = Number($("#offset").text()); //HTML要素から、総データ件数を取得,数字に変換
        var count = Math.ceil(number / 5); //総データ件数から総ページ数を割り出す
        //次の件数を表示
        if (page >= count) {
          $('#next').hide();
        }
        $('#next').click(function() {
          page++
          ajax(page);
        });

        //前の件数を表示
        if (page <= 1) {
          $('#back').hide();
        }
        $('#back').click(function() {
          page--;
          ajax(page);
        });
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
