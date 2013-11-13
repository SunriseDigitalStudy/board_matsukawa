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
        <div>{$entry->getUpdatedAt()}</div>
        <div>{$entry->getThread()->getId()}</div>//threadテーブルのIDは二通りの方法で取得できる。
        <div>{$entry->getThreadId()}</div>
{*        <div>{$entry->getAccountId}</div>*}
      </li>
      {/foreach}
    </ul>
  </li>
  {/foreach}
</ul>  
{/block}