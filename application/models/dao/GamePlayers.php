<?php

use teleios\utils\StringUtility;

/**
 * ゲームプレイヤー管理テーブル操作クラス
 */
class GamePlayers extends MY_Model
{
    const TABLE_PREFIX = 'GamePlayers_';
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
    }

    /**
     * ゲームプレイヤー管理テーブル作成
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @return [type]          [description]
     */
    public function createTable(int $gameId) : bool
    {
        $query = 'CALL CreateGamePlayers(' . $gameId . ')';
        $this->writeLog($query);
        return $this->db->simple_query($query);
    }

    /**
     * ユーザに関わるレコードを全て取得
     * @param  int     $gameId ゲーム管理ID
     * @param  integer $limit  取得するレコード数
     * @param  integer $offset 取得を開始する位置
     * @return array           [description]
     */
    public function getAll(int $gameId, int $limit = 20, int $offset = 0) : array
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->searchAll($limit, $offset);
    }

    /**
     * 特定のユーザに関わるレコードを取得
     * @param  int   $gameId       ゲーム管理ID
     * @param  int   $gamePlayerId [description]
     * @return array               [description]
     */
    public function getByGamePlayerId(int $gameId, int $gamePlayerId) : array
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        $cond = array(
            'WHERE' => array('GamePlayerId' => $gamePlayerId),
        );
        $resultSet = $this->search($cond);
        return $this->getMonoResult($resultSet);
    }

    /**
     * 特定のユーザに関わるレコードを取得
     * @param  int     $gameId ゲーム管理ID
     * @param  int     $userId [description]
     * @param  string  $order  [description]
     * @return array           [description]
     */
    public function getByUserId(int $gameId, int $userId) : array
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        $cond = array(
            'WHERE' => array('UserId' => $userId)
        );
        $resultSet = $this->search($cond);
        return $this->getMonoResult($resultSet);
    }

    /**
     * 特定のグループに属するレコードを取得
     * @param  int     $gameId  ゲーム管理ID
     * @param  int     $groupId グループ管理ID
     * @param  integer $limit   取得レコード数
     * @param  integer $offset  取得開始位置
     * @return array            取得に成功した場合は内容を含む配列を返す。失敗した場合は空の配列を返す
     */
    public function getByGroupId(int $gameId, int $groupId, int $limit = 20, int $offset = 0) : array
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        $cond = array(
            'WHERE' => array('GroupId' => $groupId),
            'NUMBER' => array($limit, $offset)
        );
        return $this->search($cond);
    }

    /**
     * 特定のゲーム側のID持つレコードを取得
     * ：間違えて同じPlayerIdで複数のユーザが登録されている場合がある。
     * @param  int   $gameId    ゲーム管理ID
     * @param  int   $playerId  [description]
     * @return array           [description]
     */
    public function getByPlayerId(int $gameId, int $playerId) : array
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        $cond = array(
            'WHERE' => array(
                'PlayerId' => $playerId
            )
        );
        return $this->search($cond);
    }

    /**
     * 特定のゲーム側のニックネームを持つレコードを取得
     * ：同じニックネームが複数のユーザが登録されている場合がある。
     * @param  int    $gameId   ゲーム管理ID
     * @param  string $nickname [description]
     * @return array            [description]
     */
    public function getLikeNickname(int $gameId, string $nickname) : array
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        $cond = array(
            'LIKE' => array('GameNickname' => $nickname)
        );
        return $this->search($cond);
    }

    /**
     * レコードを追加する
     * @param  int   $gameId ゲーム管理ID
     * @param  array $data   'UserId','PlayerId','GameNickname','GroupId','Authority'をキー名としたデータを持つ配列
     * @return int           [description]
     */
    public function add(int $gameId, array $data) : int
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->attach($data);
    }

    /**
     * 特定のゲームプレイヤーIDのレコードを更新する
     * @param  int    $gameId       ゲーム管理ID
     * @param  int    $gamePlayerId [description]
     * @param  array  $data         [description]
     * @return [type]               [description]
     */
    public function set(int $gameId, int $playerId, array $data) : bool
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->update($data, array('PlayerId' => $playerId));
    }

    /**
     * 特定のユーザに関わるレコードを論理削除する
     * @param  int  $gameId ゲーム管理ID
     * @param  int  $userId [description]
     * @return bool         [description]
     */
    public function deleteByUserId(int $gameId, int $userId) : bool
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->logicalDelete(array('UserId' => $userId));
    }

    /**
     * 特定のゲームプレイヤーIDのレコードを論理削除する
     * @param  int  $gameId   ゲーム管理ID
     * @param  int  $playerId [description]
     * @return bool           [description]
     */
    public function deleteByGamePlayerId(int $gameId, int $playerId) : bool
    {
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->logicalDelete(array('PlayerId' => $playerId));
    }
}
