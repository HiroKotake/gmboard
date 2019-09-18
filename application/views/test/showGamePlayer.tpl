ゲームプレイヤー詳細
<hr />
{if !$GamePlayer}
{$Message}
{else}
ゲーム名：{$GamePlayer.GameName}<br />
ゲーム側ユーザID：{$GamePlayer.PlayerId}<br />
ゲーム側ニックネーム：{$GamePlayer.GameNickname}<br />
{/if}
<hr />
<a href="./">戻る</a>
