ゲームプレイヤー一覧
<hr />
<table>
    <tr>
        <th>ゲーム名</th>
        <th>ゲーム側ユーザID</th>
        <th>ゲーム側ニックネーム</th>
    </tr>
    {foreach from=$GamePlayers item=Player}
    <tr>
        <td>{foreach from=$GameInfos item=Game}{if $Player.GameId == $Game.GameId}{$Game.Name}{/if}{/foreach}</td>
        <td><a href="showGamePlayer?GPID={$Player.PlayerId}">{$Player.PlayerId}</a></td>
        <td>{$Player.GameNickname}</td>
    </tr>
    {/foreach}
</table>
<hr />
<a href="./">戻る</a>
