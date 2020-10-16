<!doctype html>
<html>
<head>
    <title>ゲームページ</title>
{include file="../includes/head.tpl"}
{include file="../js/mypage.js"}
    <script>{literal}
    function extention(){}
    {/literal}</script>
</head>

<body>
    <!-- 表示 -->
    <div class="BaseContainer">
        <!-- Header -->
{include file="../includes/menu.tpl"}
        <!-- Left Sidemenu -->
        <div class="sidemenu">
{include file="../includes/GameList.tpl" GameInfos=$GameInfos}
            <br />
{include file="../includes/GroupList.tpl" GroupInfos=$GroupInfos}
        </div>
        <div class="WorkContainer">
            <!-- Right CM Area -->
            <div class="cmarea">
                cm
            </div>
            <!-- Right Main -->
            <div class="mainwork">
                <!-- メッセージ表示 -->
                <h2>グループリスト</h2>
                <hr />
{foreach from=$GroupList item=group}
{$group->GroupName}&nbsp;[{$group->LeaderName}]<br />
{/foreach}
            </div>
            <div class="funcarea">
                <form action="groupSearch" method="get">
                    グループ名検索：<input type="text" name="tgn"/>
                    <input type="hidden" name="gpid" value="{$GameId}" />
                    <input type="hidden" name="pg" value="" />
                    <input type="submit" value="検索" />
                </form>
            </div>
        </div>
        <!-- Footer -->
        <div class="footer">
            footer
        </div>
    </div>
    <!-- 隠しウィンドウ(ダイアログ代替) -->
{include file="../includes/DialogAddGame.tpl" GameGenre=$GameGenre GameList=$GameList}
{include file="../includes/DialogAddGroup.tpl" GameGenre=$GameGenre GroupGame=$GroupGame}
{include file="../includes/dialogWarning.tpl"}
</body>
</html>
