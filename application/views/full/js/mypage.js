    <script>
{literal}
        var gameListByGenre;

        function changeGameGenre() {
            var gameList = $("select[name=Target]");
            var selected = $("select[name=Genre]").val();
            // optionの削除
            while (0 < gameList.children('option').length) {
               gameList.children('option:first-child').remove();
            }
            // optionの追加
            for (var game in gameListByGenre[selected]) {
                if (gameListByGenre[selected][game]["Joined"] == 0) {
                    gameList.append($("<option>").val(gameListByGenre[selected][game]["Ub"]).text(gameListByGenre[selected][game]["Name"]));
                }
            }
            // clear textbox.
            $("#TargePID").val("");
            $("#TargetNickname").val("");
        }

        function changeGroupDropDown(data) {
            var genreList = $("#G_GameGenre");
            var gameList = $("#G_TargetGame");
            // optionの削除
            while (0 < genreList.children('option').length) {
                genreList.children('option:first-child').remove();
            }
            while (0 < gameList.children('option').length) {
                gameList.children('option:first-child').remove();
            }
            // optionの追加
            for (let genre in data["Genre"]) {
                genreList.append($("<option>").val(genre).text(data["Genre"][genre]));
            }
            for (let idx in data["GameInfos"]) {
                for (let games in data["GameInfos"][idx]) {
                    gameList.append($("<option>").val(data["GameInfos"][idx][games]["Ub"]).text(data["GameInfos"][idx][games]["Name"]));
                }
                // 多重配列の最初のループを回して終了
                break;
            }
        }

        function changeGroupGameGenre() {
            var gameList = $("select[name=GTarget]");
            var selected = $("select[name=GGenre]").val();
            // optionの削除
            while (0 < gameList.children('option').length) {
               gameList.children('option:first-child').remove();
            }
            // optionの追加
            for (var game in gameListByGenre[selected]) {
                if (gameListByGenre[selected][game]["Joined"] == 1) {
                    gameList.append($("<option>").val(gameListByGenre[selected][game]["Ub"]).text(gameListByGenre[selected][game]["Name"]));
                }
            }
            // clear textbox.
            $("#TargePID").val("");
            $("#TargetNickname").val("");
        }

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
                                gameListByGenre[genre][target]["Joined"] = 1;
                                localStorage.setItem("GamesList", JSON.stringify(gameListByGenre));
                                // update Area GameList
                                var ulGameList = $("#ulGameList");
                                if (joinedGames == 0) {
                                    ulGameList.children().remove();
                                }
                                ulGameList.append($("<li>" + gameListByGenre[genre][target]["Name"] + "</li>"));
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
                            window.location.href = "./Group/search?gpid=" + game + "&tgn=" + gameGroup + "&pg=1";
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
        })
{/literal}
    </script>
