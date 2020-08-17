<!doctype html>
<html>
<head>
    <title>グループページ</title>
{include file="./includes/head.tpl"}
{include file="./js/mypage.js"}
    <script>{literal}
        function jmpGroupFunc(game, group, func) {
            window.location.href = "../Group/" + func + "?gmid=" + game + "&grid=" + group;
        }{/literal}
    </script>
</head>

<body>
    <!-- 隠しウィンドウ(ダイアログ代替) -->
{include file="./includes/DialogAddGame.tpl" GameGenre=$GameGenre GameList=$GameList}
{include file="./includes/DialogAddGroup.tpl" GameGenre=$GameGenre GroupGame=$GroupGame}
{include file="./includes/dialogWarning.tpl"}
    <div class="BaseContainer">
        <!-- Header -->
        <!-- div class="header" -->
{include file="./includes/menu.tpl"}
        <!-- Left Sidemenu -->
        <div class="sidemenu">
{include file="./includes/GameList.tpl" GameInfos=$GameInfos}
            <br />
{include file="./includes/GroupList.tpl" GroupInfos=$GroupInfos}
            <br />
            <fieldset class="smGrouping">
                <legend>グループメニュー</legend>
                <ul>
                    <li><button class="perple_40x280" onclick='jmpGroupFunc("{$GameId}","{$GroupId}", "memberList")'>メンバーリスト</button></li>
                    <li><button class="perple_40x280" onclick='jmpGroupFunc("{$GameId}","{$GroupId}", "requestList")'>申請者リスト</button></li>
                    <li><button class="perple_40x280" onclick='jmpGroupFunc("{$GameId}","{$GroupId}", "inviteList")'>招待者登録</button></li>
                </ul>
            </fieldset>
        </div>
        <div class="WorkContainer">
            <!-- Right CM Area -->
            <div class="cmarea">
                cm
            </div>
            <!-- Right Main -->
            <div class="mainwork">
{if $PageId == $smarty.const.PAGE_ID_GROUP_MAIN}
                <!-- メッセージ表示 -->
                <h2>メッセージボード</h2>
    {foreach from=$Message item=msg name=MessageList}
        {if $smarty.foreach.MessageList.first}
                <hr />
        {/if}
                <div>
                    [{$msg->GameNickname}]{$msg->Message}<br />
                </div>
    {/foreach}
{elseif $PageId == $smarty.const.PAGE_ID_GROUP_MEMBER_LIST}
                <!-- メンバーリスト -->
                <h2>メンバーリスト</h2>
    {foreach from=$MemberList item=member name=Members}
        {if $smarty.foreach.Members.first}
                <hr />
        {/if}
                <div>
                    {$member->GameNickname}
                </div>
    {/foreach}
{elseif $PageId == $smarty.const.PAGE_ID_GROUP_REQEST_LIST}
                <!-- 申請者リスト -->
                <h2>申請者リスト</h2>
{elseif $PageId == $smarty.const.PAGE_ID_GROUP_INVITATION}
                <!-- 招待者 -->
                <h2>招待者</h2>
{elseif $PageId == $smarty.const.PAGE_ID_GROUP_EXTENTION}
                <!-- 拡張機能 -->
{/if}

            </div>
            <div class="funcarea">
                {$MsgTotal}
{if $PageId == $smarty.const.PAGE_ID_GROUP_MAIN}
                <button class="btnBlue_32x180">メッセージ作成</button>
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
