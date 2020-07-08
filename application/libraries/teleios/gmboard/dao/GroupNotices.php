<?php
namespace teleios\gmboard\dao;

use teleios\utils\StringUtility;

/**
 * グループ内告知管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class GroupNotices extends \MY_Model
{
    CONST TABLE_PREFIX = TABLE_PREFIX_GROUP_NOTICE;
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
        $this->calledClass = __CLASS__;
    }

    /**
     * テーブル作成
     * @param  int  $gameId  ゲーム管理ID
     * @param  int  $groupId グループ管理ID
     * @return bool          [description]
     */
    public function createTable(int $gameId, int $groupId) : bool
    {
        $this->calledMethod == __FUNCTION__;
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
     *                          'Priority' => [優先度]<br />
     *                          'Message' => [メッセージテキスト]<br />
     *                          'ShowStartDateTime' => [表示開始日時]<br />
     *                          'ShowEndDateTime' => [表示終了日時]<br />
     * @return int            正常にレコード追加ができた場合は'NoticeId'である整数値を返し、失敗した場合は'0'を返す
     */
    public function add(int $gameId, int $groupId, array $data) : int
    {
        $this->calledMethod == __FUNCTION__;
        if (count($data) > 0) {
            $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                        . '_' . str_pad($groupId, 12, "0", STR_PAD_LEFT);
            return $this->attach($data);
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
    public function get(
        int $gameId,
        int $groupId,
        string $order = 'DESC',
        int $number = 10,
        int $offset = 0
    ) : array {
        $this->calledMethod == __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
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
        return $this->search($cond);
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @param  int     $gameId ゲーム管理ID
     * @param  int     $groupId    グループ管理ID
     * @return array [description]
     */
    public function getAllRecords(int $gameId, int $groupId) : array
    {
        $this->calledMethod == __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        return $this->searchAll(0, 0, true);
    }

    /**
     * 指定された告知を変更する
     * @param  int  $gameId   ゲーム管理ID
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @param  int  $data     変更内容を含む連想配列
     * @return bool           [description]
     */
    public function set(int $gameId, int $groupId, int $noticeId, int $data) : bool
    {
        $this->calledMethod == __FUNCTION__;
        if (count($data) > 0) {
            $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                        . '_' . str_pad($groupId, 12, "0", STR_PAD_LEFT);
            return $this->update($data, array('NoticeId' => $noticeId));
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
        $this->calledMethod == __FUNCTION__;
        $data = array('Showable' => 1);
        return $this->set($gameId, $groupId, $noticeId, $data);
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
        $this->calledMethod == __FUNCTION__;
        $data = array('Showable' => 0);
        return $this->set($gameId, $groupId, $noticeId, $data);
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
        $this->calledMethod == __FUNCTION__;
        $data = array(
            'ShowStartDateTime' => $startDateTime,
            'ShowEndDateTime' => $endDateTime
        );
        return $this->set($gameId, $groupId, $noticeId, $data);
    }

    /**
     * 告知を論理削除する
     * @param  int  $gameId   ゲーム管理ID
     * @param  int  $groupId  グループ管理ID
     * @param  int  $noticeId 告知管理ID
     * @return bool           [description]
     */
    public function delete(int $gameId, int $groupId, int $noticeId) : bool
    {
        $this->calledMethod == __FUNCTION__;
        $data = array(
            'DeleteDate' => date("Y-m-d H:i:s"),
            'DeleteFlag' => 1
        );
        return $this->db->set($gameId, $groupId, $noticeId, $data);
    }

    /**
     * 対象のテーブルを初期化する
     * @param  int  $gameId   ゲーム管理ID
     * @param  int  $groupId  グループ管理ID
     * @return bool         [description]
     */
    public function clearTable(int $gameId, int $groupId) : bool
    {
        $this->calledMethod == __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                    . '_' . str_pad($groupId, 12, "0", STR_PAD_LEFT);
        return $this->truncate();
    }
}