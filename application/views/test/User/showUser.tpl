{if mb_strlen($Message) > 0}
<p>{$Message}</p>
{else}
<ul>
    <li>ログインID(メールアドレス)：{$UserInfo.Mail}</li>
    <li>パスワード：＜未公開＞</li>
    <li>ニックネーム：{$UserInfo.Nickname}</li>
</ul>
{/if}
<hr />
<a href="../top">戻る</a>
