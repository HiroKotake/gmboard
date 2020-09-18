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
        $obfGameId = $this->input->get("gmid");
        $obfGroupId = $this->input->get("grid");
        $libGroup = new libGroup();
        $data = $libGroup->getPageData($this->userId, $obfGameId, $obfGroupId);
        $data['PageId'] = PAGE_ID_GROUP_MAIN;
        // グループ管理者判定し、メニューを変更
        $this->smarty->view('group/top', $data);
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
        $libGroup = new libGroup();
        $data = $libGroup->getPageMemberList($this->userId, $obfGameId, $obfGroupId);
        $data['PageId'] = PAGE_ID_GROUP_REQEST_LIST;
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
        $libGroup = new libGroup();
        $data = $libGroup->getPageMemberList($this->userId, $obfGameId, $obfGroupId);
        $data['PageId'] = PAGE_ID_GROUP_INVITATION;
        $this->smarty->view('group/invite', $data);
    }

    /**
     * グループ検索
     * 指定された名称を含むグループを検索を、検索結果を表示する
     *
     * @return [type] [description]
     */
    public function search()
    {
        $gameId = $this->input->get("gpid");
        $groupName = $this->input->get("tgn");
        $currentPageNumber = $this->input->get("pg");
        $pageNumber = $currentPageNumber - 1;
        // グループ検索
        $libGroup = new libGroup();
        $searchResult = $libGroup->searchByGroupName($gameId, $groupName, $this->userId, $pageNumber, LINE_NUMBER_SEARCH);
        // データ生成
        $data = $libGroup->getPageData($this->userId);
        $data['Type'] = 'GroupSearch';
        $data['Title'] = 'グループ検索結果';
        $data['List'] = $searchResult['GroupList'];
        $data['MaxLineNumber'] = LINE_NUMBER_SEARCH;
        $data['TotalNumber'] = $searchResult['TotalNumber'];
        $data['CurrentPage'] = $currentPageNumber;
        $totalPageSub = $searchResult['TotalNumber'] % LINE_NUMBER_SEARCH;
        $totalPage = ($searchResult['TotalNumber'] - $totalPageSub) / LINE_NUMBER_SEARCH;
        if ($totalPageSub > 0) {
            $totalPage += 1;
        }
        $data['TotalPage'] = $totalPage;
        $this->smarty->view('group', $data);
    }

    // 退会
    public function withdraw()
    {

    }

    // 除名
    public function dismiss()
    {

    }

    /**
     * グループ加入申請
     * @return [type] [description]
     */
    public function petition()
    {

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
        $libGroup->setBaseInfos($obfGameId, $obfGroupId);
        // 権限変更
        $result = $libGroup->changeAuth($obfTargetUserId, $newAuth, $this->userId);
        // 戻り処理
        $data = $libGroup->getPageData($this->userId, $obfGameId, $obfGroupId);
        $data['Result'] = $result;
        // メンバーリスト
        $data = $libGroup->getPageMemberList($this->userId, $obfGameId, $obfGroupId);
        $data['PageId'] = PAGE_ID_GROUP_MEMBER_LIST;
        // ページ送信
        $this->smarty->view('group/member', $data);

    }
}
