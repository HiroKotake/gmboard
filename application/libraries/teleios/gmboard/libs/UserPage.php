<?php
namespace teleios\gmboard\libs;

use teleios\utils\StringUtility;
use teleios\gmboard\dao\PlayerIndex;
use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\GamePlayers;
use teleios\gmboard\dao\Groups;
use teleios\gmboard\dao\UserBoard;

/**
 * ユーザページ関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class UserPage
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
    }

    /**
     * ユーザが登録しているゲーム一覧を取得
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function getGameList(int $userId) : array
    {
        $daoPlayerIndex = new PlayerIndex();
        $games = $daoPlayerIndex->getByUserId($userId);
        // Get game name from GameInfo Table.
        $gameIds = array();
        foreach ($games as $game) {
            $gameIds[] = $game['GameId'];
        }
        if (empty($gameIds)) {
            return $gameIds;
        }
        // ゲーム情報を取得
        $daoGameInfos = new GameInfos();
        $gameInfos = $daoGameInfos->getByGameIds($gameIds);
        return $gameInfos;
    }

    /**
     * ユーザが登録しているゲーム一覧から所属しているグループを取得
     * @param  int   $userId    [description]
     * @param  array $gameInfos [description]
     * @return array            [description]
     */
    private function getGroups(int $userId, array $gameInfos) : array
    {
        $groups = array();
        if (count($gameInfos) <= 0) {
            return $groups;
        }
        $daoGamePlayer = new GamePlayers();
        $daoGroups = new Groups();
        foreach ($gameInfos as $game) {
            $tempData = $daoGamePlayer->getByUserId($game['GameId'], $userId);
            if (!empty($tempData) && !empty($tempData['GroupId'])) {
                // グループの情報を取得
                $tempGroup = $daoGroups->getByGroupId($game['GameId'], $tempData['GroupId']);
                if (!empty($tempGroup)) {
                    $leader = $daoGamePlayer->getByUserId($game['GameId'], $tempGroup['Leader']);
                    $groups[] = array(
                        'GameName' => $game['Name'],
                        'GroupId' => $tempData['GroupId'],
                        'GroupName' => $tempGroup['GroupName'],
                        'GroupDescription' => $tempGroup['Description'],
                        'LeaderId' => $leader['UserId'],
                        'LeaderNickname' => $leader['GameNickname'],
                        'PlayerId' => $tempData['PlayerId'],
                        'GameNickname' => $tempData['GameNickname'],
                    );
                }
            }
        }
        return $groups;
    }

    /**
     * 個人向けメッセージ取得
     * @param  int     $userId [description]
     * @param  integer $page   [description]
     * @param  integer $number [description]
     * @param  string  $order  [description]
     * @return array           [description]
     */
    public function getPersonalMessage(int $userId, int $page = 0, int $number = 20, string $order = "DESC") : array
    {
        $offset = $page * $number;
        $daoUserBoard = new UserBoard();
        return $daoUserBoard->get($userId, $number, $offset, $order);
    }

    /**
     * カテゴリ別ゲーム一覧取得
     * @return array [description]
     */
    public function getGamelistWithCategory() : array
    {
        $gameListWithCategory = array();
        // redisからデータ取得
        $currentVer = $this->cIns->sysComns->get(SYSTEM_KEY_GAMELIST_VER);
        $gameListWithCategory = unserialize($this->cIns->redis->get(KEY_GAME_CATEGORY));
        // キャッシュの存在確認と存在した場合の保持データのバージョン確認
        $fRedisIn = empty($gameListWithCategory);
        if (!$fRedisIn) {
            $fRedisIn = $gameListWithCategory[SYSTEM_KEY_GAMELIST_VER] != $currentVer;
        }
        // redisにない、もしくはデータが古い場合にデータ作成し、redisへ投入
        if ($fRedisIn) {
            // 全ゲームリスト取得
            $daoGameInfos = new GameInfos();
            $tempList = $daoGameInfos->getAll(0, 0);
            // カテゴリ別に分類
            $list = array();
            foreach ($tempList as $gameInfo) {
                if (!array_key_exists($gameInfo['Genre'], $list)) {
                    $list[(int)$gameInfo['Genre']] = array();
                }
                $list[$gameInfo['Genre']][$gameInfo['GameId']] = array(
                    'Ub'        => $gameInfo['GameId'],
                    'Name'          => $gameInfo['Name'],
                    'Desc'   => $gameInfo['Description'],
                    'Joined'        => 0
                );
            }
            // ゲームが存在しないカテゴリは除外する
            $list2 = array();
            foreach ($list as $key=>$games) {
                if (count($games) > 0) {
                    $list2[$key] = $games;
                }
            }
            //
            $gameListWithCategory = array(
                SYSTEM_KEY_GAMELIST_VER => $currentVer,
                "GameListWithCategory" => $list2
            );
            // redisへ登録
            $this->cIns->redis->set(KEY_GAME_CATEGORY, serialize($gameListWithCategory));
        }
        return $gameListWithCategory["GameListWithCategory"];
    }

    /**
     * 個人向けにゲームリストを登録状況に合わせてカスタマイズする
     * @param  array $gameList      [description]
     * @param  array $attachedGames [description]
     * @return array                [description]
     */
    public function getGameListsModifedByPersonal(array $gameList, array $attachedGames) : array
    {
        if (count($attachedGames) == 0) {
            return $gameList;
        }
        $data = array();
        foreach ($gameList as $genre => $datas) {
            $data[$genre] = array();
            foreach ($datas as $info) {
                foreach ($attachedGames as $game) {
                    if ($info['Ub'] == $game['GameId']) {
                        $info['Joined'] = 1;
                    }
                }
                $data[$genre][] = $info;
            }
        }
        return $data;
    }

    /**
     * ユーザページの初期画面で表示するデータを取得する
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function getPageData(int $userId) : array
    {
        // 登録ゲーム一覧取得
        $gameInfos = $this->getGameList($userId);
        // 登録グループ取得
        $groupInfos = array();
        if (!empty($gameInfos)) {
            $groupInfos = $this->getGroups($userId, $gameInfos);
        }
        // 個人向けメッセージ取得
        $personalMessage = $this->getPersonalMessage($userId);
        // ゲームリスト(カテゴリ別)取得 (個人別にカスタマイズしたもの)
        //$gameList = $this->getGamelistWithCategory();
        $gameList = $this->getGameListsModifedByPersonal($this->getGamelistWithCategory(), $gameInfos);
        // カテゴリリスト作成
        $categorys = array();
        foreach(array_keys($gameList) as $key) {
            if (!empty($key)) {
                $categorys[$key] = GAME_CATEGORY_RB[$key];
            }
        }
        $data = array(
            'GameInfos' => $gameInfos,
            'GroupInfos' => $groupInfos,
            'Message' => $personalMessage,
            'GameList' => $gameList,
            'GameGenre' => $categorys,
            'GamesListVer' => $this->cIns->sysComns->get(SYSTEM_KEY_GAMELIST_VER),
        );
        return $data;
    }

    /**
     * ゲームを追加する
     * @param  int    $userId       [description]
     * @param  int    $gameId       [description]
     * @param  string $playerId     [description]
     * @param  string $gameNickname [description]
     * @return array                [description]
     */
    public function attachGame(int $userId, int $gameId, string $playerId, string $gameNickname) : array
    {
        $data = array(
            'Status' => DB_STATUS_EXISTED,
            'PlayerIndexId' => null,
            'GamePlayersId' => null
        );
        $daoPlayerIndex = new PlayerIndex();
        $daoGamePlayers = new GamePlayers();
        // 登録済み確認
        $fExist = $daoPlayerIndex->isExist($userId, $playerId);
        if ($fExist) {
            return $data;
        }
        // PlayerIndexテーブルへ情報を追加
        $data["PlayerIndexId "] = $daoPlayerIndex->add($userId, $gameId);
        // GamePlayers_xxxxxxxxテーブルへ情報を追加
        $gamePlayersData = array(
            'UserId'        => $userId,
            'PlayerId'      => $playerId,
            'GameNickname'  => $gameNickname
        );
        $data["GamePlsyersId"] = $daoGamePlayers->add($gameId, $gamePlayersData);
        $data["Status"] = DB_STATUS_ADDED;
        return $data;
    }
}
