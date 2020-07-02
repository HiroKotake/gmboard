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
        $this->calledClass = __CLASS__;
    }

    /**
     * レコード追加する
     * @param  array $data [description]
     * @return int         [description]
     */
    public function add(int $userId, int $gameId) : int
    {
        $this->calledMethod == __FUNCTION__;
        return $this->attach(['UserId' => $userId, 'GameId' => $gameId]);
    }

    public function getAll(int $limit = 20, int $offset = 0) : array
    {
        $this->calledMethod == __FUNCTION__;
        return $this->searchAll($limit, $offset);
    }

    /**
     * ユーザIDを使用して検索
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function getByUserId(int $userId) : array
    {
        $this->calledMethod == __FUNCTION__;
        $cond = array(
            'WHERE' => array('UserId' => $userId)
        );
        return $this->search($cond);
    }

    /**
     * ユーザIDとゲームIDを指定して、対象のレコードが存在するか確認する
     * @param  int  $userId ユーザID
     * @param  int  $gameId ゲームID
     * @return bool         レコードが存在する場合は真を、存在しない場合は偽を返す
     */
    public function isExist(int $userId, int $gameId) : bool
    {
        $this->calledMethod == __FUNCTION__;
        return $this->isExisted(array('UserId' => $userId, 'GameId' => $gameId));
    }

    /**
     * レコード論理削除
     * @param  int  $playerIndexId [description]
     * @return bool         [description]
     */
    public function delete(int $playerIndexId) : bool
    {
        $this->calledMethod == __FUNCTION__;
        return $this->logicalDelete(array('PlayerIndexId' => $playerIndexId));
    }

}
