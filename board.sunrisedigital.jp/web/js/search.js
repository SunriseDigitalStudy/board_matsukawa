$(function() {

      //前の5件を表示するボタンは、1ページ目では使う必要がないので、はじめに非表示にする。
      $('#back').hide();
      //検索ボタンを無効化
      $("button#search").prop("disabled",true);

      var firstPage = 1; //表示するページのナンバー
      ajax(firstPage);

      /*
       *検索ボタンクリックアクション
       */
      $('#form1').submit(function(event) {
        //submitイベントを無効化
        event.preventDefault();
        $("button#search").prop("disabled",true);
        //変数firstPageはレキシカル変数
        ajax(firstPage);
      });


      function ajax(page) {
        
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
          if(json['keyword']){
            $("th#comment").html("キーワードを含む<br/>コメントの数");
          }else{
            $("th#comment").html("コメント数");
          }

          //取得したjsonデータをHTMLにレンダリングして出力
          if (json['thread_list'].length >= 1) {
            $("thead").show();  //thを表示
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
            $("tbody").html(html);
          } else {
            var tpl_html = $("#search_criteria_false").text();
            $("tbody").html(tpl_html);
          }
          
          //検索ボタンを有効化
          $("button#search").prop("disabled",false);

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
        var form = $(this.form);
        form.find(":text").val("");
        form.find(":checked").prop("checked", false);
      });
      

    });