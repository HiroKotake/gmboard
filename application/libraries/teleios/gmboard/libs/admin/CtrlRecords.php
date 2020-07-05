<?php
namespace teleios\gmboard\libs\admin;

use teleios\utils\DBUtility;
use teleios\utils\StringUtility;

class CtrlRecords
{
    private $cIns = null;
    private $data = null;
    private $filename = APPPATH . "Resources/data/test/data.json";
    private $sourceDir = APPPATH . "Resources/data/database";

    public function __construct()
    {
        $this->cIns =& get_instance();
        $hFile = fopen($this->filename, "r");
        $jsonText = fread($hFile, filesize($this->filename));
        $this->data = json_decode($jsonText, true);
    }

    public function makeTables() : array
    {
        $result = array();
        if ($hDir = opendir($this->sourceDir)) {
            $this->cIns->load->model("dao/TableCtl", "daoTableCtl");
            while (($file = readdir($hDir)) !== false) {
                if (preg_match('/^([!-~]+)(\.json)$/u', $file, $elm)) {
                    if ($elm[1] != "footer") {
                        $result[$elm[1]] = $this->cIns->daoTableCtl->makeTable($this->sourceDir . DIRECTORY_SEPARATOR . $file, $this->sourceDir . DIRECTORY_SEPARATOR . "footer.json");
                    }
                }
            }
            closedir($hDir);
        }
        return $result;
    }

    public function clearDB() : array
    {
        $this->cIns->load->model("dao/TableCtl", "daoTableCtl");
        return $this->cIns->daoTableCtl->dropAllTable();
    }

    private function makeData(string $tableName) : array
    {
        $records = array();
        for($i = 1; $i < count($this->data["Data"][$tableName]); $i++) {
            $temp = array();
            for($j = 0; $j < count($this->data["Columns"][$tableName]); $j++) {
                $temp[$this->data["Columns"][$tableName][$j]] = $this->data["Data"][$tableName][$i][$j];
            }
            $records[] = $temp;
        }
        return $records;
    }

    // ゲーム情報 (GameInfo, Groups_xxxxxxx, RegistBooking_xxxxxxxxx)
    // 登録
    public function makeGames() : void
    {
        $this->cIns->load->model("dao/GameInfos", "daoGameInfos");
        $this->cIns->load->model('dao/GamePlayers', 'daoGamePlayers');
        $this->cIns->load->model('dao/RegistBooking', 'daoRegistBooking');
        $this->cIns->load->model('dao/Groups', 'daoGroups');
        $datas = $this->makeData("GameInfos");
        foreach ($datas as $record) {
            // ゲーム追加
            $this->cIns->daoGameInfos->attach($record);
            // プレイヤー管理テーブル追加
            $this->cIns->daoGamePlayers->createTable($record["GameId"]);
            // プレイヤー予約管理テーブル追加
            $this->cIns->daoRegistBooking->createTable($record["GameId"]);
            // ゲーム別グループ管理テーブル追加
            $this->cIns->daoGroups->createTable($record["GameId"]);
        }
        // GameListのバージョンを初期化
        $this->cIns->sysComns->set(SYSTEM_KEY_GAMELIST_VER, 1);
    }
    // 削除
    public function removeGames() : void
    {
            $this->cIns->load->model("dao/TableCtl", "daoTableCtl");
            // ゲーム削除
            $this->cIns->daoTableCtl->truncateTable("GameInfos");
            // プレイヤー管理テーブル削除
            $gamePlayersTables = $this->cIns->daoTableCtl->showSubTables(TABLE_PREFIX_GAME_PLAYER);
            foreach ($gamePlayersTables as $table) {
                $this->cIns->daoTableCtl->dropTable($table);
            }
            // プレイヤー予約管理テーブル削除
            $registBookingTables = $this->cIns->daoTableCtl->showSubTables(TABLE_PREFIX_REGIST_BOOKING);
            foreach ($registBookingTables as $table) {
                $this->cIns->daoTableCtl->dropTable($table);
            }
            // ゲーム別グループ管理テーブル削除
            $groupTables = $this->cIns->daoTableCtl->showSubTables(TABLE_PREFIX_GROUP);
            foreach ($groupTables as $table) {
                $this->cIns->daoTableCtl->dropTable($table);
            }
    }

