<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\dao\Notices;

/**
 * システム告知関連操作ライブラリ
 */

class SystemNotice
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
        $this->cIns->load->model('dao/Notices', 'daoNotices');
    }

    // トップ告知
    public function getTopNotices(int $page = 0, int $number = 10) : array
    {
        return  $this->cIns->daoNotices->get(NOTICE_GLOBAL, $page * $number);
    }
    // メンバー全体告知
    public function getMemberNotices(int $page = 0, int $number = 10) : array
    {
        return  $this->cIns->daoNotices->get(NOTICE_MEMBER, $page * $number);
    }
    // グループ管理者内告知
    public function getGroupAdminNotice(int $page = 0, int $number = 10) : array
    {
        return  $this->cIns->daoNotices->get(NOTICE_GROUP_ADMIN, $page * $number);
    }
}
