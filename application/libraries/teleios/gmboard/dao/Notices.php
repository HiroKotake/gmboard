<?php
namespace teleios\gmboard\dao;

/**
 * 全体告知管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class Notices extends \MY_Model
{
    const TABLE_NAME = TABLE_NAME_NOTICES;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->calledClass = __CLASS__;
    }

    /**
     * 告知追加
     * @param  array $data 表示内容
     *                      'Priority' => [優先度]<br />
     *                      'Target' => [表示ターゲット(0:全体告知, 1:メンバー告知, 10:グループ管理者向け告知)]<br />
     *                      'Message' => [メッセージテキスト]<br />
     *                      'ShowStartDateTime' => [表示開始日時]<br />
     *                      'ShowEndDateTime' => [表示終了日時]<br />
     * @return int         正常にレコード追加ができた場合は'NoticeId'である整数値を返し、失敗した場合は'0'を返す
     */
    public function add(array $data) : int
    {
        $this->calledMethod = __FUNCTION__;
        if (count($data) > 0) {
            return $this->attach($data);
        }
        return false;
    }

    /**
     * 告知取得
     * @param  integer $target 表示ターゲット(0:全体告知, 1:メンバー告知, 10:グループ管理者向け告知)
     * @param  integer $number 取得するレコード数
     * @param  integer $offset オフセット値
     * @return array           取得したレコードを配列で返す
     */
    public function get(int $target = 0, int $number = 10, int $offset = 0) : array
    {
        $this->calledMethod = __FUNCTION__;
        $now = date("Y-m-d H:i:s");
        $cond = array(
            'WHERE' => array(
                'Showable' => 1,
                'Target' => $target,
                'ShowStartDateTime >=' => $now,
                'ShowEndDateTime <' => $now
            ),
            'ORDER_BY' => array(
                'ShowStartDateTime' => 'DESC',
                'Priority' => 'ASC'
            ),
            'LIMIT' => array($number, $offset)
        );
        return $this->search($cond);
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @return array [description]
     */
    public function getAllRecords() : array
    {
        $this->calledMethod = __FUNCTION__;
        return $this->searchAll(0, 0, true);
    }

    /**
     * 指定された告知を変更する
     * @param  int  $noticeId 告知管理ID
     * @param  int  $data     変更内容を含む連想配列
     * @return bool           [description]
     */
    public function set(int $noticeId, int $data) : bool
    {
        $this->calledMethod = __FUNCTION__;
        if (count($data) > 0) {
            return $this->update($data, array('NoticeId' => $noticeId));
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
        $this->calledMethod = __FUNCTION__;
        $data = array('Showable' => 1);
        return $this->set($noticeId, $data);
    }

    /**
     * 指定された告知を非表示にする
     * @param  int  $noticeId 告知管理ID
     * @return bool             [description]
     */
    public function hideNotice(int $noticeId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $data = array('Showable' => 0);
        return $this->set($noticeId, $data);
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
        $this->calledMethod = __FUNCTION__;
        $data = array(
            'ShowStartDateTime' => $startDateTime,
            'ShowEndDateTime' => $endDateTime
        );
        return $this->set($noticeId, $data);
    }

    /**
     * 告知を論理削除する
     * @param  int  $noticeId 告知管理ID
     * @return bool           [description]
     */
    public function delete(int $noticeId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        return $this->logicalDelete(array('NoticeId' => $noticeId));
    }

    /**
     * テーブルを初期化する
     * @return bool [description]
     */
    public function clearTable() : bool
    {
        $this->calledMethod = __FUNCTION__;
        return $this->truncate();
    }
}
