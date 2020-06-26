<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ユーザ情報管理テーブル操作クラス
 */
class PlayerIndex extends MY_Model
{
    const TABLE_NAME = 'PlayerIndex';

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
    }

    /**
     * レコード追加する
     * @param  array $data [description]
     * @return int         [description]
     */
    public function add(int $userId, int $gameId) : int
    {
        return $this->attach(['UserId' => $userId, 'GameId' => $gameId]);
    }

    public function getAll(int $limit = 20, int $offset = 0) : array
    {
        return $this->searchAll($limit, $offset);
    }

    /**
     * ユーザIDを使用して検索
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function getByUserId(int $userId) : array
    {
        $cond = array(
            'WHERE' => array('UserId' => $userId)
        );
        return $this->search($cond);
    }

    /**
     * レコード論理削除
     * @param  int  $playerIndexId [description]
     * @return bool         [description]
     */
    public function delete(int $playerIndexId) : bool
    {
        return $this->logicalDelete(array('PlayerIndexId' => $playerIndexId));
    }

}
