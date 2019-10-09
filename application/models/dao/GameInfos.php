<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ゲーム情報テーブル管理テーブル
 */
class GameInfos extends MY_Model
{
    const TABLE_NAME = 'GameInfos';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ゲーム情報を追加する
     * @param string $name        ゲーム名
     * @param string $description ゲーム説明
     */
    public function addGameInfo(string $name, string $description)
    {
        $data = array(
            'Name'          => $name,
            'Description'   => $description
        );
        // ゲームレコード追加
        return $this->add(self::TABLE_NAME, $data);
    }

    /**
     * 条件を指定せず、ゲーム情報を取得する
     * @param  integer $limit  取得するレコード数
     * @param  integer $offset 開始位置
     * @return array           対象のレコード情報を含む連想配列
     */
    public function getAllGameInfos(int $limit = 20 , int $offset = 0) : array
    {
        return $this->getAll(self::TABLE_NAME, $limit, $offset);
    }

    /**
     * 指定した名称を含むゲーム情報を取得する
     * @param  string $name ゲーム名
     * @return [type]       [description]
     */
    public function getLikeName(string $name, $limit = 10, $offset = 0) : array
    {
        $cond = array(
            'LIKE' => array('Name' => $name),
            'NUMBER' => array($limit, $offset)
        );
        return $this->get(self::TABLE_NAME, $cond);
    }

    /**
     * ゲームIDを元にゲーム情報を取得する
     * @param  int   $gameId ゲームID
     * @return array         対象が存在した場合にはゲーム情報を含む連想を配列を返し、ない場合には空の配列を返す
     */
    public function getByGameId(int $gameId) : array
    {
        $cond = array(
            'WHERE' => array('GameId' => $gameId),
        );
        $result = $this->get(self::TABLE_NAME, $cond);
        if (count($result) > 0) {
            return $result[0];
        }
        return array();
    }

    /**
     * ゲーム情報を更新する
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function updateGameInfo(int $gameId, array $data)
    {
        return $this->update(self::TABLE_NAME, $data, array('GamdId' => $gameId));
    }

    /**
     * ゲーム情報を論理削除する
     * @param  int    $gameId ゲーム情報管理ID
     * @return [type]         [description]
     */
    public function deleteGame(int $gameId)
    {
        return $this->delete(self::TABLE_NAME, array('GamdId' => $gameId));
    }
}
