<h2>{$GameInfo.Name}へ登録予約</h2>
<hr />
<form action="addGamePlayer" method="post">
    予約対象のグループ：<select name="GPID">
    {foreach from=$Groups item=Group}
        <option value="{$Group.GroupId}"{if $Group.GroupId == $GroupId} selected{/if}>{$Group.GroupName}</option>
    {/foreach}
    </select><br />
    ゲーム側ユーザID：<input type="text" name="PID" /><br />
    ゲーム側ニックネーム：<input type="text" name="NNAME" /><br />
    認証コード：<input type="text" name="ACODE" /><br />
    <input type="hidden" name="GID" value="{$GameId}" />
    <input type="submit" value="登録" />
</form>
<hr />
<a href="../top">戻る</a>
