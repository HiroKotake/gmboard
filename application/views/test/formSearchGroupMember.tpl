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
    <h3>メンバー検索</h3>
    <form action="resultSearchGroupMember" method="post">
        ゲーム側ユーザID：<input type="text" name="GPID" /><br />
        <input type="hidden" name="GID" value="{$GroupInfo.GroupId}" />
        <input type="submit" value="検索" />
    </form>
{/if}
<a href="./">戻る</a>
