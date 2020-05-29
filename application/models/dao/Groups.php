<?php

use teleios\utils\StringUtility;

/**
 * グループ情報管理テーブル操作クラス
 */
class Groups extends MY_Model
{
    const TABLE_PREFIX = 'Groups_';
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
    }

    /**
     * ゲーム別グループ管理テーブル作成
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @return [type]          [description]
     */
    public function createTable(int $gameId) : bool
    {
        $query = 'CALL CreateGroup(' . $gameId . ')';
        $this->writeLog($query);
        return $this->db->simple_query($query);
    }

    public function getAll(int $gameId) : array
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->searchAll();
    }

    /**
     * グループを追加
     * @param  int   $gameId ゲーム管理ID
     * @param  array $data   [description]
     * @return int           [description]
     */
    public function add(int $gameId, array $data) : int
    {
        if (count($data) > 0) {
            $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
            return $this->attach($data);
        }
        return false;
    }

    // グループ名検索
    /**
     * グループ名検索
     * @param  int     $gameId    ゲーム管理ID
     * @param  string  $groupName [description]
     * @param  integer $limit     [description]
     * @param  integer $offset    [description]
     * @return array              [description]
     */
    public function getByGroupName(int $gameId, string $groupName, int $limit = 10, int $offset = 0) : array
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        $cond = array(
            'LIKE' => array('GroupName' => $groupName),
            'LIMIT' => array($limit, $offset),
            'ORDER_BY' => array('GroupId' => 'ASC')
        );
        return $this->search($cond);
    }

    // グループID検索
    /**
     * グループID検索
     * @param  int   $gameId  ゲーム管理ID
     * @param  int   $groupId [description]
     * @return array          [description]
     */
    public function getByGroupId(int $gameId, int $groupId) : array
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        $cond = array(
            'WHERE' => array('GroupId' => $groupId)
        );
        $result = $this->search($cond);
        if (count($result) == 0) {
            return array();
        }
        return $result[0];
    }

    /**
     * レコードの内容を更新する
     * @param  int   $gameId  ゲーム管理ID
     * @param  int   $groupId グループ管理ID
     * @param  array $data    変更する内容を含んだいフィールド名をキーとした連想配列
     * @return bool           [description]
     */
    public function set(int $gameId, int $groupId, array $data) : bool
    {
        if (count($data) > 0) {
            $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
            return $this->update($data, array('GroupId' => $groupId));
        }
        return false;
    }

    /**
     * グループ名変更
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @param  string $groupName グループ名
     * @return bool              [description]
     */
    public function updateGroupName(int $gameId, int $groupId, string $groupName) : bool
    {
        $data = array('GroupName' => $groupName);
        return $this->set($gameId, $groupId, $data);
    }

    /**
     * リーダー変更
     * @param  int  $gameId  ゲーム管理ID
     * @param  int  $groupId グループ管理ID
     * @param  int  $userId  真リーダーのユーザID
     * @return bool          [description]
     */
    public function updateLeader(int $gameId, int $groupId, int $userId) : bool
    {
        $data = array('Leader' => $userId);
        return $this->set($gameId, $groupId, $data);
    }

    /**
     * 説明文変更
     * @param  int  $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @param  string $description 説明文
     * @return bool                [description]
     */
    public function updateDescription(int $gameId, int $groupId, string $description) : bool
    {
        $data = array('Description' => $description);
        return $this->set($gameId, $groupId, $data);
    }

    /**
     * レコードを論理削除する
     * @param  int  $gameId  ゲーム管理ID
     * @param  int  $groupId グループ管理ID
     * @return bool          [description]
     */
    public function delete(int $gameId, int $groupId) : bool
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->logicalDelete(array('GroupId' => $groupId));
    }
}
