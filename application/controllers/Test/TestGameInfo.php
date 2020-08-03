<?php

use teleios\gmboard\libs\test\GameInfo;

/**
 * テスト環境向ゲーム情報コントローラークラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category controller
 * @package teleios\gmboard
 */
class TestGameInfo extends MY_Controller
{
    /********************************************************
     * ゲーム情報関連  lib: GameInfo
     ********************************************************/
    // ゲーム情報追加
    public function formGameInfo()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('GameInfo/formGameInfo', $data);
    }

    public function addGameInfo()
    {
        $gameName = $this->input->post('GameName');
        $description = $this->input->post('Description');
        if (empty($gameName) or empty($description)) {
            $data = array(
                'Message' => '必須入力項目が入力されていません'
            );
            $this->smarty->testView('GameInfo/formGameInfo', $data);
            return;
        }
        $testGameInfo = new GameInfo();
        $result = $testGameInfo->addGameInfo($gameName, $description);
        $data = array(
            'Message'  => '',
            'GameId'   => $result['GameId'],
            'GameInfo' => $result
        );
        if (count($result) == 0) {
            $data['Message'] = 'ゲーム情報の追加に失敗しました。<br />';
        }
        $this->smarty->testView('GameInfo/showGameInfo', $data);
    }
    // ゲーム情報一覧表示
    public function listGameInfo()
    {
        $testGameInfo = new GameInfo();
        $data = array(
            'list' => $testGameInfo->listGameInfo()
        );
        $this->smarty->testView('GameInfo/listGameInfo', $data);
    }
    // ゲーム情報確認
    public function showGameInfo()
    {
        $gameId = $this->input->get('GameID');
        $testGameInfo = new GameInfo();
        $gameInfo = $testGameInfo->showGameInfo((int)$gameId);
        $data = array(
            'Message' => '',
            'GameInfo' => $gameInfo
        );
        if ($gameInfo->isEmpty()) {
            $data['Message'] = 'No DATA';
        }
        $this->smarty->testView('GameInfo/showGameInfo', $data);
    }
}
