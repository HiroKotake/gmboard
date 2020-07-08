<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\dao\Notices;

/**
 * システム告知関連操作ライブラリ
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */

class SystemNotice
{
    private $daoNotices = null;

    public function __construct()
    {
        $this->daoNotices = new Notices();
    }

    /**
     * トップ告知
     * @param  integer $page   [description]
     * @param  integer $number [description]
     * @return array           [description]
     */
    public function getTopNotices(int $page = 0, int $number = 10) : array
    {
        return  $this->daoNotices->get(NOTICE_GLOBAL, $page * $number);
    }

    /**
     * メンバー全体告知
     * @param  integer $page   [description]
     * @param  integer $number [description]
     * @return array           [description]
     */
    public function getMemberNotices(int $page = 0, int $number = 10) : array
    {
        return  $this->daoNotices->get(NOTICE_MEMBER, $page * $number);
    }

    /**
     * グループ管理者内告知
     * @param  integer $page   [description]
     * @param  integer $number [description]
     * @return array           [description]
     */
    public function getGroupAdminNotice(int $page = 0, int $number = 10) : array
    {
        return  $this->daoNotices->get(NOTICE_GROUP_ADMIN, $page * $number);
    }
}
