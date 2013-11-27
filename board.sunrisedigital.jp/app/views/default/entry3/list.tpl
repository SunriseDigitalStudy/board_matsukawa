{extends file='default/base.tpl'}
{block title append} 書き込みリスト{/block}
{block main_contents}
    <h1>{$thread->getTitle()}</h1>
    </br>
    <ol>
        {foreach $entry_list as $entry}
            <li>
                {$account = $entry->getAccount()}       
                    名前：{$account->getName()} &nbsp;
                    日時：{$entry->getZendDate('created_at')->get('yyyy年MM月dd日(E) HH時mm分ss秒')}</br>
                    {$entry->getBody()}
            </li>
        {/foreach}
    </ol>     
   

    {if $sdx_context->getUser()->hasId() == null}
        <font color="red"><b>ログインをしないとコメントはできません</b></font>
        {$form->renderStartTag() nofilter}  
        {$form.body->setLabel('コメント')->renderLabel() nofilter}
        {$form.body->render([class=>"form-control", placeholder=>"ゆっくりしていってね∩( ´∀｀)∩ヽ(〃´∀｀〃)ﾉ", disabled=>"true"]) nofilter}       
        <input type="submit" name="submit" value="送信" disabled="true" class="btn btn-success">
        </form>
    {else} 
        {$form->renderStartTag() nofilter}  
        {$form.body->setLabel('コメント')->renderLabel() nofilter}
        {$form.body->render([class=>"form-control", placeholder=>"ゆっくりしていってね∩( ´∀｀)∩ヽ(〃´∀｀〃)ﾉ"]) nofilter}
        {$form.body->renderError() nofilter}
        <input type="submit" name="submit" value="送信" class="btn btn-success">
        </form>
    {/if}
{/block}
