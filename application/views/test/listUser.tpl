ユーザ一覧
<hr />
{if mb_strlen($Message > 0) }
{$Message}<br />
{/if}
<table>
    <tr>
        <th>UserId</th>
        <th>LoginId</th>
        <th>Password</th>
        <th>Nickname</th>
        <th>Mail</th>
        <th>LastLogin</th>
        <th>MailAuthed</th>
        <th>LoginExclude</th>
        <th>CreateDate</th>
        <th>ゲームプレイヤー登録</th>
    <tr>
{foreach from=$Users item=User}
    <tr>
        <td><a href="showUser?UserId={$User.UserId}">{$User.UserId}</a></td>
        <td>{$User.LoginId}</td>
        <td>{$User.Password}</td>
        <td>{$User.Nickname}</td>
        <td>{$User.Mail}</td>
        <td>{$User.LastLogin}</td>
        <td>{$User.MailAuthed}</td>
        <td>{$User.LoginExclude}</td>
        <td>{$User.CreateDate}</td>
        <td><a href="formAttachGame?UID={$User.UserId}">登録</a></td>
    </tr>
{/foreach}
</table>
<a href="./">戻る</a>
