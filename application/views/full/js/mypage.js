    <script>
{literal}
        $(function(){
            // localStorage
            var gamesListVar = localStorage.getItem("GamesListVer");
            var addedGames = {/literal}{count($GameInfos)}{literal};
            var addedGroups = {/literal}{count($GroupInfos)}{literal};
            if (gamesListVar == null | gamesListVar != {/literal}{$GamesListVer}{literal}) {
                var jsonText = $.ajax({
                    url: "getGames",
                    async: false
                }).responseText;
                localStorage.setItem("GamesList", jsonText);
                localStorage.setItem("GamesListVer", {/literal}{$GamesListVer}{literal});
            }
            var gameListByGenre = JSON.parse(localStorage.getItem("GamesList"));
            // ゲームの追加
            $("#MyPageDialogAddGame").dialog({
                autoOpen: false,
                width: 500,
                modal: true,
                buttons: [
                    {
                        text: '追加',
                        click: function(){
                            alert('check'); // sample
                        }
                    }
                ]
            });
            $("#MyPageBtnAddGame").click(function(){
                $("#MyPageDialogAddGame").dialog("open");
            });
            // ゲームカテゴリの変更
            $("#GameGenre").change(function(){
                var gameList = $("select[name=Target]");
                var selected = $("select[name=Genre]").val();
                // optionの削除
                while (0 < gameList.children('option').length) {
                   gameList.children('option:first-child').remove();
                }
                // optionの追加
                for (var game in gameListByGenre[selected]) {
                    gameList.append($("<option>").val(gameListByGenre[selected][game]["GameId"]).text(gameListByGenre[selected][game]["Name"]));
                }
            });
            // ゲームを選択 (いらないがチェック用として配置。いずれ消すこと！)
            $("#TargetGame").change(function(){
                alert("check"); // sample
            });
            // グループの追加
            $("#MyPageDialogAddGroup").dialog({autoOpen:false});
            $("#MyPageBtnAddGroup").click(function(){
                $("#MyPageDialogAddGroup").dialog("open");
            });
        })
{/literal}
    </script>
