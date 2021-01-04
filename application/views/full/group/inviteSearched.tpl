<!doctype html>
<html>
<head>
    <title>グループページ</title>
{include file="../includes/head.tpl"}
{include file="../js/mypage.js"}
    <script>{literal}
        var gid = "{/literal}{$GameId}{literal}";
        var grid = "{/literal}{$GroupId}{literal}";
        var changerAuth = {/literal}{$Authority}{literal};
        function jmpGroupFunc(game, group, func)
        {
            window.location.href = "../Group/" + func + "?gmid=" + game + "&grid=" + group;
        }
        function openInviteDialog()
        {
            $("#dialogInvitate").dialog("open");
        }

        function extention()
        {
            $("#dialogInvitate").dialog({
                autoOpen: false,
                width: 550,
                modal: true,
                title: "グループ権限の変更",
                buttons: [
                    {
                        text: "キャンセル",
                        click: function(){
                            $("#dialogInvitate").dialog("close");
                        }
                    },
                    {
                        text: "検索",
                        click: function(){
                            var inn = $("#InviteNickName").val();
                            var irid = $("#InviteGamerID").val();
                            window.location.href = "./searchInvideMember?gmid=" + gid + "&grid=" + grid + "&inn=" + inn + "&irid=" + irid;
                        }
                    }
                ]
            });
        }
    {/literal}</script>
</head>

<body>
    <!-- 表示 -->
    <div class="BaseContainer">
        <!-- Header -->
        <!-- div class="header" -->
{include file="../includes/menu.tpl"}
        <!-- Left Sidemenu -->
        <div class="sidemenu">
{include file="../includes/GroupMenu.tpl" GameId=$GameId GroupId=$GroupId}
            <br />
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
                <h2>{$GroupName}&nbsp;&nbsp;招待者リスト</h2>
            </div>
            <div class="funcarea">
                {$MsgTotal}
{if $Authority <= $smarty.const.GROUP_AUTHORITY_SUB_LEADER}
                <button class="btnBlue_32x180" onClick="openInviteDialog()">新規招待者登録</button>
{/if}
            </div>
        </div>
        <!-- Footer -->
        <div class="footer">
            footer
        </div>
    </div>
    <!-- 隠しウィンドウ(ダイアログ代替) -->
    <div id="dialogInvitate">
        <form action="" method="post">
            <!-- ニックネーム -->
            <ul style="list-style-type: none">
            <li>ニックネーム：<input type="text" id="InviteNickName" name="inn"></li>
            <!-- ゲームID -->
            <li>ゲームID：<input type="text"  id="InviteGamerID" name="irid"/></li>
            </ul>
        </form>
    </div>
{include file="../includes/DialogAddGame.tpl" GameGenre=$GameGenre GameList=$GameList}
{include file="../includes/DialogAddGroup.tpl" GameGenre=$GameGenre GroupGame=$GroupGame}
{include file="../includes/dialogWarning.tpl"}
</body>
</html>
