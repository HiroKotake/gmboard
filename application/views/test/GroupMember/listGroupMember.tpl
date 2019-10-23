<h1>{$GroupInfo.GroupId}：{$GroupInfo.GroupName}</h1>
<hr />
{if mb_strlen($Message) > 0}
{$Message}<br />
{else}
<h2>メンバーリスト</h2>
{if count($MemberList) > 0}
<table>
    <tr>
        <th>PlayerId</th>
        <th>Nicknamec</th>
    </tr>
    {foreach from=$MemberList item=Member}
    <tr>
        <td>{$Member.PlayerId}</td>
        <td>{$Member.GameNickname}</td>
    </tr>
    {/foreach}
</table>
{else}
メンバーが登録されていません。
{/if}
<hr />
<h2>予約者リスト</h2>
{if count($BookingList) > 0}
<table>
    <tr>
        <th>PlayerId</th>
        <th>Authcode</th>
        <th>GameNickname</th>
    </tr>
    {foreach from=$BookingList item=Booking}
    <tr>
        <td>{$Booking.PlayerId}</td>
        <td>{$Booking.GameNickname}</td>
        <td>{$Booking.AuthCode}</td>
    </tr>
    {/foreach}
</table>
{else}
予約者がいません。
{/if}
{/if}
<hr />
<a href="../top">戻る</a>
