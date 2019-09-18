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
     * レコードを全て取得する
     * @return [type] [description]
     */
    public function getAll()
    {
        $this->db->where('DeleteFlag', 0);
        $query = $this->getQuerySelect(self::TABLE_NAME);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * 指定した名称を含むゲーム情報を取得する
     * @param  string $name ゲーム名
     * @return [type]       [description]
     */
    public function getLikeName(string $name)
    {
        $this->db->like('Name', $name);
        $this->db->where('DeleteFlag', 0);
        $query = $this->getQuerySelect(self::TABLE_NAME);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * ゲームIDを元にゲーム情報を取得する
     * @param  int   $gameId ゲームID
     * @return array         対象が存在した場合にはゲーム情報を含む連想を配列を返し、ない場合には空の配列を返す
     */
    public function getByGameId(int $gameId) : array
    {
        $this->db->where('GameId', $gameId);
        $query = $this->getQuerySelect(self::TABLE_NAME);
        $resultSet = $this->db->query($query);
        $gameInfos = $resultSet->result_array();
        if (count($gameInfos) == 0) {
            return array();
        }
        return $gameInfos[0];
    }

    /**
     * ゲーム情報を追加する
     * @param string $name        ゲーム名
     * @param string $description ゲーム説明
     */
    public function addGameInfo(string $name, string $description)
    {
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'Name'          => $name,
            'Description'   => $description,
            'CreateDate'    => $datetime
        );
        $query = $this->getQueryInsert(self::TABLE_NAME, $data);
        $this->db->query($query);
        return $this->db->insert_id();
    }

    /**
     * ゲーム情報を更新する
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function updateGameInfo(array $data)
    {
        $datetime = date("Y-m-d H:i:s");
        $updateData = array();
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $updateData[$key] = $value;
                }
            }
            $updateData['UpdateDate'] = $datetime;
            $query = $this->getQueryUpdate(self::TABLE_NAME, $updateData);
            return $this->db->query($query);
        }
        return false;
    }

    /**
     * ゲーム情報を論理削除する
     * @param  int    $gameId ゲーム情報管理ID
     * @return [type]         [description]
     */
    public function deleteGameInfo(int $gameId)
    {
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'DeleteDate'    => $datetime,
            'DeleteFlag'    => 1
        );
        $this->db->where('GameId', $gameId);
        $query = $this->getQueryUpdate(self::TABLE_NAME, $data);
        return $this->db->query($query);
    }
}
