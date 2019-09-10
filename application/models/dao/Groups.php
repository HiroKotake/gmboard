<?php
namespace gmboard\application\models\dao;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * グループ情報管理テーブル操作クラス
 */
class Groups extends CI_Model
{
    private $tableName = 'Groups';

    public function __construct()
    {
        parent::__construct();
    }

    // 追加
    public function addGroup(array $data) : int
    {
        if (count($data) > 0) {
            $data['CreateDate'] = date("Y-m-d H:i:s");
            $this->db->insert($this->tableName, $data);
            return $this->db->insert_id();
        }
        return false;
    }
    // 全検索
    /**
     * 全レコード取得
     * @return arrey [description]
     */
    public function getAll() : arrey
    {
        $resultSet = $this->db->get($this->tableName);
        return $resultSet->result_array();
    }

    /**
     * ゲーム管理IDによる検索
     * @param  int     $gameId      ゲーム管理ID
     * @param  boolean $deleted     削除したレコードも対象に含む場合に true をセット(default: false)
     * @return array                [description]
     */
    public function getByGameId(int $gameId, bool $deleted = false) : array
    {
        $this->db->where('GameId', $gameId);
        $this->db->where('DeleteFlag', ($deleted == true ? 1 : 0));
        $resultSet = $this->db->get($this->tableName);
        return $resultSet->result_array();
    }

    // グループ名検索
    public function getByGroupName(string $groupName, bool $deleted = false) : array
    {
        $this->db->like('GroupName', $groupName);
        $this->db->where('DeleteFlag', ($deleted == true ? 1 : 0));
        $resultSet = $this->db->get($this->tableName);
        return $resultSet->result_array();
    }

    /**
     * レコードの内容を更新する
     * @param  int   $groupId グループ管理ID
     * @param  array $data    変更する内容を含んだいフィールド名をキーとした連想配列
     * @return bool           [description]
     */
    public function update(int $groupId, array $data) : bool
    {
        if (count($data) > 0) {
            $data['UpdateDate'] = date("Y-m-d H:i:s");
            $this->db->where('GroupId', $groupId);
            return $this->db->update($this->tableName, $data);
        }
        return false;
    }

    /**
     * グループ名変更
     * @param  int    $groupId グループ管理ID
     * @param  string $groupName グループ名
     * @return bool              [description]
     */
    public function updateToGroupName(int $groupId, string $groupName) : bool
    {
        $data = array('GroupName' => $groupName);
        return $this->update($groupId, $data);
    }

    //
    /**
     * リーダー変更
     * @param  int  $groupId グループ管理ID
     * @param  int  $userId  真リーダーのユーザID
     * @return bool          [description]
     */
    public function updateToLeader(int $groupId, int $userId) : bool
    {
        $data = array('Leader' => $userId);
        return $this->update($groupId, $data);
    }

    /**
     * 説明文変更
     * @param  int    $groupId グループ管理ID
     * @param  string $description 説明文
     * @return bool                [description]
     */
    public function updateToDescription(int $groupId, string $description) : bool
    {
        $data = array('Description' => $description);
        return $this->update($groupId, $data);
    }

    /**
     * レコードを論理削除する
     * @param  int  $groupId グループ管理ID
     * @return bool          [description]
     */
    public function delete(int $groupId) : bool
    {
        $data = array(
            'DeleteDate' => date("Y-m-d H:i:s"),
            'DeleteFlag' => 1
        );
        return $this->update($groupId, $data);
    }
}
