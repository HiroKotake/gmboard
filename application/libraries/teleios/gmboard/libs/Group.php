<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\libs\common\Personal;
use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\Groups;
use teleios\gmboard\dao\RegistBooking;
use teleios\gmboard\dao\GamePlayers;

/**
 * グループ関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class Group extends Personal
{
    private $daoGroups = null;

    public function __construct()
    {
        parent::__construct();
        $this->daoGroups = new Groups();
    }

    /**
     * グループネームで検索を実行
     *
     * @param  int     $gameId    [description]
     * @param  string  $groupName [description]
     * @param  int     $userId    [description]
     * @param  integer $page      [description]
     * @param  integer $number    [description]
     * @return array              [description]
     */
    public function searchByName(int $gameId, string $groupName, int $userId, int $page = 0, int $number = 20) : array
    {
        $data = array();
        // ユーザのグループ登録状況を確認するためにGamePlayerテーブルから対象ユーザを取得
        $daoGamePlayers = new GamePlayers();
        $userInfo = $daoGamePlayers->getByUserId($gameId, $userId);
        // 検索
        // グループリストを取得し、登録状況カラムを追加。また、不要なデータを削除
        $listGroups = $this->daoGroups->getByGroupName($gameId, $groupName, $page, $number);
        $leaderIds = array();
        if (count($listGroups) > 0) {
            foreach ($listGroups as $group) {
                $leaderIds[] = $group["Leader"];
                $data[$group["GroupId"]] = array(
                    "GroupId" => $group["GroupId"],
                    "GroupName" => $group["GroupName"],
                    "Leader" => "",
                    "Joined" => ($group["GroupId"] == $userInfo["GroupId"] ? 1 : 0)
                );
            }
            // リーダーのニックネームを取得し、データに追記
            $leaders = $daoGamePlayers->getByLeaders($gameId, $leaderIds);
            foreach ($leaders as $ld) {
                $data[$ld["GroupId"]]["Leader"] = $ld["GameNickname"];
            }
        }
        $result = array(
            'TotalNumber' => $this->daoGroups->countByGroupName($gameId, $groupName),
            'GroupList' => $data
        );
        return $result;
    }

    /**
     * ユーザページで表示するデータを取得する
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
        // ゲームリスト(カテゴリ別)取得 (個人別にカスタマイズしたもの)
        $groupGameList = $this->getGamelistWithCategory();
        $gameList = $this->getGameListsModifedByPersonal($groupGameList, $gameInfos);
        // カテゴリリスト作成
        $categorys = $this->makeExistGenre($gameList);
        $data = array(
            'GameInfos' => $gameInfos,
            'GroupInfos' => $groupInfos,
            'GameGenre' => $categorys,
            'GameList' => $gameList,
            'GroupGame' => $groupGameList,
            'GamesListVer' => $this->cIns->sysComns->get(SYSTEM_KEY_GAMELIST_VER),
        );
        return $data;
    }
}
