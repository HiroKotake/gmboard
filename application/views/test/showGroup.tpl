{if mb_strlen($Message) > 0}
{$Message}<br />
{else}
グループ名：{$GroupInfo.GroupName}<br />
ゲーム名：{$GameName}<br />
リーダーID：{$GroupInfo.Leader}<br />
説明：{$GroupInfo.Description}<br />
{/if}
<a href="./">戻る</a>
