<html>
<head>
    <title>テスト</title>
    <script type="text/javascript" src="../../js/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="../../js/jquery-ui-1.12.1/jquery-ui.min.css" />
    <script>
        function changeModifyData(pref, flag) {
            $("#" + pref + "GameInfo").prop("checked", flag);
            $("#" + pref + "User").prop("checked", flag);
            $("#" + pref + "PlayerIndex").prop("checked", flag);
            $("#" + pref + "Group").prop("checked", flag);
            $("#" + pref + "GroupMember").prop("checked", flag);
            $("#" + pref + "BookingGroupMember").prop("checked", flag);
        }

        function sumModifyData(pref) {
            var result = 0;
            if ($("#" + pref + "GameInfo").prop("checked"))             result +=      1;
            if ($("#" + pref + "User").prop("checked"))                 result +=     10;
            if ($("#" + pref + "PlayerIndex").prop("checked"))          result +=    100;
            if ($("#" + pref + "Group").prop("checked"))                result +=   1000;
            if ($("#" + pref + "GroupMember").prop("checked"))          result +=  10000;
            if ($("#" + pref + "BookingGroupMember").prop("checked"))   result += 100000;
            return (result + '').padStart(6, '0');  // 数値を文字列化し、左に"0"を６桁になるように追加する
        }

        function showConfirmTable(target) {
            var targetUrl = "./TestData/" + target;
            var ope = target == "buildTables" ? "作成" : "削除";
            $("#showDialog").html("この操作を実行すると元に戻せませんが、<br />" + ope + "を実行しますか？");
            $("#showDialog").dialog({
                width: 350,
                model: true,
                title: "確認",
                buttons: {
                    "OK": function(){
                        $(this).dialog("close");
                        var result = $.ajax({
                            url: targetUrl,
                            type: "GET",
                            async: false
                        }).responseText;
                        var data = JSON.parse(result);
                        alert(data.message);
                    },
                    "CANCEL": function(){
                        $(this).dialog("close");
                    }
                }
            });
        }

        $(function(){
            $("#addAllData").change(function(){
                var fChecked = $("#addAllData").prop("checked");
                if (fChecked) {
                    changeModifyData("add", true);
                } else {
                    changeModifyData("add", false);
                }
            });
            $("#delAllData").change(function(){
                var fChecked = $("#delAllData").prop("checked");
                if (fChecked) {
                    changeModifyData("del", true);
                } else {
                    changeModifyData("del", false);
                }
            });
            $("#btnAddDataExec").click(function(){
                var fAdd = sumModifyData("add");
                if (fAdd > 0) {
                    var result = $.ajax({
                        url: "./TestData/buildData",
                        type: "POST",
                        data: "target=" + fAdd,
                        async: false
                    }).responseText;
                    data = JSON.parse(result);
                    alert("データ追加:" + data["Message"]);
                }
            });
            $("#btnDelDataExec").click(function(){
                var fDel = sumModifyData("del");
                if (fDel > 0) {
                    var result = $.ajax({
                        url: "./TestData/removeData",
                        type: "POST",
                        data: "target=" + fDel,
                        async: false
                    }).responseText;
                    data = JSON.parse(result);
                    alert("データ削除:" + data["Message"]);
                }
            });
            $("#btnBuildTable").click(function(){
                showConfirmTable("buildTables");
            });
            $("#btnDestroyTable").click(function(){
                showConfirmTable("destoryTables");
            });
        });
    </script>
