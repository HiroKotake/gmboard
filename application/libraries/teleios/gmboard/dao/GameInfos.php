<?php
namespace teleios\gmboard\dao;

use teleios\gmboard\dao\Bean;

/**
 * ゲーム情報テーブル管理テーブル
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class GameInfos extends \MY_Model
{
    const TABLE_NAME = TABLE_NAME_GAME_INFOS;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->idType = ID_TYPE_GAME_INFOS;
        $this->calledClass = __CLASS__;
    }

    /**
     * ゲーム情報を追加する
     * @param string $name        ゲーム名
     * @param string $description ゲーム説明
     */
    public function add(string $name, string $description)
    {
        $this->calledMethod = __FUNCTION__;
        $data = array(
            'Name'          => $name,
            'Description'   => $description
        );
        // ゲームレコード追加
        return $this->attach($data);
    }

    /**
     * 条件を指定せず、ゲーム情報を取得する
     * @param  integer $limit  取得するレコード数
     * @param  integer $offset 開始位置
     * @return array           対象のレコード情報を含む連想配列
     */
    public function getAll(int $limit = 20 , int $offset = 0) : array
    {
        $this->calledMethod = __FUNCTION__;
        return $this->searchAll($limit, $offset);
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @return array [description]
     */
    public function getAllRecords() : array
    {
        $this->calledMethod = __FUNCTION__;
        return $this->searchAll(0, 0, true);
    }

    /**
     * 指定した名称を含むゲーム情報を取得する
     * @param  string $name ゲーム名
     * @return [type]       [description]
     */
    public function getLikeName(string $name, $limit = 10, $offset = 0) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'LIKE' => array('Name' => $name),
            'NUMBER' => array($limit, $offset)
        );
        return $this->search($cond);
    }

    /**
     * ゲームIDを元にゲーム情報を取得する
     * @param  int   $gameId ゲームID
     * @return array         対象が存在した場合にはゲーム情報を含む連想を配列を返し、ない場合には空の配列を返す
     */
    public function getByGameId(int $gameId) : Bean
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array('GameId' => $gameId),
        );
        $result = $this->search($cond);
        return $this->getMonoResult($result);
    }

    /**
     * 複数のゲームIDを元にゲーム情報を取得する
     * @param  array $gameId ゲームID
     * @return array         対象が存在した場合にはゲーム情報を含む連想を配列を返し、ない場合には空の配列を返す
     */
    public function getByGameIds(array $gameIds) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array('GameId' => $gameIds),
        );
        $result = $this->search($cond);
        return $result;
    }

    /**
     * ゲーム情報を更新する
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function set(int $gameId, array $data)
    {
        $this->calledMethod = __FUNCTION__;
        return $this->update($data, array('GamdId' => $gameId));
    }

    /**
     * ゲーム情報を論理削除する
     * @param  int    $gameId ゲーム情報管理ID
     * @return [type]         [description]
     */
    public function delete(int $gameId)
    {
        $this->calledMethod = __FUNCTION__;
        return $this->logicalDelete(array('GamdId' => $gameId));
    }

    /**
     * テーブルを初期化する
     * @return bool [description]
     */
    public function clearTable() : bool
    {
        $this->calledMethod = __FUNCTION__;
        return $this->truncate();
    }
}
