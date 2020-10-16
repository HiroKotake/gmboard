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

        function doWithdraw(tid)
        {
            var result = window.confirm("退会しようとしていますが、よろしいですか？");
            if (result) {
                window.location.href="./withdraw?gmid=" + gid + "&grid=" + grid;
            }
        }

        function doDismiss(tid)
        {
            var result = window.confirm("除名しようとしていますが、よろしいですか？");
            if (result) {
                window.location.href="./dismiss?gmid=" + gid + "&grid=" + grid + "&tuid=" + tid;
            }
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
                <!-- メンバーリスト -->
                <h2>{$GroupName}&nbsp;&nbsp;メンバーリスト</h2>
DEBUG(UserId):{$UserId};
{if !empty($Result)}
{/if}
    {foreach from=$MemberList item=member name=Members}
        {if $smarty.foreach.Members.first}
                <hr />
        {/if}
                <div>
                    {$member->GameNickname}{$member->UserId}{if $UserId == $member->UserId}<button onClick="doWithdraw('{$member->AliasId}')">退会</button>{else}{if $Authority <= 2}<button onClick="doDismiss('{$member->AliasId}')">除名</button>{/if}{/if}{if $Authority <= 2}<button onClick="changeAuth('{$member->AliasId}',{$member->Authority})">権限変更</button>{/if}
                </div>
    {/foreach}
            </div>
            <div class="funcarea">
            </div>
        </div>
        <!-- Footer -->
        <div class="footer">
            {$Result}footer
        </div>
    </div>
    <!-- 隠しウィンドウ(ダイアログ代替) -->
{include file="../includes/DialogAddGame.tpl" GameGenre=$GameGenre GameList=$GameList}
{include file="../includes/DialogAddGroup.tpl" GameGenre=$GameGenre GroupGame=$GroupGame}
{include file="../includes/dialogWarning.tpl"}
    <div id="dialogAuthChange">
        <form action="memberAuthChange" method="post">
            <ul>
                <li><input type="radio" name="auth" id="catAuth1" value="{$smarty.const.GROUP_AUTHORITY_LEADER}">リーダー</li>
                <li><input type="radio" name="auth" id="catAuth2" value="{$smarty.const.GROUP_AUTHORITY_SUB_LEADER}">サブリーダー</li>
                <li><input type="radio" name="auth" id="catAuth3" value="{$smarty.const.GROUP_AUTHORITY_MENBER}">一般メンバー</li>
                <li><input type="radio" name="auth" id="catAuth4" value="{$smarty.const.GROUP_AUTHORITY_OBSERVER}">オブサーバー</li>
                <li><input type="radio" name="auth" id="catAuth5" value="{$smarty.const.GROUP_AUTHORITY_GUEST}">ゲスト</li>
            </ul>
        </form>
    </div>
</body>
</html>
