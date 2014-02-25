$(function() {

  //前の5件を表示するボタンは、1ページ目では使う必要がないので、はじめに非表示にする。
  $('#back').hide();

  var firstPage = 1; //表示するページのナンバー
  ajax(firstPage);

  /*
   *検索ボタンクリックアクション
   */
  


  function ajax(page) {
    
    //通信中はクリックイベントを無効
    $('#next').off();
    $('#back').off();
    $('#form1').off();

    //thを隠す
    $("thead").hide();
    //ローディング画像表示
    $("tbody").html("<img src='/img/image_607975.gif' style='display:block; margin:auto;'/>");

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

      //あいまい検索してたら、テーブルヘッダの表記を変更
      if (json['keyword']) {
        $("th#comment").html("キーワードを含む<br/>コメントの数");
      } else {
        $("th#comment").html("コメント数");
      }

      //取得したjsonデータをHTMLにレンダリングして出力
      if (json['thread_list'].length >= 1) {
        $("thead").show();  //thを表示
        var tpl_html = $("#search_criteria_true").text();
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
        $("tbody").html(html);
      } else {
        var tpl_html = $("#search_criteria_false").text();
        $("tbody").html(tpl_html);
      }
      
      //前の件数を表示するイベント
      $('#back').on('click', function() {
        var prevPage = Number($("#pager").data("prev-page"));
        ajax(prevPage);
      });

      //次の件数を表示するイベント
      $('#next').on('click', function() {
        var nextPage = Number($("#pager").data("next-page"));
        ajax(nextPage);
      });

      //検索ボタンのクリックイベント
      $('#form1').submit(function(event) {
        //submitイベントを無効化
        event.preventDefault();
        //変数firstPageはレキシカル変数
        ajax(firstPage);
      });

    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
      alert('Error : ' + errorThrown);
    });

  }


  /*
   * 選択されたラジオボタン、チェックボックスのチェックをリセットする処理
   */
  $(".clearForm").bind("click", function() {
    var form = $(this.form);
    form.find(":text").val("");
    form.find(":checked").prop("checked", false);
  });


});