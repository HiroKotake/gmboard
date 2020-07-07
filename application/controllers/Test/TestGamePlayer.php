<?php

use teleios\gmboard\dao\test\GamePlayer;

/********************************************************
 * ユーザ関連２   lib: GamePlayer
 ********************************************************/
class TestGamePlayer extends MY_Controller
{
    public function formGameList()
    {
        $testGamePlayer = new GamePlayer();
        $data = $testGamePlayer->formGameList();
        $this->smarty->testView('GamePlayer/formGameList', $data);
    }
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

    public function listGames()
    {
        $testGamePlayer = new GamePlayer();
        $data = $testGamePlayer->listGames();
        $this->smarty->testView('GamePlayer/listGames', $data);
    }
    public function listGamePlayers()
    {
        $gameId = $this->input->get('GID');
        $testGamePlayer = new GamePlayer();
        $data = $testGamePlayer->listGamePlayers((int)$gameId);
        $this->smarty->testView('GamePlayer/listGamePlayers', $data);
    }
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
