{$Message}
<form action="addGroup" method="post">
    対象ゲーム：<select name="TRGT">
        {foreach from=$Games item=game}
        <option value="{$game.GameId}">{$game.Name}</option>
        {/foreach}
    </select><br />
    グループ名：<input type="text" name="GNAME" /><br />
    説明：<textarea name="DESCRIP" /></textarea><br />
    <input type="submit" value="登録" />
</form>
<a href="./">戻る</a>
