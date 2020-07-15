<html>
<head>
    <title>{$Title}</title>
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
        {include file="./includes/menu.tpl"}
    </div>
    <!-- メインエリア -->
    <div>
        <div>
        <!-- メインエリア（左：ゲーム・グループリスト表示) -->
        {include file="./includes/GameList.tpl" GameInfos=$GameInfos}
        {include file="./includes/GroupList.tpl" GroupInfos=$GroupInfos}
            <a href="../MyPage">マイページ</a>
        </div>
        <!-- メインエリア（右：データ表示） -->
        <div>
        {if $Type == GroupSearch}
            <!-- 検索結果表示 -->
            {include file="./individual/groupSearch.tpl" list=$List total=$TotalNumber cpage=$CurrentPage maxLineNumber=$MaxLineNumber totalPage=$TotalPage}
        {else}
        {/if}
        </div>
    </div>
    <!-- フッター -->
    <div>
    </div>
</body>
</html>
