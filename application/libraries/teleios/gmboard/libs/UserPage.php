<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\libs\common\Personal;
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
class UserPage extends Personal
{
    public function __construct()
    {
        parent::__construct();
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
        $groupGameList = $this->getGamelistWithCategory();
        $gameList = $this->getGameListsModifedByPersonal($groupGameList, $gameInfos);
        // カテゴリリスト作成
        $categorys = $this->makeExistGenre($gameList);
        $data = array(
            'GameInfos' => $gameInfos,
            'GroupInfos' => $groupInfos,
            'Message' => $personalMessage,
            'GameGenre' => $categorys,
            'GameList' => $gameList,
            'GroupGame' => $groupGameList,
            'GamesListVer' => $this->cIns->sysComns->get(SYSTEM_KEY_GAMELIST_VER),
        );
        return $data;
    }
}
