{extends file='default/base.tpl'}
{block title append} 書き込みリスト{/block}
{block main_contents}
<ol>
  {foreach $entry_list as $entry}
  <li>
    <div class="account">{$entry->getBody()}</div>
{*    <ul>
      {foreach $genere->getEntryList() as $entry}
      <li>
        <div>{$genre->getCreatedAt()}</div>
        <div>{$genre->getThread()->getTitle()}</div>
        <div>{$genre->getBody()}</div>
      </li>
      {/foreach}
    </ul>*}
  </li>
  {/foreach}
</ol>  
{/block}