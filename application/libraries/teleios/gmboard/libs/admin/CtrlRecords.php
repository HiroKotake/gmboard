<?php
namespace teleios\gmboard\libs\admin;

use teleios\utils\DBUtility;
use teleios\utils\StringUtility;
use teleios\gmboard\Beans\Bean;
use teleios\gmboard\dao\TableCtl;
use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\GamePlayers;
use teleios\gmboard\dao\GroupBoard;
use teleios\gmboard\dao\GroupNotices;
use teleios\gmboard\dao\Groups;
use teleios\gmboard\dao\PlayerIndex;
use teleios\gmboard\dao\RegistBooking;
use teleios\gmboard\dao\UserBoard;
use teleios\gmboard\dao\UserInfos;
use teleios\gmboard\dao\Users;

/**
 * DB操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
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
        fclose($hFile);
    }

    /**
     * テーブル作成
     *
     * @return array [description]
     */
    public function makeTables() : array
    {
        $result = array();
        if ($hDir = opendir($this->sourceDir)) {
            $daoTableCtl = new TableCtl();
            while (($file = readdir($hDir)) !== false) {
                if (preg_match('/^([!-~]+)(\.json)$/u', $file, $elm)) {
                    if ($elm[1] != "footer") {
                        $result[$elm[1]] = $daoTableCtl->makeTable($this->sourceDir . DIRECTORY_SEPARATOR . $file, $this->sourceDir . DIRECTORY_SEPARATOR . "footer.json");
                    }
                }
            }
            closedir($hDir);
        }
        return $result;
    }

    /**
     * 全テーブル削除
     * @return array [description]
     */
    public function clearDB() : array
    {
        $daoTableCtl = new TableCtl();
        return $daoTableCtl->dropAllTable();
    }

    /**
     * 設定ファイルから指定したテーブルのデータを取得する
     * @param  string $tableName [description]
     * @return array             [description]
     */
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

    /**
     * ゲーム情報 (GameInfo, Groups_xxxxxxx, RegistBooking_xxxxxxxxx)を登録
     */
    public function makeGames() : void
    {
        $daoGameInfos = new GameInfos();
        $daoGamePlayers = new GamePlayers();
        $daoRegistBooking = new RegistBooking();
        $daoGroups = new Groups();
        $daoGroupBoard = new GroupBoard();
        $daoGroupNotices = new GroupNotices();
        $datas = $this->makeData("GameInfos");
        foreach ($datas as $record) {
            // ゲーム追加
            $daoGameInfos->attach($record);
            // プレイヤー管理テーブル追加
            $daoGamePlayers->createTable($record["GameId"]);
            // プレイヤー予約管理テーブル追加
            $daoRegistBooking->createTable($record["GameId"]);
            // ゲーム別グループ管理テーブル追加
            $daoGroups->createTable($record["GameId"]);
            // ゲーム別全体ボード
            $daoGroupBoard->createTable($record["GameId"], 0);
            // ゲーム別告知
            $daoGroupNotices->createTable($record["GameId"], 0);
        }
        // GameListのバージョンを初期化
        $this->cIns->sysComns->set(SYSTEM_KEY_GAMELIST_VER, 1);
    }

    /**
     * ゲーム情報 (GameInfo, Groups_xxxxxxx, RegistBooking_xxxxxxxxx)を削除
     */
    public function removeGames() : void
    {
            $daoTableCtl = new TableCtl();
            // ゲーム削除
            $daoTableCtl->truncateTable("GameInfos");
            // プレイヤー管理テーブル削除
            $gamePlayersTables = $daoTableCtl->showSubTables(TABLE_PREFIX_GAME_PLAYER);
            foreach ($gamePlayersTables as $table) {
                $daoTableCtl->dropTable($table);
            }
            // プレイヤー予約管理テーブル削除
            $registBookingTables = $daoTableCtl->showSubTables(TABLE_PREFIX_REGIST_BOOKING);
            foreach ($registBookingTables as $table) {
                $daoTableCtl->dropTable($table);
            }
            // ゲーム別グループ管理テーブル削除
            $groupTables = $daoTableCtl->showSubTables(TABLE_PREFIX_GROUP);
            foreach ($groupTables as $table) {
                $daoTableCtl->dropTable($table);
            }
    }

    /**
     * ユーザ (Users, UserInfos, UBoard_xxxxxxxxxxxx)を登録
     */
    public function makeUsers() : void
    {
        $daoUsers = new Users();
        $daoUserBoard = new UserBoard();
        $daoUserInfos = new UserInfos();
        $datas = $this->makeData("Users");
        foreach ($datas as $user) {
            $user['Password'] = StringUtility::getHashedPassword($user['Password']);
            $daoUsers->attach($user);
            $daoUserBoard->createTable($user["UserId"]);
            $message = array(
                'FromUserId'    => SYSTEM_USER_ID,                // 送信者ユーザID
                'FromUserName'  => SYSTEM_USER_NAME,              // 送信者表示名
                'FromGroupId'   => SYSTEM_GROUP_ID,               // 送信者グループID
                'FromGroupName' => SYSTEM_GROUP_NAME,             // 送信者グループ名
                'message'       => 'ようこそ、いらっしゃいました！'    // メッセージテキスト
            );
            $daoUserBoard->add($user["UserId"], $message);
            $daoUserInfos->attach(["UserInfoId" => $user["UserId"]]);
        }
    }

    /**
     * ユーザ (Users, UserInfos, UBoard_xxxxxxxxxxxx)を削除
     */
    public function removeUsers() : void
    {
        $daoTableCtl = new TableCtl();
        // UBorad_
        $userBoardTables = $daoTableCtl->showSubTables(TABLE_PREFIX_USER_BOARD);
        foreach ($userBoardTables as $table) {
            $daoTableCtl->dropTable($table);
        }
        // UserInfos
        $daoTableCtl->truncateTable("UserInfos");
        // Users
        $daoTableCtl->truncateTable("Users");
    }

    /**
     * ユーザのゲーム (PlayerIndex)を登録
     */
    public function makePlayerIndex() : void
    {
        $daoPlayerIndex = new PlayerIndex();
        $daoGamePlayers = new GamePlayers();
        $records = $this->makeData("PlayerIndex");
        foreach ($records as $player) {
            $daoPlayerIndex->add($player["UserId"], $player["GameId"]);
            $data = array(
                "UserId"       => $player["UserId"],
                "PlayerId"     => $player["PlayerId"],
                "GameNickname" => $player["GameNickname"]
            );
            $daoGamePlayers->add($player["GameId"], $data);
        }
    }

    /**
     * ユーザのゲーム (PlayerIndex)を削除
     */
    public function removePlayerIndex() : void
    {
        // 重複動作しないように処理済み判定フラグ集を生成
        $daoGameInfos = new GameInfos();
        $fGames = array();
        $gameRecords = $daoGameInfos->getAllRecords();
        foreach ($gameRecords as $game) {
            $fGames[$game["GameId"]] = false;
        }
        // 対象を初期化
        $daoPlayerIndex = new PlayerIndex();
        $daoGamePlayers = new GamePlayers();
        $records = $daoPlayerIndex->getAllRecords();
        foreach ($records as $user) {
            if (!$fGames[$user["GameId"]]) {
                $daoGamePlayers->clearTable($user["GameId"]);
                $fGames[$user["GameId"]] = true;
            }
        }
        $daoPlayerIndex->clearTable();
    }

    /**
     * グループ (Groups_xxxxxxxx(レコード追加),  GBoard_xxxxxxxx_xxxxxxxxxx(作成),  GNotice_xxxxxxxx_xxxxxxxxxx(作成))を登録
     */
    public function makeGroups() : void
    {
        $daoGroups = new Groups();
        $daoGamePlayers = new GamePlayers();
        $daoGroupBoard = new GroupBoard();
        $daoGroupNotices = new GroupNotices();
        $records = $this->makeData("Group");
        foreach ($records as $player) {
           $data = array(
               "GroupId" => $player["GroupId"],
               "AliasId" => $player["AliasId"],
               "GroupName" => $player["GroupName"],
               "Leader" => $player["Leader"],
               "Description" => $player["Description"]
           );
           $daoGroups->add($player["GameId"], $data);
           $data2 = array(
               "GroupId" => $player["GroupId"],
               "Authority" => GROUP_AUTHORITY_LEADER
           );
           $daoGamePlayers->set($player["GameId"], $player["Leader"], $data2);
           $daoGroupBoard->createTable($player["GameId"], $player["GroupId"]);
           $daoGroupNotices->createTable($player["GameId"], $player["GroupId"]);
        }
    }

    /**
     * グループ (Groups_xxxxxxxx(レコード追加),  GBoard_xxxxxxxx_xxxxxxxxxx(作成),  GNotice_xxxxxxxx_xxxxxxxxxx(作成))を削除
     */
    public function removeGroups() : void
    {
        // 重複動作しないように処理済み判定フラグ集を生成
        $daoGameInfos = new GameInfos();
        $fGames = array();
        $gameRecords = $daoGameInfos->getAllRecords();
        foreach ($gameRecords as $game) {
            $fGames[$game["GameId"]] = false;
        }
        // PlayerIndexをall selectし、対象のゲームを抽出
        $daoPlayerIndex = new PlayerIndex();
        $playerRecords = $daoPlayerIndex->getAllRecords();
        foreach ($playerRecords as $player) {
            if (!$fGames[$player["GameId"]]) {
                $fGames[$player["GameId"]] = true;
            }
        }
        // ゲーム別で登録グループを消去
        $daoGroups = new Groups();
        $daoTableCtl = new TableCtl();
        // Group_...をクリア
        foreach ($fGames as $gameId => $flag) {
            if (!$flag) {
                continue;
            }
            $daoGroups->clearTable($gameId);
        }
        // GBoard_...をドロップ
        $groupBoardTables = $daoTableCtl->showSubTables(TABLE_PREFIX_GROUP_BOARD);
        foreach ($groupBoardTables as $table) {
            $daoTableCtl->dropTable($table);
        }
        // GNotices_...をドロップ
        $groupNoticeTables = $daoTableCtl->showSubTables(TABLE_PREFIX_GROUP_NOTICE);
        foreach ($groupNoticeTables as $table) {
            $daoTableCtl->dropTable($table);
        }
    }

    /**
     * グループメンバー (GamePlayers_xxxxxxxx, GBoard_xxxxxxxx_xxxxxxxxxx)を登録
     */
    public function makeGroupMember() : void
    {
        $daoGamePlayers = new GamePlayers();
        $daoGroupBoard = new GroupBoard();
        $records = $this->makeData("GroupMember");
        foreach ($records as $member) {
            $data = array(
                "GroupId" => $member["GroupId"],
                "Authority" => $member["Authority"],
                "AliasId" => $member["GamePlayersAliasId"]
            );
            $daoGamePlayers->set($member["GameId"], $member["UserId"], $data);
            // ユーザ新規参加メッセージ追加
            $line = $daoGamePlayers->getByUserId($member["GameId"], $member["UserId"]);
            if (!empty($line)) {
                $message = array(
                    "AliasId" => $member["GBoardAliasId"],
                    'UserId' => $member["UserId"],
                    "GamePlayerId" => SYSTEM_NOTICE_ID,
                    "GameNickname" => SYSTEM_NOTICE_NAME,
                    "Idiom" => 1,
                    "Message" => $line->GameNickname
                );
                $daoGroupBoard->add($member["GameId"], $member["GroupId"], $message);
            }
        }
    }

    /**
     * グループメンバー (GamePlayers_xxxxxxxx, GBoard_xxxxxxxx_xxxxxxxxxx)を削除
     */
    public function removeGroupMember() : void
    {
        $daoGamePlayers = new GamePlayers();
        $daoGroupBoard = new GroupBoard();
        $records = $this->makeData("GroupMember");
        foreach ($records as $member) {
            $data = array(
                "GroupId" => null,
                "Authority" => 0
            );
            $daoGamePlayers->set($member["GameId"], $member["UserId"], $data);
            $daoGroupBoard->clearTable($member["GameId"], $member["GroupId"]);
        }
    }

    /**
     * グループ予約メンバー (RegistBooking_xxxxxxxx)を登録
     * [makeRegistBooking description]
     */
    public function makeRegistBooking() : void
    {

    }

    /**
     * グループ予約メンバー (RegistBooking_xxxxxxxx)を削除
     */
    public function removeRegistBooking() : void
    {

    }
}
