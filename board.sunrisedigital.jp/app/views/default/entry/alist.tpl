{extends file='default/base.tpl'}
{block title append} 書き込みリスト{/block}
{block main_contents}
<ol>
  {foreach $entry_list as $entry}
  <li>
  {$account = $entry->getAccount()}
    <div class="account">        
        名前：{$account->getName()} &nbsp;
        日時：{$entry->getCreatedAt()}</br>
        {$entry->getBody()}
        
    </div>

  </li>
  {/foreach}
</ol>  
{/block}
