<?php

use teleios\gmboard\libs\test\GamePlayer;

/**
 * テスト環境向ゲームプレイヤーコントローラークラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category controller
 * @package teleios\gmboard
 */
class TestGamePlayer extends MY_Controller
{
    /**
     * ゲージ一覧を表示
     * @return [type] [description]
     */
    public function formGameList()
    {
        $testGamePlayer = new GamePlayer();
        $data = $testGamePlayer->formGameList();
        $this->smarty->testView('GamePlayer/formGameList', $data);
    }

    /**
     * ゲームプレイヤーを表示
     * @return [type] [description]
     */
    public function formGamePlayer()
    {
        $gameId = $this->input->get('GID');
        $groupId = $this->input->get('GPID');
        $data = array(
            'Message' => '',
            'GameId' => $gameId,
            'GroupId' => $groupId
        );
        $testGamePlayer = new GamePlayer();
        $result = $testGamePlayer->formGamePlayer($gameId, $groupId);
        $data = array_merge($data, $result);
        $this->smarty->testView('GamePlayer/formGamePlayer', $data);
    }

    /**
     * ゲームプレイヤーを追加
     */
    public function addGamePlayer()
    {
        $gameId = $this->input->post('GID');
        $groupId = $this->input->post('GPID');
        $playerId = $this->input->post('PID');
        $nickname = $this->input->post('NNAME');
        $authcode = $this->input->post('ACODE');

        echo 'DEBUG:<br />';
        echo 'GameID:&nbsp;' . $gameId . '<br />';
        echo 'GroupId:&nbsp;' . $groupId . '<br />';
        echo 'GamePlayerId:&nbsp;' . $playerId . '<br />';
        echo 'GameNickname:&nbsp;' . $nickname . '<br />';
        echo '<hr />';
        $data = array(
            'Message' => '',
            'GameId' => $gameId,
            'GroupId' => $groupId
        );

        $testGamePlayer = new GamePlayer();
        $result = $testGamePlayer->addGamePlayer($gameId, $groupId, $playerId, $nickname, $authcode);
        if (count($result['PlayerInfo']) == 0) {
            $data['Message'] = '登録に失敗しました';
        }
        $data['PlayerInfo'] = $result['PlayerInfo'];
        $data['GameInfo'] = $result['GameInfo'];
        $this->smarty->testView('GamePlayer/addGamePlayer', $data);
    }

    /**
     * ゲーム一覧を表示
     * @return [type] [description]
     */
    public function listGames()
    {
        $testGamePlayer = new GamePlayer();
        $data = $testGamePlayer->listGames();
        $this->smarty->testView('GamePlayer/listGames', $data);
    }

    /**
     * グループを指定してプレイヤー一覧を表示
     * @return [type] [description]
     */
    public function listGamePlayers()
    {
        $gameId = $this->input->get('GID');
        $testGamePlayer = new GamePlayer();
        $data = $testGamePlayer->listGamePlayers((int)$gameId);
        $this->smarty->testView('GamePlayer/listGamePlayers', $data);
    }

    /**
     * ゲームプレイヤー情報を表示
     * @return [type] [description]
     */
    public function showGamePlayer()
    {
        $data = array(
            'Message' => '対象のゲームプレイヤーは登録されていません',
            'GamePlayer' => null
        );
        $registBookingId = $this->input->get('RBID');
        $gameId = $this->input->get('GID');
        $testGamePlayer = new GamePlayer();
        $data['GamePlayer'] = $testGamePlayer->showGamePlayer((int)$gameId, (int)$registBookingId);
        $this->smarty->testView('GamePlayer/showGamePlayer', $data);
    }
}
