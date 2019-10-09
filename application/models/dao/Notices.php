<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 全体告知管理テーブル操作クラス
 */
class Notices extends MY_Model
{
    const TABLE_NAME = 'Notices';

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
    public function addNotice(array $data) : int
    {
        if (count($data) > 0) {
            return $this->add(self::TABLE_NAME, $data);
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
        $cond = array(
            'WHERE' => array(
                'Showable' => 1,
                'ShowStartDateTime >=' => $now,
                'ShowEndDateTime <' => $now
            ),
            'LIMIT' => array($number, $offset)
        );
        return $this->get(self::TABLE_NAME, $cond);
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
            return $this->update(self::TABLE_NAME, $data, array('NoticeId' => $noticeId));
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
    public function deleteNotice(int $noticeId) : bool
    {
        return $this->delete(self::TABLE_NAME, array('NoticeId' => $noticeId));
    }
}
