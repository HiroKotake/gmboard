<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * グループ内告知管理テーブル操作クラス
 */
class GroupNotices extends MY_Model
{
    CONST TABLE_PREFIX = 'GNotice_';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * テーブル作成
     * @param  int    $groupId グループ管理ID
     * @return [type]          [description]
     */
    public function createGroupNotice(int $groupId)
    {
        $query = 'CALL CreateGroupNotice(' . $groupId . ')';
        $this->writeLog($query);
        return $this->db->query($query);
    }

    // 追加
    /**
     * 告知を追加する
     * @param  int   $groupId グループ管理ID
     * @param  array $data    表示内容
     *                          'Priority' => [優先度]
     *                          'Message' => [メッセージテキスト]
     *                          'ShowStartDateTime' => [表示開始日時]
     *                          'ShowEndDateTime' => [表示終了日時]
     * @return int            正常にレコード追加ができた場合は'NoticeId'である整数値を返し、失敗した場合は'0'を返す
     */
    public function addNotice(int $groupId, array $data) : int
    {
        if (count($data) > 0) {
            $tableName = TABLE_PREFIX . str_pad($groupId, 12, "0", STR_PAD_LEFT);
            $data['CreateDate'] = date("Y-m-d H:i:s");
            $query = $this->getQueryInsert($tableName, $data);
            $this->db->query($query);
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * 告知取得
     * @param  int     $groupId グループ管理ID
     * @param  integer $number 取得するレコード数
     * @param  integer $offset オフセット値
     * @return array           取得したレコードを配列で返す
     */
    public function getNotices(int $groupId, int $number = 10, int $offset = 0) : array
    {
        $tableName = TABLE_PREFIX . str_pad($groupId, 12, "0", STR_PAD_LEFT);
        $now = date("Y-m-d H:i:s");
        $this->db->where('Showable', 1);
        $this->db->where('DeleteFlag', 0);
        $this->db->where('ShowStartDateTime >=', $now);
        $this->db->where('ShowEndDateTime <', $now);
        $this->db->limit($number, $offset);
        $query = $this->getQuerySelect($tableName);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * 指定された告知を変更する
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @param  int  $data     変更内容を含む連想配列
     * @return bool           [description]
     */
    public function updateNotice(int $groupId, int $noticeId, int $data) : bool
    {
        if (count($data) > 0) {
            $tableName = TABLE_PREFIX . str_pad($groupId, 12, "0", STR_PAD_LEFT);
            $data['UpdateDate'] = date("Y-m-d H:i:s");
            $this->db->where('NoticeId', $noticeId);
            $query = $this->getQueryUpdate($tableName, $data);
            return $this->db->query($query);
        }
        return false;
    }

    /**
     * 指定された告知を表示にする
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @return bool             [description]
     */
    public function showNotice(int $groupId, int $noticeId) : bool
    {
        $data = array('Showable' => 1);
        return $this->updateNotice($groupId, $noticeId, $data);
    }

    /**
     * 指定された告知を非表示にする
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @return bool             [description]
     */
    public function hideNotice(int $groupId, int $noticeId) : bool
    {
        $data = array('Showable' => 0);
        return $this->updateNotice($groupId, $noticeId, $data);
    }

    /**
     * 告知の表示期間を変更する
     * @param  int    $groupId       グループ管理ID
     * @param  int    $noticeId      告知管理ID
     * @param  string $startDateTime 表示開始日時
     * @param  string $endDateTime   表示終了日時
     * @return bool                  [description]
     */
    public function updateShowTerm(int $groupId, int $noticeId, string $startDateTime, string $endDateTime) : bool
    {
        $data = array(
            'ShowStartDateTime' => $startDateTime,
            'ShowEndDateTime' => $endDateTime
        );
        return $this->updateNotice($groupId, $noticeId, $data);
    }

    /**
     * 告知を論理削除する
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @return bool           [description]
     */
    public function delete(int $groupId, int $noticeId) : bool
    {
        $data = array(
            'DeleteDate' => date("Y-m-d H:i:s"),
            'DeleteFlag' => 1
        );
        return $this->db->updateNotice($groupId, $noticeId, $data);
    }
}
