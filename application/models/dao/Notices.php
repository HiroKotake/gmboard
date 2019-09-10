<?php
namespace gmboard\application\models\dao;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 全体告知管理テーブル操作クラス
 */
class Notices extends CI_Model
{
    private $tableName = 'Notices';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 告知追加
     * @param  array $data 表示内容
     *                      'Priority' => [優先度]
     *                      'Message' => [メッセージテキスト]
     *                      'ShowStartDateTime' => [表示開始日時]
     *                      'ShowEndDateTime' => [表示終了日時]
     * @return int         正常にレコード追加ができた場合は'NoticeId'である整数値を返し、失敗した場合は'0'を返す
     */
    public function setNotice(array $data) : int
    {
        if (count($data) > 0) {
            $data['CreateDate'] = date("Y-m-d H:i:s");
            $this->db->insert($this->tableName, $data);
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * 告知取得
     * @param  integer $number 取得するレコード数
     * @param  integer $offset オフセット値
     * @return array           取得したレコードを配列で返す
     */
    public function getNotices(int $number = 10, int $offset = 0) : array
    {
        $now = date("Y-m-d H:i:s");
        $this->db->where('Showable', 1);
        $this->db->where('DeleteFlag', 0);
        $this->db->where('ShowStartDateTime >=', $now);
        $this->db->where('ShowEndDateTime <', $now);
        $resultSet = $this->db->get($this->tableName, $number, $offset);
        return $resultSet->result_array();
    }

    // 更新
    /**
     * 指定された告知を変更する
     * @param  int  $noticeId 告知管理ID
     * @param  int  $data     変更内容を含む連想配列
     * @return bool           [description]
     */
    public function updateNotice(int $noticeId, int $data) : bool
    {
        if (count($data) > 0) {
            $data['UpdateDate'] = date("Y-m-d H:i:s");
            $this->db->where('NoticeId', $noticeId);
            return $this->db->update($this->tableName, $data);
        }
        return false;
    }

    /**
     * 指定された告知を表示にする
     * @param  int  $noticeId 告知管理ID
     * @return bool             [description]
     */
    public function showNotice(int $noticeId) : bool
    {
        $data = array('Showable' => 1);
        return $this->updateNotice($noticeId, $data);
    }

    /**
     * 指定された告知を非表示にする
     * @param  int  $noticeId 告知管理ID
     * @return bool             [description]
     */
    public function hideNotice(int $noticeId) : bool
    {
        $data = array('Showable' => 0);
        return $this->updateNotice($noticeId, $data);
    }

    /**
     * 告知の表示期間を変更する
     * @param  int    $noticeId      告知管理ID
     * @param  string $startDateTime 表示開始日時
     * @param  string $endDateTime   表示終了日時
     * @return bool                  [description]
     */
    public function updateShowTerm(int $noticeId, string $startDateTime, string $endDateTime) : bool
    {
        $data = array(
            'ShowStartDateTime' => $startDateTime,
            'ShowEndDateTime' => $endDateTime
        );
        return $this->updateNotice($noticeId, $data);
    }

    /**
     * 告知を論理削除する
     * @param  int  $noticeId 告知管理ID
     * @return bool           [description]
     */
    public function delete(int $noticeId) : bool
    {
        $data = array(
            'DeleteDate' => date("Y-m-d H:i:s"),
            'DeleteFlag' => 1
        );
        return $this->db->update($noticeId, $data);
    }
}
