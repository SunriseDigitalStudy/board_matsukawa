{extends file='default/base.tpl'}
{block title append} ジャンルリスト{/block}
{block main_contents}

    <h3>ジャンル一覧表示</h3> 
    {foreach $genre_list as $genre}
        <a href="/thread3/{$genre->getId()}/list">{$genre->getName()}</a>  <br/>  
    {/foreach}
    <br/> 
    <br/> 
    <br/> 

    <h3>検索フォーム</h3>
    <form method="get" name="form1" id="form1" action="javascript:void(0);"> 
        ジャンルを選択<br/>
        <label>
            <input type="radio" name="radio" value="">何も選択しない
        </label>
        <br/>
        {foreach $genre_list as $genre}
            <label>
                <input type="radio" name="radio" value={$genre->getId()}>
                {$genre->getName()}
            </label>
            <br/>  
        {/foreach}

        <br/>
        タグを選択<br/>
        {foreach $tag_list as $tag}
            <label>
                <input type="checkbox" name="checkbox[]"  value={$tag->getId()}>
                {$tag->getName()}  
            </label>
            <br/>  
        {/foreach}
        <br/> 
        <br/>
{*        <input type="submit" value="検索する">*}
    </form>
    <h3>検索結果</h3>
    <div id="content"></div>


    <script>
        $(document).ready(function()
        {
            $('#form1').click(function() {

                    var $form = $("#form1");
                    var formVal = $form.serialize();

                    $.ajax({
                        type: "GET",
                        url: "/search/list",
                        data: formVal,
                        success: function(data)
                        {
                            $('#content').html(data);
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown)
                        {
                            alert('Error : ' + errorThrown);
                        }
                    });

                });
{*            $('#form1').submit(function() {       

            });*}
        });

    </script>

{/block}