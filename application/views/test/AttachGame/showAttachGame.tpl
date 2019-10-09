{if !$Infos}
{$Message}
{else}
対象
<hr>
ゲーム：{$Infos.GameInfo.Name}<br />
グループ：{$Infos.GroupInfo.GroupName}
<hr />
{$SubTitle}
<table>
    <tr>
        <th>ゲームユーザID</th>
        <th>ゲームニックネーム</th>
    </tr>
    {foreach from=$Infos.GamePlayers item=$Player}
    <tr>
        <td>{$Player.PlayerId}</td>
        <td>{$Player.GameNickname}</td>
    </tr>
    {/foreach}
</table>
{/if}
<hr />
<a href="../top">戻る</a>
