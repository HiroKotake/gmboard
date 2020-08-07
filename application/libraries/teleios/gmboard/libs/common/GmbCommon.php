<?php
namespace teleios\gmboard\libs\common;

use teleios\utils\StringUtility;
use teleios\utils\Identifier;
use teleios\gmboard\dao\Bean;
use teleios\gmboard\dao\CiSessions;
use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\GamePlayers;
use teleios\gmboard\dao\Groups;
use teleios\gmboard\dao\PlayerIndex;

class GmbCommon
{
    protected $cIns = null;
    protected $ciSession = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
        $this->ciSession = new Cisessions;
    }

    /**
     * エイリアスIDからゲームIDを取得する
     * キャッシュを検索し、なければDBから取得する。その後、キャッシュに追加する
     * @param  string $obfuscateId 難読化済エイリアスID
     * @return int                 存在する場合はゲームIDを返し、存在しない場合は 0 を返す
     */
    public function trnasAliasToGameId(string $obfuscateId) : int
    {
        $aliasId = Identifier::sftDecode($obfuscateId);
        $aliasList = unserialize($this->cIns->redis->get(KEY_ALIAS_GAME));
        if (empty($aliasList)) {
            $aliasList = array();
        }
        if (array_key_exists($aliasId, $aliasList)) {
            return $aliasList[$aliasId];
        }
        $daoGameInfos = new GameInfos();
        $gameInfo = $daoGameInfos->getByAlias($obfuscateId);
        if ($gameInfo->isEmpty()) {
            return 0;
        }
        $aliasList[$aliasId] = $gameInfo->GameId;
        $this->cIns->redis->set(KEY_ALIAS_GAME, serialize($aliasList));
        return $gameInfo->GameId;
    }

    /**
     * ユーザが登録しているゲーム一覧を取得
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function getGameList(int $userId) : array
    {
        if ($this->ciSession->isSet(SESSION_INFO_GAME)) {
            // セッションを確認し、格納済みならば、そこから値を取得し返す
            return unserialize($this->ciSession->getSessionData(SESSION_INFO_GAME));
        }
        $daoPlayerIndex = new PlayerIndex();
        $games = $daoPlayerIndex->getByUserId($userId);
        // Get game name from GameInfo Table.
        $gameIds = array();
        foreach ($games as $game) {
            $gameIds[] = $game->GameId;
        }
        if (empty($gameIds)) {
            return $gameIds;
        }
        // ゲーム情報を取得
        $daoGameInfos = new GameInfos();
        $result = $daoGameInfos->getByGameIds($gameIds);
        // データの縮小
        $gameInfos = array();
        foreach ($result as $gInfo) {
            $gameInfos[] = array(
                "GameId" => $gInfo->GameId,
                "AliasId" => $gInfo->AliasId,
                "Genre" => $gInfo->Genre,
                "Name" => $gInfo->Name,
                "Description" => $gInfo->Description
            );
        }
        // セッションに格納
        $this->ciSession->setSessionData(SESSION_INFO_GAME, serialize($gameInfos));
        return $gameInfos;
    }

    /**
     * ユーザが登録しているゲーム一覧から所属しているグループを取得
     * @param  int   $userId    [description]
     * @param  array $gameInfos [description]
     * @return array            [description]
     */
    protected function getGroups(int $userId, array $gameInfos) : array
    {
        if ($this->ciSession->isSet(SESSION_INFO_GROUP)) {
            return unserialize($this->ciSession->getSessionData(SESSION_INFO_GROUP));
        }
        $groups = array();
        if (count($gameInfos) <= 0) {
            return $groups;
        }
        $daoGamePlayer = new GamePlayers();
        $daoGroups = new Groups();
        foreach ($gameInfos as $game) {
            $tempData = $daoGamePlayer->getByUserId($game['GameId'], $userId);
            //if (!$tempData->isEmpty() && !empty($tempData->GroupId)) {
            if (!$tempData->isEmpty() && !is_null($tempData->GroupId)) {
                // グループの情報を取得
                $tempGroup = $daoGroups->getByGroupId($game["GameId"], $tempData->GroupId);
                if (!$tempGroup->isEmpty()) {
                    $leader = $daoGamePlayer->getByUserId($game["GameId"], $tempGroup->Leader);
                    $groups[] = array(
                        'GameName' => $game["Name"],
                        'GameId' => $game["AliasId"],
                        //'GroupId' => $tempData->GroupId,
                        //'AliasId' => $tempData->AliasId,
                        'GroupId' => $tempData->AliasId,
                        'GroupName' => $tempGroup->GroupName,
                        'GroupDescription' => $tempGroup->Description,
                        //'LeaderId' => $leader->UserId,
                        'LeaderNickname' => $leader->GameNickname,
                        'PlayerId' => $tempData->PlayerId,
                        'GameNickname' => $tempData->GameNickname,
                    );
                }
            }
        }
        // セッションにデータを格納
        $this->ciSession->setSessionData(SESSION_INFO_GROUP, serialize($groups));
        return $groups;
    }

    /**
     * カテゴリ別ゲーム一覧取得
     * @return array [description]
     */
    public function getGamelistWithCategory() : array
    {
        $gameListWithCategory = array();
        // redisからデータ取得
        $currentVer = $this->cIns->redis->get(SYSTEM_KEY_GAMELIST_VER);
        if (empty($currentVer)) {
            $currentVer = $this->cIns->sysComns->get(SYSTEM_KEY_GAMELIST_VER);
            $this->cIns->redis->set(SYSTEM_KEY_GAMELIST_VER, $currentVer);
        }
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
                if (!array_key_exists($gameInfo->Genre, $list)) {
                    $list[(int)$gameInfo->Genre] = array();
                }
                //$list[$gameInfo->Genre][$gameInfo->GameId] = array(
                $list[$gameInfo->Genre][] = array(
                    'Ad' => $gameInfo->AliasId,
                    'Ub'    => $gameInfo->GameId,
                    'Name'  => $gameInfo->Name,
                    'Desc'   => $gameInfo->Description,
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
     * 個人の登録ゲームリストからグループ検索用の対象のジャンルリストと、ゲームリスト用データを生成する
     * @param  array $gameList [description]
     * @return array           [description]
     */
    public function getGroupGamelistWithCategory(array $gameList) : array
    {
        $list = array();
        if (count($gameList) <= 0) {
            return $list;
        }
        $genre = array();
        $games = array();
        foreach ($gameList as $game) {
            $genre[$game['Genre']] = GAME_CATEGORY_RB[$game['Genre']];
            $games[$game["Genre"]][] = array(
                "Ad" => $game["AliasId"],
                "Ub" => $game["GameId"],
                "Name" => $game["Name"]
            );
        }
        $list["Genre"] = $genre;
        $list["GameInfos"] = $games;
        return $list;
    }

    /**
     * 個人向けにゲームリストを登録状況に合わせてカスタマイズする
     * @param  array $gameList      [description]
     * @param  array $attachedGames [description]
     * @return array                [description]
     */
    public function getGameListsModifedByPersonal(array $gameList, array $attachedGames) : array
    {
        if ($this->ciSession->isSet(SESSION_LIST_GAME)) {
            return unserialize($this->ciSession->getSessionData(SESSION_LIST_GAME));
        }
        if (count($attachedGames) == 0) {
            $this->ciSession->setSessionData(SESSION_LIST_GAME, serialize($gameList));
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
                //$data[$genre][$info['Ub']] = $info;
                $data[$genre][] = $info;
            }
        }
        // セッションへ格納
        $this->ciSession->setSessionData(SESSION_LIST_GAME, serialize($data));
        return $data;
    }

    /**
     * ゲームが登録されていないジャンルを除外した配列を作成する
     * @param  array $gameList [description]
     * @return array           [description]
     */
    public function makeExistGenre(array $gameList): array
    {
        $categorys = array();
        foreach(array_keys($gameList) as $key) {
            if (!empty($key)) {
                $categorys[$key] = GAME_CATEGORY_RB[$key];
            }
        }
        return $categorys;
    }

    /**
     * ページの共通部分で表示するデータを取得する
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function getPageDataCommon(int $userId) : array
    {
        // 登録ゲーム一覧取得
        $gameInfos = $this->getGameList($userId);
        // 登録グループ取得
        $groupInfos = array();
        if (!empty($gameInfos)) {
            $groupInfos = $this->getGroups($userId, $gameInfos);
        }
        // ゲームリスト(カテゴリ別)取得 (個人別にカスタマイズしたもの)
        $groupGameList = $this->getGamelistWithCategory();
        $gameList = $this->getGameListsModifedByPersonal($groupGameList, $gameInfos);
        // グループ申請用ジャンル・ゲームリスト生成
        $groupDropDown = $this->getGroupGamelistWithCategory($gameInfos);
        // カテゴリリスト作成
        $categorys = $this->makeExistGenre($gameList);
        $data = array(
            'GameInfos' => $gameInfos,
            'GroupInfos' => $groupInfos,
            'GameGenre' => $categorys,
            'GameList' => $gameList,
            'GroupGenre' => (count($groupDropDown) > 0 ? $groupDropDown["Genre"] : null),
            'GroupGame' => (count($groupDropDown) > 0 ? $groupDropDown["GameInfos"] : null),
            'GamesListVer' => $this->cIns->sysComns->get(SYSTEM_KEY_GAMELIST_VER),
        );
        return $data;
    }

    /**
     * ログアウト処理
     */
    public function logout() : void
    {
        $this->ciSession->deleteSession(session_id());
    }
}
