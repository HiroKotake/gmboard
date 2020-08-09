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
        $libGroup->gameId = $libGroup->trnasAliasToGameId($obfGameId);
        $libGroup->groupId = $libGroup->transAliasToId($obfGroupId, ID_TYPE_GROUP);
        if ($libGroup->groupId == 0) {
            // セッションからGroupIdを取れなかった場合の保険
            $libGroup->groupId = $libGroup->getAliasIdtoGroupId($obfGroupId);
        }
echo 'GamaId&nbsp;:&nbsp;' . $libGroup->gameId . '<br />';
echo 'GroupId:&nbsp;' . $libGroup->groupId. '<br />';
echo '[DEBUG]&nbsp;' . $libGroup->getAliasIdtoGroupId($obfGroupId) . '<br />';
        $data = $libGroup->getPageData($this->userId);
echo '<br />';
var_dump($data);
        // 登録済みゲーム取得
        // グループ告知取得
        // グループメッセージ取得
        // グループ管理者判定し、メニューを変更

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

    /**
     * グループ加入申請
     * @return [type] [description]
     */
    public function petition()
    {

    }
}
