    <script>
{literal}
        var gameListByGenre;

        $(function(){
            // localStorage
            var gamesListVar = localStorage.getItem("GamesListVer");
            var currentJoinedGames = localStorage.getItem("JoinedGames");
            var joinedGames = {/literal}{count($GameInfos)}{literal};
            var joinedGroups = {/literal}{count($GroupInfos)}{literal};
            if (gamesListVar == null | gamesListVar != {/literal}{$GamesListVer}{literal} | currentJoinedGames != joinedGames) {
                var jsonText = $.ajax({
                    url: "MyPage/getGames",
                    async: false
                }).responseText;
                localStorage.setItem("GamesList", jsonText);
                localStorage.setItem("GamesListVer", {/literal}{$GamesListVer}{literal});
                localStorage.setItem("JoinedGames", joinedGames);
            }
            gameListByGenre = JSON.parse(localStorage.getItem("GamesList"));
            // ゲームの追加
            $("#DialogAddGame").dialog({
                autoOpen: false,
                width: 550,
                modal: true,
                title: "ゲームの追加",
                buttons: [
                    {
                        text: '追加',
                        click: function(){
                            var genre = $("#GameGenre option:selected").val();
                            var target = $("#TargetGame option:selected").val();
                            var gpid = $("#TargePID").val();
                            var gnn = $("#TargetNickname").val();
                            var newGameInfo = $.ajax({
                                url: "MyPage/attachGame",
                                type: "POST",
                                data: "target=" + target + "&gpid=" + gpid + "&gnn=" + gnn,
                                async: false
                            }).responseText;
                            var result = JSON.parse(newGameInfo);
                            if (result["Status"] == {/literal}{$smarty.const.DB_STATUS_ADDED}{literal}) {
                                // update WebStrage's GameList
                                gameListByGenre = result["GameList"];
                                localStorage.setItem("GamesList", JSON.stringify(result["GameList"]));
                                // update Area GameList
                                var ulGameList = $("#ulGameList");
                                if (joinedGames == 0) {
                                    ulGameList.children().remove();
                                }
                                ulGameList.append($("<li></li>"));
                                ulGameListLast = $("#ulGameList > li:last");
                                var targetUrl = "<button class=\"perple_40x280\" onClick='jmpGame(\"" + result["GameInfo"]["AliasId"] + "\")'>" + result["GameInfo"]["Name"] + "</button>";
                                ulGameListLast.append(targetUrl);
                                joinedGames += 1;
                                localStorage.setItem("JoinedGames", joinedGames);
                                changeGameGenre();
                                changeGroupDropDown(result["GpDrDw"]);
                            } else {
                                $("#DialogAddGame").dialog("close");
                                windows.alert();
                            }
                        }
                    }
                ]
            });
            $("#BtnAddGame").click(function(){
                $("#DialogAddGame").dialog("open");
            });
            // ゲームカテゴリの変更
            $("#GameGenre").change(function(){
               changeGameGenre();
            });
            // ゲームを選択 (いらないがチェック用として配置。いずれ消すこと！)
            $("#TargetGame").change(function(){
                alert("check"); // sample
            });
            // グループの追加
            $("#DialogAddGroup").dialog({
                autoOpen:false,
                width: 550,
                modal: true,
                title: "グループの追加",
                buttons: [
                    {
                        text: '検索',
                        click: function(){
                            var game = $("#G_TargetGame option:selected").val();
                            var gameGroup = $("#TargetGrouName").val();
{/literal}{if isset($obfGroupId)}{literal}
                            window.location.href = "./Group/search?gpid=" + game + "&grid={/literal}{$obfGroupId}{literal}&tgn=" + gameGroup + "&pg=1";
{/literal}{else}{literal}
                            window.location.href = "./{/literal}{$PageName}{literal}/searchGroup?gpid=" + game + "&tgn=" + gameGroup + "&pg=1";
{/literal}{/if}{literal}
                            $("#DialogAddGroup").dialog("close");
                        }
                    }
                ]
            });
            // ゲームカテゴリの変更
            $("#G_GameGenre").change(function(){
               changeGroupGameGenre();
            });
            // グループの検索ダイアログオープン
            $("#BtnAddGroup").click(function(){
                if ($("#G_GameGenre option").length > 0) {
                    $("#DialogAddGroup").dialog("open");
                } else {
                    $("#dialogWarning p").text("グループの追加をする前に、ゲームを追加してください。");
                    $("#dialogWarning").dialog("open");
                }
            });
            // WARNING ダイアログ
            $("#dialogWarning").dialog({
                autoOpen: false,
                width: 500,
                modal: true,
                title: "WARNING",
                buttons: [
                    {
                        text: "閉じる",
                        click: function(){
                            $("#dialogWarning").dialog("close");
                        }
                    }
                ]
            });
            //
            extention();
        })
{/literal}
    </script>
