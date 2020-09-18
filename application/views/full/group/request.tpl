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
        var currentAuth;
        var tuid;
        function jmpGroupFunc(game, group, func)
        {
            window.location.href = "../Group/" + func + "?gmid=" + game + "&grid=" + group;
        }
        function exitGroup(game, group, guid)
        {
            window.location.href = "../Group/exitGroup?gmid=" + game + "&grid=" + group + "&guid=" + guid;
        }
        function changeAuth(tid, cAuth)
        {
            currentAuth = cAuth;
            tuid = tid;
            if (changerAuth != 1){
                $("#catAuth1").attr("disabled","disabled");
            }
            $("#catAuth" + cAuth).attr("checked",true);
            $("#dialogAuthChange").dialog("open");
        }

        function extention()
        {
            $("#dialogAuthChange").dialog({
                autoOpen: false,
                width: 550,
                modal: true,
                title: "グループ権限の変更",
                buttons: [
                    {
                        text: "キャンセル",
                        click: function(){
                            $("#dialogAuthChange").dialog("close");
                        }
                    },
                    {
                        text: "変更",
                        click: function(){
                            var radioVal = $("input[name='auth']:checked").val();
                            if (currentAuth == radioVal) {
                                window.alert("権限に変更がありません。");
                            } else if (changeAuth == {/literal}{$smarty.const.GROUP_AUTHORITY_LEADER}{literal} && radioVal > {/literal}{$smarty.const.GROUP_AUTHORITY_LEADER}{literal}) {
                                var result = window.confirm("リーダーから降りようとしていますが、よろしいですか？");
                                if (result) {
                                    // 変更リクエスト
                                    window.location.href = "./memberAuthChange?gmid=" + gid + "&grid=" + grid + "&tuid=" + tuid + "&nath=" + radioVal;
                                }
                            } else {
                                // 最終的に権限変更リクエストを出す
                                var result = window.confirm("権限を変更しますが、よろしいですか？");
                                if (result) {
                                    // 変更リクエスト
                                    window.location.href = "./memberAuthChange?gmid=" + gid + "&grid=" + grid + "&tuid=" + tuid + "&nath=" + radioVal;
                                }
                            }
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
{include file="../includes/GameList.tpl" GameInfos=$GameInfos}
            <br />
{include file="../includes/GroupList.tpl" GroupInfos=$GroupInfos}
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
                <h2>申請者リスト</h2>
            </div>
            <div class="funcarea">
                {$MsgTotal}
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
