ユーザ一覧
<hr />
{if mb_strlen($Message > 0) }
{$Message}<br />
{/if}
<table>
    <tr>
        <th>UserId</th>
        <th>AliasId</th>
        <th>Mail(LoginId)</th>
        <th>Password</th>
        <th>Nickname</th>
        <th>LastLogin</th>
        <th>MailAuthed</th>
        <th>LoginExclude</th>
        <th>ゲームプレイヤー登録</th>
    <tr>
{foreach from=$Users item=User}
    <tr>
        <td><a href="showUser?UserId={$User->UserId}">{$User->UserId}</a></td>
        <td>{$User->AliasId}</td>
        <td>{$User->Mail}</td>
        <td>{$User->Password}</td>
        <td>{$User->Nickname}</td>
        <td>{$User->LastLogin}</td>
        <td>{$User->MailAuthed}</td>
        <td>{$User->LoginExclude}</td>
        <td><a href="formAttachGame?UID={$User->UserId}">登録</a></td>
    </tr>
{/foreach}
</table>
<hr />
<a href="../top">戻る</a>