    // ユーザ (Users, UserInfos, UBoard_xxxxxxxxxxxx)
    // 登録
    public function makeUsers() : void
    {
        $this->cIns->load->model("dao/Users", "daoUsers");
        $this->cIns->load->model("dao/UserBoard", "daoUserBoard");
        $this->cIns->load->model("dao/UserInfos", "daoUserInfos");
        $datas = $this->makeData("Users");
        foreach ($datas as $user) {
            $user['Password'] = StringUtility::getHashedPassword($user['Password']);
            $this->cIns->daoUsers->attach($user);
            $this->cIns->daoUserBoard->createTable($user["UserId"]);
            $this->cIns->daoUserInfos->attach(["UserId" => $user["UserId"]]);
        }
    }
    // 削除
    public function removeUsers() : void
    {
        $this->cIns->load->model("dao/TableCtl", "daoTableCtl");
        // UBorad_
        $userBoardTables = $this->cIns->daoTableCtl->showSubTables(TABLE_PREFIX_USER_BOARD);
        foreach ($userBoardTables as $table) {
            $this->cIns->daoTableCtl->dropTable($table);
        }
        // UserInfos
        $this->cIns->daoTableCtl->truncateTable("UserInfos");
        // Users
        $this->cIns->daoTableCtl->truncateTable("Users");

    }

    // ユーザのゲーム (PlayerIndex)
    // 登録
    public function makePlayerIndex() : void
    {
        $this->cIns->load->model("dao/PlayerIndex", "daoPlayerIndex");
        $this->cIns->load->model("dao/GamePlayers", "daoGamePlayers");
        $records = $this->makeData("PlayerIndex");
        foreach ($records as $player) {
            $this->cIns->daoPlayerIndex->add($player["UserId"], $player["GameId"]);
            $data = array(
                "UserId"       => $player["UserId"],
                "PlayerId"     => $player["PlayerId"],
                "GameNickname" => $player["GameNickname"]
            );
            $this->cIns->daoGamePlayers->add($player["GameId"], $data);
        }
    }
    // 削除
    public function removePlayerIndex() : void
    {
        // 重複動作しないように処理済み判定フラグ集を生成
        $this->cIns->load->model("dao/GameInfos", "daoGameInfos");
        $fGames = array();
        $gameRecords = $this->cIns->daoGameInfos->getAllRecords();
        foreach ($gameRecords as $game) {
            $fGames[$game["GameId"]] = false;
        }
        // 対象を初期化
        $this->cIns->load->model("dao/PlayerIndex", "daoPlayerIndex");
        $this->cIns->load->model("dao/GamePlayers", "daoGamePlayers");
        $records = $this->cIns->daoPlayerIndex->getAllRecords();
        foreach ($records as $user) {
            if (!$fGames[$user["GameId"]]) {
                $this->cIns->daoGamePlayers->clearTable($user["GameId"]);
                $fGames[$user["GameId"]] = true;
            }
        }
        $this->cIns->daoPlayerIndex->clearTable();
    }

