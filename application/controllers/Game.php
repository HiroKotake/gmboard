<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\gmboard\libs\GamePage;
/**
 * ゲーム情報コントローラークラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category controller
 * @package teleios\gmboard
 */
class Game extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $obfGameId = $this->input->get("gmid");
        $libGamePage = new GamePage();
        $data = $libGamePage->getPageData($this->userId, $obfGameId);
        $this->smarty->view('game/top', $data);
    }

    public function group()
    {
        $obfGameId = $this->input->get("gmid");
        $page = $this->input->get("page");
        $libGamePage = new GamePage();
        $data = $libGamePage->getGroupData($this->userId, $obfGameId, $page);
        $this->smarty->view('game/group', $data);
    }

    public function searchGroup()
    {
        $obfGameId = $this->input->get("gpid");
        $groupName = $this->input->get("tgn");
        $currentPageNumber = $this->input->get("pg");
        $page = $currentPageNumber - 1;
        $libGamePage = new GamePage();
        $data = $libGamePage->searchGroupByName($this->userId, $obfGameId, $groupName, $page);
        $this->smarty->view('game/group', $data);
    }
}
