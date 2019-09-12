<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ゲームプレイヤー管理テーブル操作クラス
 */
class GamePlayers extends MY_Model
{
    const TABLE_NAME = 'GamePlayers';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 特定のユーザに関わるレコードを取得
     * @param  int    $userId [description]
     * @return [type]         [description]
     */
    public function getByUserId(int $userId)
    {
        $this->db->where('UserId', $userId);
        $this->db->where('DeleteFlag', 0);
        $query = $this->getQuerySelect(self::TABLE_NAME);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * 特定のグループに属するレコードを取得
     * @param  int    $groupId [description]
     * @return [type]          [description]
     */
    public function getByGroupId(int $groupId)
    {
        $this->db->where('GroupId', $groupId);
        $this->db->where('DeleteFlag', 0);
        $query = $this->getQuerySelect(self::TABLE_NAME);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * 特定のゲーム側のID持つレコードを取得
     * @param  int    $playerId [description]
     * @return [type]           [description]
     */
    public function getByPlayerId(int $playerId)
    {
        $this->db->where('PlayerId', $playerId);
        $this->db->where('DeleteFlag', 0);
        $query = $this->getQuerySelect(self::TABLE_NAME);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * 特定のゲーム側のニックネームを持つレコードを取得
     * @param  string $nickname [description]
     * @return [type]           [description]
     */
    public function getLikeNickname(string $nickname)
    {
        $this->db->like('Nickname', $nickname);
        $this->db->where('DeleteFlag', 0);
        $query = $this->getQuerySelect(self::TABLE_NAME);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * レコードを追加する
     * @param array $data 'UserId','GameId','PlayerId','GameNickname','GroupId','Authority'をキー名としたデータを持つ配列
     */
    public function addNewGamePlayer(array $data)
    {
        $datetime = date("Y-m-d H:i:s");
        $data['CreateDate'] = $datetime;
        $query = $this->getQueryInsert(self::TABLE_NAME, $data);
        $this->db->query($query);
        return $this->db->insert_id();
    }

    /**
     * 特定のゲームプレイヤーIDのレコードを更新する
     * @param  int    $gamePlayerId [description]
     * @param  array  $data         [description]
     * @return [type]               [description]
     */
    public function updateByGamePlayerId(int $gamePlayerId, array $data)
    {
        $datetime = date("Y-m-d H:i:s");
        $updateData = array(
            'UpdateDate'    => $datetime
        );
        foreach ($data as $key => $value) {
            $updateData[$key] = $value;
        }
        $this->db->where('GamePlayerId', $gamePlayerId);
        $query = $this->getQueryUpdate(self::TABLE_NAME, $updateData);
        return $this->db->query($query);
    }

    /**
     * 特定のユーザに関わるレコードを論理削除する
     * @param  int    $userId [description]
     * @return [type]         [description]
     */
    public function deleteByUserId(int $userId)
    {
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'DeleteDate' => $datetime,
            'DeleteFlag' => 1
        );
        $this->db->where('UserId', $userId);
        $query = $this->getQueryUpdate(self::TABLE_NAME, $data);
        return $this->db->query($query);
    }

    /**
     * 特定のゲームプレイヤーIDのレコードを論理削除する
     * @param  int    $playerId [description]
     * @return [type]           [description]
     */
    public function deleteByGamePlayerId(int $playerId)
    {
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'DeleteDate' => $datetime,
            'DeleteFlag' => 1
        );
        $this->db->where('PlayerId', $playerId);
        $query = $this->getQueryUpdate(self::TABLE_NAME, $data);
        return $this->db->query($query);
    }
}
