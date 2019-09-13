<table>
    <tr>
        <th>GameId</th>
        <th>Name</th>
        <th>Description</th>
    </tr>
{foreach from=$list item=gameInfo}
    <tr>
        <td><a href="showGameInfo?GameID={$gameInfo.GameId}">{$gameInfo.GameId}</a></td>
        <td>{$gameInfo.Name}</td>
        <td>{$gameInfo.Description}</td>
    </tr>
{/foreach}
</table>
<a href="./">戻る</a>
