    <h2>グループ検索結果</h2>
    <ul id="groupSearchResult" style="list-style-type: none">
        {foreach from=$List item=group}
        <li>
            <ul style="list-style-type: none">
                <li>{$group.GroupName}</li><li>[リーダー:{$group.Leader}]</li><li><button>申請</button></ul>
            </ul>
        </li>
        {/foreach}
    </ul>
    {$cpage}:{$total}[MaxLine{$maxLineNumber}]
