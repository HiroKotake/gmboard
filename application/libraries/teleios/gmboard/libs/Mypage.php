<?php
namespace taleios\gmboard\libs;

class Mypage
{

    public function __construct()
    {

    }

    /**
     * マイページ表示用のデータを取得する
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function myPage(int $userId) : array
    {
        $data = array();
        // 会員向けを含む告知取得
        // 個人向けメッセージ取得
        // 登録ゲーム取得
        return $data;
    }

    public function getGroupData(int $userId, int $gameId, int $groupId) : array
    {
        $data = array();
        return $data;
    }
}
