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
            gameList.append($("<option>").val(gameListByGenre[selected][game]["Ad"]).text(gameListByGenre[selected][game]["Name"]));
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
            gameList.append($("<option>").val(data["GameInfos"][idx][games]["Ad"]).text(data["GameInfos"][idx][games]["Name"]));
        }
        // 多重配列の最初のループを回して終了
        break;
    }
}

function jmpGame(game) {
    window.location.href = "../Game?gmid=" + game;
}

function jmpGroup(game, group) {
    window.location.href = "../Group?gmid=" + game + "&grid=" + group;
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
            gameList.append($("<option>").val(gameListByGenre[selected][game]["Ad"]).text(gameListByGenre[selected][game]["Name"]));
        }
    }
    // clear textbox.
    $("#TargePID").val("");
    $("#TargetNickname").val("");
}
