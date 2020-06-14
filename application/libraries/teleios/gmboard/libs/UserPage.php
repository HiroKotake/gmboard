<?php
namespace teleios\gmboard\libs;

class UserPage
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
    }

    /**
     * [MyPage description]
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function getPageData(int $userId) : array
    {
        $data = array();
        // 登録ゲーム取得
        // 登録グループ取得
        // 個人向けメッセージ取得
        return $data;
    }

}
