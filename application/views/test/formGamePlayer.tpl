<form action="addGamePlayer" method="post">
    <select name="GID">
    {foreach from=$GameInfos item=game}
        <option value="{$game.GameId}">{$game.Name}</option>
    {/foreach}
    </select>
    <br />
    ゲーム側ユーザID：<input type="text" name="GPID" /><br />
    ゲーム側ニックネーム：<input type="text" name="NNAME" /><br />
    <input type="submit" value="登録" />
</form>
