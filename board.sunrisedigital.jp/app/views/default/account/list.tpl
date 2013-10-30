{extends file='default/base.tpl'}
{block title append} アカウントリスト{/block}
{block main_contents}
<ul>
  {foreach $account_list as $account}
  <li>
    <div class="account">{$account->getLoginId()}</div>
    <ul>
      {foreach $account->getEntryList() as $entry}
      <li>
        <div>{$entry->getCreatedAt()}</div>
        <div>{$entry->getThread()->getTitle()}</div>
        <div>{$entry->getBody()}</div>
      </li>
      {/foreach}
    </ul>
  </li>
  {/foreach}
</ul>  
{/block}