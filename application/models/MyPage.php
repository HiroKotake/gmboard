<?php

namespace gmboard\application\models;

class MyPage
{

    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& \get_instance();
    }

    public function getMyPageData(int $userId, int $lineNumber = 100, int $page = 0) : array
    {
        // ゲームプレイヤー情報取得
        $cIns->load->model('GamePlayers');
        $result = $cIns->GamePlayers->getByUserId($userId);

        // 個人用メッセージボードのメッセージ取得
        $cIns->load->model('UserBoard');
        $myMessages = $cIns->UserBoard->getLastest($userId, $lineNumber, $page);

        // 所属グループ一覧取得（登録済み、登録予約）
        $cIns->load->model('GamePlayers');
        $myGroups = $cIns->GamePlayers->getByUserId($userId);

        // 所属可能グループ一覧

        $data = array(
            'MyMessages' => $myMessages,
            'MyGroups' => $myGroups
        );
        return $data;
    }
}
