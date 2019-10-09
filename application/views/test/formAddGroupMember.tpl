{if mb_strlen($Message)}
{$Message}<br />
{else}
    <h3>登録済みメンバー</h3>
    {if count($RegistedMembers) > 0}
    <table>
        <tr>
            <th>GameNickName</th>
            <th>PlayerId</th>
        </tr>
        {foreach from=$RegistedMembers item=RMember}
        <tr>
            <td>{$RMember.GameNickname}</td>
            <td>{$RMember.PlayerId}</td>
        </tr>
        {/foreach}
    </table>
    {else}
    登録済みメンバーがいません。<br />
    {/if}
    <hr />
    <h3>予約済みメンバー</h3>
    {if count($BookingMembers) > 0}
    <table>
        <tr>
            <th>GameNickName</th>
            <th>PlayerId</th>
            <th>AuthCode<th>
        </tr>
        {foreach from=$BookingMembers item=BMember}
        <tr>
            <td>{$BMember.GameNickname}</td>
            <td>{$BMember.PlayerId}</td>
            <td>{$BMember.AuthCode}</td>
        </tr>
        {/foreach}
    </table>
    {else}
    予約済みメンバーがいません。<br />
    {/if}
    <hr />
    <h3>メンバー予約</h3>
    <form action="addGroupMember" method="post">
        ゲーム側ユーザID：<input type="text" name="GPID" /><br />
        認証確認用コード：<input type="text" name="ACD" /><br />
        ゲーム側ニックネーム：<input type="text" name="GNIN" /><br />
        <input type="hidden" name="GID" value="{$GroupId}" />
        <input type="submit" value="登録" />
    </form>
{/if}
<hr />
<a href="../top">戻る</a>
