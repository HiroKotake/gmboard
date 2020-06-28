<html>
<head>
    <!-- test -->
    <title>ユーザページ</title>
    {include file="./includes/head.tpl"}
    {include file="./js/mypage.js"}
</head>
<body>
    <!-- 隠しウィンドウ(ダイアログ代替) -->
    <div id="MyPageDialogAddGame">
        <form action="AttachGame" method="post">
            <ul style="list-style-type: none">
            <!-- ゲーム追加 -->
            <!-- カテゴリ -->
                <li>
                    <select id="GameGenre" name="Genre">
                    {foreach from=$GameGenre key=GenreId item=Genre}
                        <option value="{$GenreId}">{$Genre}</option>
                    {/foreach}
                </select>
            <!-- ゲーム -->
                    <select id="TargetGame" name="Target">
                    {foreach from=$GameList[1] item=GameInfo}
                        {if $GameInfo.Joined == 0}
                        <option value="{$GameInfo.GameId}">{$GameInfo.Name}</option>
                        {/if}
                    {/foreach}
                    </select>
                </li>
            <!-- ゲーム側のプレイヤーID -->
                <li>ゲーム側のプレイヤーID:<input type="text" id="TargePID" name="gpid" /></li>
            <!-- ゲーム側のニックネーム -->
                <li>ゲーム側のニックネーム:<input type="text" id="TargetNickname" name="gnn" /></li>
            </ul>
        </form>
    </div>
    <div id="MyPageDialogAddGroup">グループの追加
        <form action="AttachGroup" method="post">
            <ul style="list-style-type: none">
            <!-- グループ追加 -->
            <!-- 対象のゲーム -->
            <!-- 名称検索 -->
            </ul>
        </form>
    </div>
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
        <div name="AreaGameList">ゲームリスト表示<br />
            <ul name="GameList" id="ulGameList" style="list-style-type: none">
            {if count($GameInfos) > 0}
            <!-- ゲームリスト -->
                {foreach from=$GameInfos item=Game name=GameInfoList}
                <li>{$Game.Name}</li>
                {/foreach}
            {else}
            <li>登録されているゲームはありません。</li>
            {/if}
            </ul>
            <br /><button id="MyPageBtnAddGame">ゲーム追加</button>
        </div>
        <div name="AreaGroupList">グループリスト表示<br />
            <ul name="GroupList" id="ulGroupList"style="list-style-type: none">
            {if count($GroupInfos) > 0}
            <!-- グループリスト -->
                {foreach from=$GroupInfos item=Group name=GroupList}
                <li>[{$Group.GameName}]&nbsp;{$Group.GroupName}</li>
                {/foreach}
            {else}
            <li>登録されているグループはありません。</li>
            {/if}
            </ul>
            <br /><button id="MyPageBtnAddGroup">グループ追加</button>
        </div>
    <!-- メインエリア（右：データ表示） -->
        <div>データ表示
            <!-- メッセージ表示 -->
            <div>
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
