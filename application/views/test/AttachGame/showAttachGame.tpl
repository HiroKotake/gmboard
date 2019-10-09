{$SubTitle}
<hr />
{if !$GamePlayer}
{$Message}
{else}
ゲーム名：{$GamePlayer.GameName}<br />
ゲーム側ユーザID：{$GamePlayer.PlayerId}<br />
ゲーム側ニックネーム：{$GamePlayer.GameNickname}<br />
{/if}
<hr />
<a href="../top">戻る</a>
