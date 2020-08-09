<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\libs\common\Group;
use teleios\gmboard\dao\Bean;
use teleios\gmboard\dao\GroupBoard;

/**
 * グループページ関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class GroupPage extends Group
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getGroupMessage(int $page = 0, int $number = 20, string $order = "DESC") : array
    {
        $offset = $page * $number;
        $daoGroupBoard = new GroupBoard();
        return $daoGroupBoard->get($this->gameId, $this->groupId, $number, $offset, $order);
    }

    /**
     * ユーザページで表示するデータを取得する
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function getPageData(int $userId) : array
    {
        // 共通ページデータ取得
        $data = $this->getPageDataCommon($userId);
        // グループ向けメッセージ取得
        $data["Message"] = $this->getGroupMessage();
        return $data;
    }
}
