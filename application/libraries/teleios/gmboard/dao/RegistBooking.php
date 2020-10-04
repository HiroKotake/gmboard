<?php
namespace teleios\gmboard\dao;

use teleios\utils\StringUtility;

/**
 * ユーザ登録予約管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class RegistBooking extends \MY_Model
{
    const TABLE_PREFIX = TABLE_PREFIX_REGIST_BOOKING;
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
        $this->idType = ID_TYPE_REGIST_BOOKING;
        $this->calledClass = __CLASS__;
    }

    /**
     * ユーザ登録予約管理テーブル作成
     * @param  int  $gameId ゲーム管理ID
     * @return bool         [description]
     */
    public function createTable(int $gameId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $query = 'CALL CreateRBooking(' . $gameId . ')';
        $this->writeLog($query);
        return $this->db->simple_query($query);
    }

    /**
     * レコード追加
     * @param  int    $gameId   ゲーム管理ID
     * @param  int    $groupId  [description]
     * @param  string $playerId [description]
     * @param  string $nickname [description]
     * @param  string $authCode [description]
     * @return int              [description]
     */
    public function add(
        int $gameId,
        int $groupId,
        string $playerId,
        string $nickname,
        string $authCode
    ) : int {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        $data = array(
            'GroupId'       => $groupId,
            'PlayerId'      => $playerId,
            'GameNickName'  => $nickname,
            'AuthCode'      => $authCode,
        );
        return $this->attach($data);
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @param  int     $gameId ゲーム管理ID
     * @return array [description]
     */
    public function getAllRecords(int $gameId) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->searchAll(0, 0, true);
    }

    /**
     * 様々な条件で検索
     * @param  int   $gameId    ゲーム管理ID
     * @param  array $condition 検索条件を含む連想配列
     * @return array            検索結果を含む配列。該当するレコードがない場合は空の配列を返す
     */
    public function get(int $gameId, array $condition) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->search($condition);
    }

    /**
     * RegistBookingIdで検索
     * @param  int   $gameId          ゲーム管理ID
     * @param  int   $registBookingId [description]
     * @return array                  [description]
     */
    public function getByRegistBookingId(int $gameId, int $registBookingId) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array('RegistBookingId' => $registBookingId)
        );
        $result = $this->get($gameId, $cond);
        if (count($result) == 0) {
            return array();
        }
        return $result[0];
    }

    /**
     * ゲーム別の予約情報を取得する
     * @param  int     $gameId ゲーム管理ID
     * @param  integer $limit  最大取得レコード数
     * @param  integer $offset レコード取得開始位置
     * @return array           [description]
     */
    public function getByGameId(int $gameId, int $limit = 20, int $offset = 0) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'NUMBER' => array($limit, $offset)
        );
        return $this->get($gameId, $cond);
    }

    /**
     * ゲーム側IDで検索
     * @param  int    $gameId   ゲーム管理ID
     * @param  string $playerId [description]
     * @return array            [description]
     */
    public function getByPlayerId(int $gameId, string $playerId) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array('PlayerId' => $playerId)
        );
        return $this->get($gameId, $cond);
    }

    /**
     * ゲーム側ニックネームで検索
     * @param  int    $gameId       ゲーム管理ID
     * @param  string $gameNickname [description]
     * @return array                [description]
     */
    public function getByNickname(int $gameId, string $gameNickname) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array('GameNickname' => $gameNickname)
        );
        return $this->get($gameId, $cond);
    }

    // グループIDで検索
    /**
     * グループIDで検索
     * @param  int   $gameId  ゲーム管理ID
     * @param  int   $groupId [description]
     * @param  int   $limit   [description]
     * @param  int   $offset  [description]
     * @return array          [description]
     */
    public function getByGroupId(int $gameId, int $groupId, int $limit = 20, int $offset = 0) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array('GroupId' => $groupId),
            'LIMIT' => array($limit, $offset)
        );
        return $this->get($gameId, $cond);
    }

    /**
     * グループへの申請者リストを取得する
     * @param  int     $gameId  ゲーム管理ID
     * @param  int     $groupId [description]
     * @param  integer $limit   [description]
     * @param  integer $offset  [description]
     * @return array            [description]
     */
    public function getRequestsByGroupId(int $gameId, int $groupId, int $limit = 20, int $offset = 0) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array(
                'GroupId' => $groupId,
                'Type' => 0
            ),
            'LIMIT' => array($limit, $offset)
        );
        return $this->get($gameId, $cond);
    }

    /**
     * グループへの招待者リストを取得する
     * @param  int     $gameId  ゲーム管理ID
     * @param  int     $groupId [description]
     * @param  integer $limit   [description]
     * @param  integer $offset  [description]
     * @return array            [description]
     */
    public function getInvitesByGroupId(int $gameId, int $groupId, int $limit = 20, int $offset = 0) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array(
                'GroupId' => $groupId,
                'Type' => 1
            ),
            'LIMIT' => array($limit, $offset)
        );
        return $this->get($gameId, $cond);
    }

    /**
     * ユーザIDで検索
     * @param  int   $gameId ゲーム管理ID
     * @param  int   $userId [description]
     * @param  int   $limit  [description]
     * @param  int   $offset [description]
     * @return array         [description]
     */
    public function getByUserId(int $gameId, int $userId, int $limit, int $offset) : array
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array('UserId' => $userId),
            'LIMIT' => array($limit, $offset)
        );
        return $this->get($gameId, $cond);
    }

    /**
     * レコード内容を更新する
     * @param  int   $gameId           ゲーム管理ID
     * @param  int   $registBookingId [description]
     * @param  array $data            [description]
     * @return bool                   [description]
     */
    public function set(int $gameId, int $registBookingId, array $data) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->update($data, array('RegistBookingId' => $registBookingId));
    }

    /**
     * グループ参加予約者が認証コードを打ち、予約を成立させたことを示す登録済みフラグ更新
     * @param  int  $gameId          ゲーム管理ID
     * @param  int  $registBookingId [description]
     * @return bool                  [description]
     */
    public function registed(int $gameId, int $registBookingId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $data = array(
            'Registed'      => 1,
        );
        return $this->set($gameId, $registBookingId, $data);
    }

    /**
     * グループ管理者がグループ参加希望者を承認状態にする
     * (この直後の対応としてGemePlayersテーブルへプレイヤー情報を書き込む)
     * @param  int  $gameId          ゲーム管理ID
     * @param  int  $registBookingId [description]
     * @return bool                  [description]
     */
    public function approve(int $gameId, int $registBookingId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $data = array(
            'Approved'      => 1,
        );
        return $this->set($gameId, $registBookingId, $data);
    }

    /**
     * レコード論理削除
     * @param  int  $gameId          ゲーム管理ID
     * @param  int  $registBookingId [description]
     * @return bool                  [description]
     */
    public function delete(int $gameId, int $registBookingId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->logicalDelete(array('RegistBookingId' => $registBookingId));
    }

    /**
     * 対象のテーブルを初期化する
     * @param  int  $gameId   ゲーム管理ID
     * @return bool         [description]
     */
    public function clearTable(int $gameId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
        return $this->truncate();
    }
}
