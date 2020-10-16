<?php
namespace teleios\gmboard\libs\common;

use teleios\gmboard\dao\GamePlayers;
use teleios\gmboard\dao\Groups;
use teleios\gmboard\dao\GroupNotices;
use teleios\gmboard\dao\GroupBoard;
use teleios\utils\LangUtils;

/**
 * ゲーム関連基本クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class Game extends GmbCommon
{
    public $gameId;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ゲーム内告知取得
     * @param  integer $page   [description]
     * @param  integer $number [description]
     * @param  string  $order  [description]
     * @return array           [description]
     */
    public function getGameNotices(int $page = 0, int $number = 20, string $order = "DESC") : array
    {
        $offset = $page * $number;
        $daoGroupNotices = new GroupNotices();
        $messages = $daoGroupNotices->get($this->gameId, 0, $order, $number, $offset);
        return $messages;
    }

    /**
     * ゲーム内告知取得
     * @param  integer $page   [description]
     * @param  integer $number [description]
     * @param  string  $order  [description]
     * @return array           [description]
     */
    public function getGameMessage(string $gameName, int $page = 0, int $number = 20, string $order = "DESC") : array
    {
        $offset = $page * $number;
        $daoGroupBoard = new GroupBoard();
        $messages = $daoGroupBoard->get($this->gameId, 0, $number, $offset, $order);
        $count = count($messages);
        if ($count > 0) {
            $langUtils = new LangUtils("japanese", LangUtils::MODE_REDIS);
            for ($i = 0; $i < $count; $i++) {
                if ($messages[$i]->Idiom > 0) {
                    $messages[$i]->Message = $langUtils->getMessageByCode($messages[$i]->Idiom, [$gameName]);
                }
            }
        }
        return $messages;
    }

    /**
     * グループリストの取得
     * @param  int     $page   [description]
     * @param  integer $number [description]
     * @return array           [description]
     */
    public function getGroupList(int $page, int $number = 20) : array
    {
        $offset = $page * $number;
        $daoGroup = new Groups();
        // リスト取得
        $tempGroupList = $daoGroup->getByGameId($this->gameId, $number, $offset);
        $groupNumber = $daoGroup->countGroupInGame($this->gameId);
        // リーダー情報取得
        $daoGamePlayers = new GamePlayers();
        $groupList = array();
        foreach ($tempGroupList as $group) {
            $leader = $daoGamePlayers->getByUserIdInGroup($this->gameId, $group->GroupId, $group->Leader);
            $group->LeaderName = $leader->GameNickname;
            $groupList[] = $group;
        }
        return ['GroupList' => $groupList, 'GroupNumber' => $groupNumber];
    }
}
