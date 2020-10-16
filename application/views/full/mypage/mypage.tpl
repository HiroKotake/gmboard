<!doctype html>
<html>
<head>
    <title>マイページ</title>
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
                <h2>メッセージボード</h2>
{foreach from=$Message item=msg name=MessageList}
    {if $smarty.foreach.MessageList.first}
                <hr />
    {/if}
                <div>
                    [{$msg->FromUserName}]{$msg->Message}<br />
                </div>
{/foreach}
            </div>
            <div class="funcarea">
                {$MsgTotal}
                <button class="btnBlue_32x180">メッセージ作成</button>
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
