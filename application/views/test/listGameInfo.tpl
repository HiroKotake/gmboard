{foreach from=$list item=gameInfo}
<h3>{$gameInfo.GameId}:{$gameInfo.Name}</h3>
<p>{$gameInfo.Description}</p>
<a href="showGameInfo?GameID={$gameInfo.GameId}">詳細を見る</a>
{/foreach}
