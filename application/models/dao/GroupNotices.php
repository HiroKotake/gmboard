<?php

use teleios\utils\StringUtility;

/**
 * グループ内告知管理テーブル操作クラス
 */
class GroupNotices extends MY_Model
{
    CONST TABLE_PREFIX = 'GNotice_';
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
    }

    /**
     * テーブル作成
     * @param  int  $gameId  ゲーム管理ID
     * @param  int  $groupId グループ管理ID
     * @return bool          [description]
     */
    public function createTable(int $gameId, int $groupId) : bool
    {
        $query = 'CALL CreateGroupNotice(' . $gameId . ', ' . $groupId . ')';
        $this->writeLog($query);
        return $this->db->simple_query($query);
    }

    // 追加
    /**
     * 告知を追加する
     * @param  int   $gameId  ゲーム管理ID
     * @param  int   $groupId グループ管理ID
     * @param  array $data    表示内容
     *                          'Priority' => [優先度]
     *                          'Message' => [メッセージテキスト]
     *                          'ShowStartDateTime' => [表示開始日時]
     *                          'ShowEndDateTime' => [表示終了日時]
     * @return int            正常にレコード追加ができた場合は'NoticeId'である整数値を返し、失敗した場合は'0'を返す
     */
    public function addNotice(int $gameId, int $groupId, array $data) : int
    {
        if (count($data) > 0) {
            $tableName = TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                        . '_' . str_pad($groupId, 12, "0", STR_PAD_LEFT);
            return $this->add($tableName, $data);
        }
        return false;
    }

    /**
     * 告知取得
     * @param  int     $gameId  ゲーム管理ID
     * @param  int     $groupId グループ管理ID
     * @param  string  $order   所得するレコードの告知IDを目的とした順序の指定
     * @param  integer $number  取得するレコード数
     * @param  integer $offset  オフセット値
     * @return array            取得したレコードを配列で返す
     */
    public function getNotices(
        int $gameId,
        int $groupId,
        string $order = 'DESC',
        int $number = 10,
        int $offset = 0
    ) : array {
        $tableName = TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                    . '_' . str_pad($groupId, 12, "0", STR_PAD_LEFT);
        $now = date("Y-m-d H:i:s");
        $cond = array(
            'WHERE' => array(
                'Showable' => 1,
                'ShowStartDateTime >=' => $now,
                'ShowEndDateTime <' => $now
            ),
            'ORDER_BY' => array('NoticeId' => $order),
            'LIMIT' => array($number, $offset)
        );
        return $this->get($tableName, $cond);
    }

    /**
     * 指定された告知を変更する
     * @param  int  $gameId   ゲーム管理ID
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @param  int  $data     変更内容を含む連想配列
     * @return bool           [description]
     */
    public function updateNotice(int $gameId, int $groupId, int $noticeId, int $data) : bool
    {
        if (count($data) > 0) {
            $tableName = TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                        . '_' . str_pad($groupId, 12, "0", STR_PAD_LEFT);
            return $this->update($tableName, $data, array('NoticeId' => $noticeId));
        }
        return false;
    }

    /**
     * 指定された告知を表示にする
     * @param  int  $gameId   ゲーム管理ID
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @return bool             [description]
     */
    public function showNotice(int $gameId, int $groupId, int $noticeId) : bool
    {
        $data = array('Showable' => 1);
        return $this->updateNotice($gameId, $groupId, $noticeId, $data);
    }

    /**
     * 指定された告知を非表示にする
     * @param  int  $gameId   ゲーム管理ID
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @return bool             [description]
     */
    public function hideNotice(int $gameId, int $groupId, int $noticeId) : bool
    {
        $data = array('Showable' => 0);
        return $this->updateNotice($gameId, $groupId, $noticeId, $data);
    }

    /**
     * 告知の表示期間を変更する
     * @param  int    $gameId        ゲーム管理ID
     * @param  int    $groupId       グループ管理ID
     * @param  int    $noticeId      告知管理ID
     * @param  string $startDateTime 表示開始日時
     * @param  string $endDateTime   表示終了日時
     * @return bool                  [description]
     */
    public function updateShowTerm(
        int $gameId,
        int $groupId,
        int $noticeId,
        string $startDateTime,
        string $endDateTime
    ) : bool {
        $data = array(
            'ShowStartDateTime' => $startDateTime,
            'ShowEndDateTime' => $endDateTime
        );
        return $this->updateNotice($gameId, $groupId, $noticeId, $data);
    }

    /**
     * 告知を論理削除する
     * @param  int  $gameId   ゲーム管理ID
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @return bool           [description]
     */
    public function deleteGroup(int $gameId, int $groupId, int $noticeId) : bool
    {
        $data = array(
            'DeleteDate' => date("Y-m-d H:i:s"),
            'DeleteFlag' => 1
        );
        return $this->db->updateNotice($gameId, $groupId, $noticeId, $data);
    }
}
