ゲームプレイヤー詳細
<hr />
{if !$GamePlayer}
{$Message}
{else}
ゲーム名：{$GamePlayer.GameName}<br />
グループ名：{$GamePlayer.GroupName}<br />
ゲーム側ユーザID：{$GamePlayer.PlayerId}<br />
ゲーム側ニックネーム：{$GamePlayer.GameNickname}<br />
認証コード：{$GamePlayer.AuthCode}<br />
{/if}
<hr />
<a href="../top">戻る</a>
