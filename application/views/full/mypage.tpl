<html>
<head>
    <!-- test -->
    <title>ユーザページ</title>
    {include file="./includes/head.tpl"}
    {include file="./js/mypage.js"}
</head>
<body>
    <!-- 隠しウィンドウ(ダイアログ代替) -->
    {include file="./includes/DialogAddGame.tpl" GameGenre=$GameGenre GameList=$GameList}
    {include file="./includes/DialogAddGroup.tpl" GameGenre=$GameGenre GroupGame=$GroupGame}
    <!-- ヘッダー -->
    <div>
        ヘッダー
    </div>
    <!-- メニュー -->
    <div>
        メニュー
    </div>
    <!-- メインエリア -->
    <div>
    <!-- メインエリア（左：ゲーム・グループリスト表示) -->
    {include file="./includes/GameList.tpl" GameInfos=$GameInfos}
    {include file="./includes/GroupList.tpl" GroupInfos=$GroupInfos}
    <!-- メインエリア（右：データ表示） -->
        <div>データ表示
            <!-- メッセージ表示 -->
            <div>
            <h2>メッセージボード</h2>
            {foreach from=$Message item=msg name=MessageList}
                {if $smarty.foreach.MessageList.first}
                <hr />
                {/if}
                <div>
                [{$msg.FromUserName}]{$msg.Message}<br />
                </div>
            {/foreach}
            </div>
            <!-- メッセージ書き込み(For Group Chat) -->
            {if $Mode == "Group"}
            <div>
                （メッセージ書き込み）
            </div>
            {/if}
        </div>
    </div>
    <!-- フッター -->
    <div>
    </div>
</html>
