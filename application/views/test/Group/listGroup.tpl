{if mb_strlen($Message) > 0}
{$Message}<br />
{else}
<table>
    <tr>
        <th>GroupId</th>
        <th>GameName</th>
        <th>GroupName</th>
        <th>LeaderId</th>
        <th>Description</th>
        <th>Member List</th>
        <th>Add Member</th>
        <th>Del Member</th>
    </tr>
    {foreach from=$GroupList item=group}
    <tr>
        <td><a href="showGroup?GID={$GameId}&GPID={$group->GroupId}">{$group->GroupId}</a></td>
        <td>{foreach from=$Games item=game}{if $GameId == $game->GameId}{$game->Name}{/if}{/foreach}</td>
        <td>{$group->GroupName}</td>
        <td>{$group->Leader}</td>
        <td>{$group->Description}</td>
        <td><a href="../TestGroupMember/listGroupMember?GMID={$GameId}&GID={$group->GroupId}">メンバー一覧</a></td>
        <td><a href="../TestGamePlayer/formGamePlayer?GID={$GameId}&GPID={$group->GroupId}">メンバー追加予約</a></td>
    </tr>
    {/foreach}
</table>
{/if}
<hr />
<a href="../top">戻る</a>
