<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
{include file="./includes/head.tpl"}
{include file="./js/mypage.js"}
</head>

<body>
    <!-- 隠しウィンドウ(ダイアログ代替) -->
{include file="./includes/DialogAddGame.tpl" GameGenre=$GameGenre GameList=$GameList}
{include file="./includes/DialogAddGroup.tpl" GameGenre=$GameGenre GroupGame=$GroupGame}
	<div class="BaseContainer">
		<!-- Header -->
		<div class="header">
            <!-- メニュー -->
{include file="./includes/menu.tpl"}
		</div>
		<!-- Left Sidemenu -->
		<div class="sidemenu">
{include file="./includes/GameList.tpl" GameInfos=$GameInfos}
            <br />
{include file="./includes/GroupList.tpl" GroupInfos=$GroupInfos}
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
                    [{$msg.FromUserName}]{$msg.Message}<br />
                    </div>
                {/foreach}
			</div>
			<div class="funcarea">
            {if $Mode == "Group"}
            {else}
                <button>メッセージ作成</button>
            {/if}
			</div>
		</div>
		<!-- Footer -->
		<div class="footer">
			footer
		</div>
	</div>
</body>
</html>
