<?php
namespace gmboard\application\models\dao;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ゲーム情報テーブル管理テーブル
 */
class GameInfos extends CI_Model
{
    private $tableName = 'GameInfos';

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
        $resultSet = $this->db->get(self::$tableName);
        return $resultSet->result();
    }

    /**
     * 指定した名称を含むゲーム情報を取得する
     * @param  string $name ゲーム名
     * @return [type]       [description]
     */
    public function getLikeName(string $name)
    {
        $this->db->like('Name', $name);
        $resultSet = $this->db->get(self::$tableName);
        return $resultSet->result();
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
        $this->db->insert(self::$tableName, $data);
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
            return $this->db->update(self::$tableName, $updateData);
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
        return $this->db->update(self::$tableName, $data);
    }
}
