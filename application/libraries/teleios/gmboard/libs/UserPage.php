<?php
namespace teleios\gmboard\libs;

use teleios\utils\StringUtility;

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
    private function getGameList(int $userId) : array
    {
        $this->cIns->load->model('dao/PlayerIndex', 'daoPlayerIndex');
        $games = $this->cIns->daoPlayerIndex->getByUserId($userId);
        // Get game name from GameInfo Table.
        $gameIds = array();
        foreach ($games as $game) {
            $gameIds[] = $game['GameId'];
        }
        if (empty($gameIds)) {
            return $gameIds;
        }
        // ゲーム情報を取得
        $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
        $gameInfos = $this->cIns->daoGameInfos->getByGameIds($gameIds);
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
        $this->cIns->load->model('dao/GamePlayers', 'daoGamePlayer');
        $this->cIns->load->model('dao/Groups', 'daoGroups');
        foreach ($gameInfos as $game) {
            $tempData = $this->cIns->daoGamePlayer->getByUserId($game['GameId'], $userId);
            if (!empty($tempData)) {
                // グループの情報を取得
                $tempGroup = $this->cIns->daoGroups->getByGroupId($game['GameId'], $tempData['GroupId']);
                if (!empty($tempGroup)) {
                    $leader = $this->cIns->daoGamePlayer->getByUserId($game['GameId'], $tempGroup['Leader']);
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

    public function getPersonalMessage(int $userId, int $page = 0, int $number = 20, string $order = "DESC") : array
    {
        $offset = $page * $number;
        $this->cIns->load->model('dao/UserBoard', 'daoUserBoard');
        return $this->cIns->daoUserBoard->get($userId, $number, $offset, $order);
    }

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
            $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
            $tempList = $this->cIns->daoGameInfos->getAll(0, 0);
            // カテゴリ別に分類
            $list = array();
            foreach ($tempList as $gameInfo) {
                if (!array_key_exists($gameInfo['Genre'], $list)) {
                    $list[(int)$gameInfo['Genre']] = array();
                }
                $list[$gameInfo['Genre']][] = array(
                    'GameId'        => $gameInfo['GameId'],
                    'Name'          => $gameInfo['Name'],
                    'Description'   => $gameInfo['Description']
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
        // ゲームリスト(カテゴリ別)取得
        $gameList = $this->getGamelistWithCategory();
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

    public function attachGame(int $userId, int $gameId, string $playerId, string $gameNickname) : array
    {
        $this->cIns->load->model('dao/PlayerIndex', 'daoPlayerIndex');
        $this->cIns->load->model('dao/GamePlayers', 'daoGamePlayers');
        // PlayerIndexテーブルへ情報を追加
        $playerIndexId = $this->cIns->daoPlayerIndex->add($userId, $gameId);
        // GamePlayers_xxxxxxxxテーブルへ情報を追加
        $gamePlayersData = array(
            'UserId'        => $userId,
            'PlayerId'      => $playerId,
            'GameNickname'  => $gameNickname
        );
        $gamePlsyersId = $this->cIns->daoGamePlayers->add($gameId, $playerId, $gamePlayersData);
        return array(
            'PlayerIndexId' => $playerIndexId,
            'GamePlayersId' => $gamePlsyersId
        );
    }
}