</head>
<body>
    <div id="showDialog"></div>
    <h1>テストページ</h1>
    <hr />
    <h3>テスト用データ</h3>
    <ul style="list-style-type: none">
        <li><button id="btnBuildTable">DBテーブル作成</button></li>
        <li><button id="btnDestroyTable">DBテーブル全削除</button></li>
        <br />
        <li>データ生成
        <form id="addData">
            <ul style="list-style-type: none">
                <li><input type="checkbox" id="addAllData" />&nbsp;全情報登録</li>
                <li><input type="checkbox" id="addGameInfo" />&nbsp;-&nbsp;ゲーム情報登録</li>
                <li><input type="checkbox" id="addUser" />&nbsp;-&nbsp;ユーザ登録</li>
                <li><input type="checkbox" id="addPlayerIndex" />&nbsp;-&nbsp;ユーザのゲーム登録</li>
                <li><input type="checkbox" id="addGroup" />&nbsp;-&nbsp;グループ登録</li>
                <li><input type="checkbox" id="addGroupMember" />&nbsp;-&nbsp;グループメンバー登録</li>
                <li><input type="checkbox" id="addBookingGroupMember" />&nbsp;-&nbsp;グループ予約メンバー登録</li>
            </ul>
        </form>
        </li>
        <li><button id="btnAddDataExec">テストデータ生成実行</button></li>
        <br />
        <li>データ削除
        <form id="delData">
            <ul style="list-style-type: none">
                <li><input type="checkbox" id="delAllData" />&nbsp;全情報削除</li>
                <li><input type="checkbox" id="delGameInfo" />&nbsp;-&nbsp;ゲーム情報削除</li>
                <li><input type="checkbox" id="delUser" />&nbsp;-&nbsp;ユーザ削除</li>
                <li><input type="checkbox" id="delPlayerIndex" />&nbsp;-&nbsp;ユーザのゲーム削除</li>
                <li><input type="checkbox" id="delGroup" />&nbsp;-&nbsp;グループ削除</li>
                <li><input type="checkbox" id="delGroupMember" />&nbsp;-&nbsp;グループメンバー削除</li>
                <li><input type="checkbox" id="delBookingGroupMember" />&nbsp;-&nbsp;グループ予約メンバー削除</li>
            </ul>
        </form>
        </li>
        <li><button id="btnDelDataExec">テストデータ削除実行</button></li>
    </ul>
    <h3>ゲーム関連</h3>
    <ul>
        <li><a href="TestGameInfo/formGameInfo">ゲーム情報追加</a></li>
        <li><a href="TestGameInfo/listGameInfo">ゲーム情報一覧表示</a></li>
        <li><a href="TestGameInfo/showGameInfo?GameID=2">ゲーム情報確認</a></li>
    </ul>
    <h3>ユーザ関連１</h3>
    <ul>
        <li><a href="TestUser/formUser">ユーザ追加</a></li>
        <li><a href="TestUser/listUser">ユーザ一覧表示</a></li>
        <li><a href="TestUser/showUser?UserId=1">ユーザ情報表示</a></li>
        <li><a href="TestUser/checkLogin">ログイン認証・確認</a></li>
    </ul>
    <h3>ユーザ関連２:RegistBooking - グループリーダー用登録方式</h3>
    <ul>
        <li><a href="TestGamePlayer/formGameList">ゲームプレイヤー予約</a></li>
        <li><a href="TestGamePlayer/listGames">ゲーム一覧表示</a></li>
        <li><a href="TestGamePlayer/showGamePlayer?GID=1&RBID=1">ゲームプレイヤー表示</a></li>
    </ul>
    <h3>ユーザ関連３:GamePlayers - ユーザ用登録方式</h3>
    <ul>
        <li><a href="TestAttachGame/formAttachGame?UID=1">ゲームプレイヤー登録</a></li>
        <li><a href="TestAttachGame/showAttachGame?GMID=1&GID=1">ゲームプレイヤー表示</a></li>
    </ul>
    <h3>グループ関連</h3>
    <ul>
        <li><a href="TestGroup/formGroup">グループ追加</a></li>
        <li><a href="TestGroup/listGroup?GID=1">グループ一覧表示</a></li>
        <li><a href="TestGroup/showGroup?GID=1&GPID=1">グループ情報表示</a></li>
        <li><a href="TestGroupMember/formAddGroupMember?GPID=1">グループメンバー追加１</a></li>
        <li><a href="TestGroupMember/formSearchGroupMember?GPID=1">グループメンバー追加２</a></li>
        <li><a href="TestGroupMember/listGroupMember?GMID=1&GID=1">グループメンバー一覧表示</a></li>
        <li><a href="TestGroupMember/delGroupMember?GPID=1">グループメンバー除名</a></li>
    </ul>
    <h3>掲示板関連</h3>
    <ul>
        <li><a href="GroupMessage/formGroupMessage">グループ掲示板へメッセージ追加</a></li>
        <li><a href="GroupMessage/showGroupMessage">グループ掲示板表示</a></li>
        <li><a href="TestUserMessage/formUserMessage">ユーザ掲示板へメッセージ追加</a></li>
        <li><a href="TestUserMessage/showUserMessage">ユーザ掲示板表示</a></li>
    </ul>
</body>
</html>
