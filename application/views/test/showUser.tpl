{if mb_strlen($Message) > 0}
<p>{$Message}</p>
{else}
<ul>
    <li>ログインID：{$UserInfo.LoginId}</li>
    <li>パスワード：＜未公開＞</li>
    <li>ニックネーム：{$UserInfo.Nickname}</li>
    <li>メールアドレス：{$UserInfo.Mail}</li>
</ul>
{/if}
<a href="./">戻る</a>
