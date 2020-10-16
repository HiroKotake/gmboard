<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\gmboard\libs\GroupPage as libGroup;

/**
 * グループ関連コントローラー
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category controller
 * @package teleios\gmboard
 */
class Group extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * グループ関連情報表示
     * グループに関するトップページを表示する
     * @return [type] [description]
     */
    public function index()
    {
        /*
        $obfGameId = $this->input->get("gmid");
        $obfGroupId = $this->input->get("grid");
        $libGroup = new libGroup();
        $data = $libGroup->getMessagePage($this->userId, $obfGameId, $obfGroupId);
        $data['PageId'] = PAGE_ID_GROUP_MAIN;
        // グループ管理者判定し、メニューを変更
        $this->smarty->view('group/top', $data);
        */
       $this->notices();
    }

    /**
     * メッセージボード表示
     * @return [type] [description]
     */
    public function board()
    {
        $obfGameId = $this->input->get("gmid");
        $obfGroupId = $this->input->get("grid");
        $libGroup = new libGroup();
        $data = $libGroup->getMessagePage($this->userId, $obfGameId, $obfGroupId);
        $data['PageId'] = PAGE_ID_GROUP_MAIN;
        // グループ管理者判定し、メニューを変更
        $this->smarty->view('group/top', $data);
    }

    public function notices()
    {
        $obfGameId = $this->input->get("gmid");
        $obfGroupId = $this->input->get("grid");
        $page = $this->input->get("page") ?? 0;
        if ($page > 0) {
            $page = $page - 1;
        }
        $libGroup = new libGroup();
        $data = $libGroup->getNoticesData($this->userId, $obfGameId, $obfGroupId, $page);
        $data['PageId'] = PAGE_ID_GROUP_MAIN;
        // グループ管理者判定し、メニューを変更
        $this->smarty->view('group/notices', $data);
    }

    /**
     * グループメンバー関連情報表示
     * @return [type] [description]
     */
    public function memberList()
    {
        $obfGameId = $this->input->get("gmid");
        $obfGroupId = $this->input->get("grid");
        $libGroup = new libGroup();
        $data = $libGroup->getPageMemberList($this->userId, $obfGameId, $obfGroupId);
        $data['PageId'] = PAGE_ID_GROUP_MEMBER_LIST;
        $this->smarty->view('group/member', $data);
    }

    /**
     * グループ加入申請者関連情報表示
     * @return [type] [description]
     */
    public function requestList()
    {
        $obfGameId = $this->input->get("gmid");
        $obfGroupId = $this->input->get("grid");
        $page = $this->input->get("page");
        $libGroup = new libGroup();
        // 加入申請者リスト取得
        $data = $libGroup->getPageRequestList($this->userId, $obfGameId, $obfGroupId, $page);
        // 表示
        $this->smarty->view('group/request', $data);
    }

    /**
     * グループ招待関連情報表示
     * @return [type] [description]
     */
    public function inviteList()
    {
        $obfGameId = $this->input->get("gmid");
        $obfGroupId = $this->input->get("grid");
        $page = $this->input->get("page");
        $libGroup = new libGroup();
        // グループ招待者リスト取得
        $data = $libGroup->getPageInviteList($this->userId, $obfGameId, $obfGroupId, $page);
        // 表示
        $this->smarty->view('group/invite', $data);
    }

    public function newInvite()
    {
        $obfGameId = $this->input->get("gmid");
        $obfGroupId = $this->input->get("grid");
        $newPlayer = $this->input->get("gpid");  // ゲーム側のプレイヤーID
        $libGroup = new libGroup();
    }

    // ToDo グループ名検索機能については各コントローラー上で実装するように変更すること。view関連で問題が発生しているので、その対応。また、js側も改修が必要！
    /**
     * グループ検索
     * 指定された名称を含むグループを検索を、検索結果を表示する
     *
     * @return [type] [description]
     */
    public function search()
    {
        $gameId = $this->input->get("gpid");
        $groupId = $this->input->get("grid");
        $groupName = $this->input->get("tgn");
        $currentPageNumber = $this->input->get("pg");
        $pageNumber = $currentPageNumber - 1;
        $libGroup = new libGroup();
        $data = $libGroup->getGroupSearchPage($this->userId, $gameId, $groupId, $groupName, $pageNumber); // ToDo: グループIDが必要
        $this->smarty->view('group/group', $data);
    }

    /**
     * 退会
     * @return [type] [description]
     */
    public function withdraw()
    {
        $obfGameId = $this->input->post_get("gmid");
        $obfGroupId = $this->input->post_get("grid");
        $libGroup = new libGroup();
        $libGroup->setBaseInfos($obfGameId, $obfGroupId, $this->userId);
        // 退会処理
        $libGroup->doWithdraw();
        redirect("../mypage");
    }

    /**
     * 除名
     * @return [type] [description]
     */
    public function dismiss()
    {
        $obfGameId = $this->input->post_get("gmid");
        $obfGroupId = $this->input->post_get("grid");
        $obfTargetUserId = $this->input->post_get("tuid");
        $libGroup = new libGroup();
        $libGroup->setBaseInfos($obfGameId, $obfGroupId, $this->userId);
        // 除名処理
        $libGroup->doDismiss($obfTargetUserId, $this->userId);
        redirect("../group/memberList?gmid=" . $obfGameId . "&grid=" . $obfGroupId);
    }

    /**
     * グループ加入申請
     * @return [type] [description]
     */
    public function petition()
    {
        $obfGameId = $this->input->post_get("gmid");
        $obfGroupId = $this->input->post_get("grid");
        $libGroup = new libGroup();
        $libGroup->setBaseInfos($obfGameId, $obfGroupId, $this->userId);
        // 申請
        // 結果表示
    }

    /**
     * メンバー権限変更
     * @return [type] [description]
     */
    public function memberAuthChange()
    {
        // $serverMethod = $this->input->server(false);
        $obfGameId = $this->input->post_get("gmid");
        $obfGroupId = $this->input->post_get("grid");
        $obfTargetUserId = $this->input->post_get("tuid");
        $newAuth = $this->input->post_get("nath");
        $libGroup = new libGroup();
        // 権限変更
        $result = $libGroup->changeAuth($obfTargetUserId, $newAuth, $this->userId);
        // 戻り処理
        // メンバーリスト
        $data = $libGroup->getPageMemberList($this->userId, $obfGameId, $obfGroupId);
        $data['Result'] = $result;
        $data['PageId'] = PAGE_ID_GROUP_MEMBER_LIST;
        // ページ送信
        $this->smarty->view('group/member', $data);

    }
}
