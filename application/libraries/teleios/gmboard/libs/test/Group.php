<?php
namespace teleios\gmboard\libs\test;

use teleios\gmboard\dao\Bean;
use teleios\gmboard\dao\Groups;
use teleios\gmboard\dao\GroupBoard;
use teleios\gmboard\dao\GroupNotices;
use teleios\gmboard\dao\GameInfos;

/**
 * テスト環境向ゲームグループ関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class Group
{
    private $daoGroups = null;

    public function __construct()
    {
        $this->daoGroups = new Groups();
    }

    /**
     * ゲーム一覧を取得
     * @return [type] [description]
     */
    public function formGroup()
    {
        // ゲームリスト取得
        $daoGameInfos =  new GameInfos();
        return $daoGameInfos->getAll();
    }
    /**
     * グループ追加
     * @param  int    $gameId      [description]
     * @param  string $groupName   [description]
     * @param  string $description [description]
     * @return array               [description]
     */
    public function addGroup(int $gameId, string $groupName, string $description) : array
    {
        // グループ登録
        $data = array(
            'GroupName'     => $groupName,      // グループ名
            'Leader'        => SYSTEM_USER_ID,  // リーダーのユーザID
            'Description'   => $description     // 説明
        );
        // リータ追加
        $newGroupId = $this->daoGroups->add($gameId, $data);
        // グループ掲示板作成
        $daoGroupBoard = new GroupBoard();
        $daoGroupBoard->createTable($gameId, $newGroupId);
        // グループ告知枠作成
        $daoGroupNotices = new GroupNotices();
        $daoGroupNotices->createTable($gameId, $newGroupId);
        // グループ情報取得
        $result = $this->daoGroups->getByGroupId($gameId, $newGroupId);
        if (count($result) == 0) {
            return array();
        }
        return $result[0];
    }

    /**
     * 指定したゲームのグループ一覧を取得
     * @param  int   $gameId [description]
     * @return array         [description]
     */
    public function listGroup(int $gameId) : array
    {
        $groups = $this->daoGroups->getAll($gameId);
        return $groups;
    }

    /**
     * 指定したゲームの特定のグループ情報を取得
     * @param  int   $gameId  [description]
     * @param  int   $groupId [description]
     * @return array          [description]
     */
    public function showGroup(int $gameId, int $groupId) : Bean
    {
        $group = $this->daoGroups->getByGroupId($gameId, $groupId);
        if ($group->isEmpty()) {
            return array();
        }
        $daoGameInfos = new GameInfos();
        $gameInfo = $daoGameInfos->getByGameId($gameId);
        if ($gameInfo->isEmpty()) {
            $group['GameName'] = '未登録ゲーム';
            return $group;
        }
        $group->GameName = $gameInfo->Name;
        return $group;
    }
}
