<?php
namespace teleios\gmboard\dao;

/**
 * ユーザ情報管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class PlayerIndex extends \MY_Model
{
    const TABLE_NAME = TABLE_NAME_PLAYER_INDEX;

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

    /**
     * 指定した範囲でのプレイヤーとゲームのリンク情報を取得する
     * @param  integer $limit  [description]
     * @param  integer $offset [description]
     * @return array           [description]
     */
    public function getAll(int $limit = 20, int $offset = 0) : array
    {
        $this->calledMethod == __FUNCTION__;
        return $this->searchAll($limit, $offset);
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @return array [description]
     */
    public function getAllRecords() : array
    {
        $this->calledMethod == __FUNCTION__;
        return $this->searchAll(0, 0, true);
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
        return $this->isExisted(array('WHERE' => ['UserId' => $userId, 'GameId' => $gameId]));
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

    /**
     * テーブルを初期化する
     * @return bool [description]
     */
    public function clearTable() : bool
    {
        $this->calledMethod == __FUNCTION__;
        return $this->truncate();
    }
}
