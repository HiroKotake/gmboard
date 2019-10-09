登録予約対象ゲーム一覧（サンプル）
<hr />
<table>
    <tr>
        <th>ゲームID</th>
        <th>ゲーム名</th>
    </tr>
    {foreach from=$GameInfos item=Info}
    <tr>
        <td><a href="formGamePlayer?GID={$Info.GameId}&GPID=1"</a>{$Info.GameId}</td>
        <td>{$Info.Name}</td>
    </tr>
    {/foreach}
</table>
<hr />
<a href="../top">戻る</a>
