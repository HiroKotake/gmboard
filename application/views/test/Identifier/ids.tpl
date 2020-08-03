ランダム生成されたID:<br />
<ul>
{foreach from=$IDs item=id}
    <li>{$id.Origin}&nbsp;->&nbsp;{$id.Outer}&nbsp;->&nbsp;{$id.Decoded}&nbsp;{$id.Check}</li>
{/foreach}
</ul>
<hr />
<a href="top">戻る</a>
