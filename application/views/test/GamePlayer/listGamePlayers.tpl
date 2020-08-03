<h2>{$GameInfo.Name}ゲームプレヤー一覧</h2>
<hr />
{if count($GamePlayers) > 0}
<table>
    <tr>
        <th>ニックネーム</th>
        <th>プレイヤーID</th>
        <th>認証コード</th>
    </tr>
    {foreach from=$GamePlayers item=Players}
    <tr>
        <td>{$Players->GameNickname}</td>
        <td>{$Players->PlayerId}</td>
        <td>{$Players->AuthCode}</td>
    </tr>
    {/foreach}
</table>
{else}
予約しているメンバーはいません。
{/if}
<hr />
<a href="../top">戻る</a>
