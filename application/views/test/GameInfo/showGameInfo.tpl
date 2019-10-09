{if mb_strlen($Message) > 0}
{$Message}
{else}
<h2>ゲーム情報</h3>
ゲーム名称：{$GameInfo.Name}<br />
説明：{$GameInfo.Description}<br />
登録日：{$GameInfo.CreateDate}<br />
{/if}
<hr />
<a href="../top">戻る</a>
