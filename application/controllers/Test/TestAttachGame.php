<?php

/********************************************************
 * ユーザ関連３   lib: AttachGame
 * GamePlayerテーブルへ情報を書き込むことができるか確認する。
 * 本来は直接データの実施はせず、登録予約承認に併せて行う。
 ********************************************************/
class TestAttachGame extends MY_Controller
{
    public function formAttachGame()
    {
        $userId = $this->input->get('UID');
        // データ作成
        $data = array(
            'Message' => '',
            'UserId' => $userId
        );
        $this->load->model('test/AttachGame', 'testAttachGame');
        $data['GameInfos'] = $this->testAttachGame->formAttachGame();
        $this->smarty->testView('AttachGame/formAttachGame', $data);
    }

    public function addAttachGame()
    {
        $userId = $this->input->post('UID');
        $gameId = $this->input->post('GID');
        $playerId = $this->input->post('GPID');
        $nickname = $this->input->post('NNAME');
        echo 'デバッグ<br />';
        echo '$useId&nbsp;=&nbsp;' . $userId . '<br />';
        echo '$gameId&nbsp;=&nbsp;' . $gameId . '<br />';
        echo '$playerId&nbsp;=&nbsp;' . $playerId . '<br />';
        echo '$nickname&nbsp;=&nbsp;' . $nickname . '<br />';
        echo '<hr />';
        // データ作成
        $data = array(
            'SubTitle' => 'ゲームプレイヤー登録完了',
            'Message' => '',
            'GamePlayer' => null
        );
        $this->load->model('test/AttachGame', 'testAttachGame');
        $data['GamePlayer'] = $this->testAttachGame->addAttachGame((int)$userId, (int)$gameId, (int)$playerId, $nickname);
        $this->smarty->testView('AttachGame/showAttachGame', $data);
    }

    public function showAttachGame()
    {
        $gameId = $this->input->get('GMID');
        $groupId = $this->input->get('GID');
        // データ作成
        $data = array(
            'SubTitle' => 'グループ員一覧',
            'Message' => '',
            'Infos' => null
        );
        $this->load->model('test/AttachGame', 'testAttachGame');
        $data['Infos'] = $this->testAttachGame->showAttachGame((int)$gameId, (int)$groupId);
        /*
        var_dump($data);
        echo '<br /><hr />';
        echo '<a href="/Test/top">戻る</a>';
        */
        $this->smarty->testView('AttachGame/showAttachGame', $data);
    }
}