    // グループ (Groups_xxxxxxxx(レコード追加),  GBoard_xxxxxxxx_xxxxxxxxxx(作成),  GNotice_xxxxxxxx_xxxxxxxxxx(作成))
    // 登録
    public function makeGroups() : void
    {
        $this->cIns->load->model("dao/Groups", "daoGroups");
        $this->cIns->load->model("dao/GamePlayers", "daoGamePlayers");
        $this->cIns->load->model("dao/GroupBoard", "daoGroupBoard");
        $this->cIns->load->model("dao/GroupNotices", "daoGroupNotices");
        $records = $this->makeData("Group");
        foreach ($records as $player) {
           $data = array(
               "GroupId" => $player["GroupId"],
               "GroupName" => $player["GroupName"],
               "Leader" => $player["Leader"],
               "Description" => $player["Description"]
           );
           $this->cIns->daoGroups->add($player["GameId"], $data);
           $data2 = array(
               "GroupId" => $player["GroupId"],
               "Authority" => GROUP_AUTHORITY_LEADER
           );
           $this->cIns->daoGamePlayers->set($player["GameId"], $player["Leader"], $data2);
           $this->cIns->daoGroupBoard->createTable($player["GameId"], $player["GroupId"]);
           $this->cIns->daoGroupNotices->createTable($player["GameId"], $player["GroupId"]);
        }
    }
    // 削除
    public function removeGroups() : void
    {
        // 重複動作しないように処理済み判定フラグ集を生成
        $this->cIns->load->model("dao/GameInfos", "daoGameInfos");
        $fGames = array();
        $gameRecords = $this->cIns->daoGameInfos->getAllRecords();
        foreach ($gameRecords as $game) {
            $fGames[$game["GameId"]] = false;
        }
        // PlayerIndexをall selectし、対象のゲームを抽出
        $this->cIns->load->model("dao/PlayerIndex", "daoPlayerIndex");
        $playerRecords = $this->cIns->daoPlayerIndex->getAllRecords();
        foreach ($playerRecords as $player) {
            if (!$fGames[$player["GameId"]]) {
                $fGames[$player["GameId"]] = true;
            }
        }
        // ゲーム別で登録グループを消去
        $this->cIns->load->model("dao/Groups", "daoGroups");
        $this->cIns->load->model("dao/TableCtl", "daoTableCtl");
        // Group_...をクリア
        foreach ($fGames as $gameId => $flag) {
            if (!$flag) {
                continue;
            }
            $this->cIns->daoGroups->clearTable($gameId);
        }
        // GBoard_...をドロップ
        $groupBoardTables = $this->cIns->daoTableCtl->showSubTables(TABLE_PREFIX_GROUP_BOARD);
        foreach ($groupBoardTables as $table) {
            $this->cIns->daoTableCtl->dropTable($table);
        }
        // GNotices_...をドロップ
        $groupNoticeTables = $this->cIns->daoTableCtl->showSubTables(TABLE_PREFIX_GROUP_NOTICE);
        foreach ($groupNoticeTables as $table) {
            $this->cIns->daoTableCtl->dropTable($table);
        }
    }

    // グループメンバー (GamePlayers_xxxxxxxx, GBoard_xxxxxxxx_xxxxxxxxxx)
    // 登録
    public function makeGroupMember() : void
    {
        $this->cIns->load->model("dao/GamePlayers", "daoGamePlayers");
        $this->cIns->load->model("dao/GroupBoard", "daoGroupBoard");
        $records = $this->makeData("GroupMember");
        foreach ($records as $member) {
            $data = array(
                "GroupId" => $member["GroupId"],
                "Authority" => $member["Authority"]
            );
            $this->cIns->daoGamePlayers->set($member["GameId"], $member["UserId"], $data);
            // ユーザ新規参加メッセージ追加
            $line = $this->cIns->daoGamePlayers->getByUserId($member["GameId"], $member["UserId"]);
            if (!empty($line)) {
                $message = array(
                    'UserId' => $member["UserId"],
                    "GamePlayerId" => SYSTEM_NOTICE_ID,
                    "GameNickname" => SYSTEM_NOTICE_NAME,
                    "Idiom" => 1,
                    "Message" => $line["GameNickname"]
                );
                $this->cIns->daoGroupBoard->add($member["GameId"], $member["GroupId"], $message);
            }
        }
    }
    // 削除
    public function removeGroupMember() : void
    {
        $this->cIns->load->model("dao/GamePlayers", "daoGamePlayers");
        $this->cIns->load->model("dao/GroupBoard", "daoGroupBoard");
        $records = $this->makeData("GroupMember");
        foreach ($records as $member) {
            $data = array(
                "GroupId" => null,
                "Authority" => 0
            );
            $this->cIns->daoGamePlayers->set($member["GameId"], $member["UserId"], $data);
            $this->cIns->daoGroupBoard->clearTable($member["GameId"], $member["GroupId"]);
        }
    }

    // グループ予約メンバー (RegistBooking_xxxxxxxx)
    // 登録
    public function makeRegistBooking() : void
    {

    }
    // 削除
    public function removeRegistBooking() : void
    {

    }
}
