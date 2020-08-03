ゲーム一覧
<hr />
<table>
    <tr>
        <th>ゲーム名</th>
        <th>予約中プレイヤー一覧</th>
    </tr>
    {foreach from=$GameInfos item=Game}
    <tr>
        <td>{$Game->Name}</td>
        <td><a href="listGamePlayers?GID={$Game->GameId}">表示</a></td>
    </tr>
    {/foreach}
</table>
<hr />
<a href="../top">戻る</a>
