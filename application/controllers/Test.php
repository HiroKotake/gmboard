<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\utils\StringUtility;

/**
 * ログイン画面
 */
class Test extends MY_Controller
{
    /**
     * テストトップ画面
     * @return [type] [description]
     */
    public function index()
    {
        $this->smarty->testView('top');
    }

    /********************************************************
     * ゲーム情報関連  lib: GameInfo
     ********************************************************/
    // ゲーム情報追加
    public function formGameInfo()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('formGameInfo', $data);
    }

    public function addGameInfo()
    {
        $gameName = $this->input->post('GameName');
        $description = $this->input->post('Description');
        if (empty($gameName) or empty($description)) {
            $data = array(
                'message' => '必須入力項目が入力されていません'
            );
            $this->smarty->testView('formGameInfo', $data);
            return;
        }
        $this->load->model('test/GameInfo', 'testGameInfo');
        $result = $this->testGameInfo->addGameInfo($gameName, $description);
        $data = array(
            'Message'  => '',
            'GameId'   => $result[0]['GameId'],
            'GameInfo' => $result[0]
        );
        if (count($result) == 0) {
            $data['Message'] = 'ゲーム情報の追加に失敗しました。<br />';
        }
        $this->smarty->testView('showGameInfo', $data);
    }
    // ゲーム情報一覧表示
    public function listGameInfo()
    {
        $this->load->model('test/GameInfo', 'testGameInfo');
        $data = array(
            'list' => $this->testGameInfo->listGameInfo()
        );
        $this->smarty->testView('listGameInfo', $data);
    }
    // ゲーム情報確認
    public function showGameInfo()
    {
        $gameId = $this->input->get('GameID');
        $this->load->model('test/GameInfo', 'testGameInfo');
        $gameInfo = $this->testGameInfo->showGameInfo((int)$gameId);
        $data = array(
            'Message' => '',
            'GameInfo' => $gameInfo
        );
        if (count($gameInfo) == 0) {
            $data['Message'] = 'No DATA';
        }
        $this->smarty->testView('showGameInfo', $data);
    }
    /********************************************************
     * ユーザ関連１   lib: User
     ********************************************************/
    // ユーザ追加
    public function formUser()
    {
        $data = array(
            'message' => '入力をお願いします'
        );
        $this->smarty->testView('formUser', $data);
    }
    public function addUser()
    {
        $loginId    = $this->input->post('LoginID');
        $passwd     = $this->input->post('PWD');
        $repasswd   = $this->input->post('RPWD');
        $nickname   = $this->input->post('NNAME');
        $mailAddr   = $this->input->post('MAIL');
        // 入力確認
        $notice = "";
        if (mb_strlen($loginId) == 0) {
            $notice .= 'ログインIDが入力されていません。<br />';
        }
        if (!preg_match("/^[a-zA-Z0-9_-]+$/", $loginId)) {
            $notice .= 'ログインIDは半角英数、ハイフン、アンダーバーのみ使用できます。<br />';
        }
        if (mb_strlen($passwd) == 0) {
            $notice .= 'パスワードが入力されていません。<br />';
        }
        if (mb_strlen($repasswd) == 0) {
            $notice .= 'パスワード(確認)が入力されていません。<br />';
        }
        if ($repasswd !== $repasswd) {
            $notice .= '入力されたパスワードと確認用パスワードが異なります<br />';
        }
        if (mb_strlen($nickname) == 0) {
            $notice .= 'ニックネームが入力されていません。<br />';
        }
        if (mb_strlen($mailAddr) == 0) {
            $notice .= 'メールアドレスが入力されていません。<br />';
        }
        if (!preg_match("/^[a-zA-Z0-9_-]+$/", $loginId)) {
            $notice .= 'ログインIDは半角英数、ハイフン、アンダーバーのみ使用できます。<br />';
        }
        $sUtil = new StringUtility();
        if (!$sUtil->isMailAddr($mailAddr)) {
            $notice .= 'メールアドレスの記述が間違っています<br />';
        }

        // ログインID重複チェック
        $this->load->model('test/User', 'testUser');
        $isLoginIdDeplicate = $this->testUser->checkDeplicate($loginId);
        if (!$isLoginIdDeplicate) {
            $notice .= '入力されたログインIDは、既に使われています。<br />';
        }

        // エラーがある場合は入力画面を再表示
        if (mb_strlen($notice) > 0) {
            $data = array('Message' => $notice);
            $this->smarty->testView('formUser', $data);
            return;
        }

        // 登録
        $newUserId = $this->testUser->addUser($loginId, $passwd, $nickname, $mailAddr);

        // 登録情報取得
        $userInfo = $this->testUser->showUser($newUserId);
        $data = array(
            'Message' => '',
            'UserInfo' => $userInfo[0]
        );

        $this->smarty->testView('showUser', $data);
    }
    // ユーザ一覧表示
    public function listUser()
    {
        $this->load->model('test/User', 'testUser');
        $users = $this->testUser->listUser();
        $message = '';
        if (count($users) == 0) {
            $message = 'ユーザが登録されていません。';
        }
        $data = array(
            'Message' => $message,
            'Users' => $users
        );
        $this->smarty->testView('listUser', $data);
    }
    // ユーザ情報表示
    public function showUser()
    {
        $userId = $this->input->get('UserId');
        $this->load->model('test/User', 'testUser');
        $userInfo = $this->testUser->showUser($userId);
        $message = '';
        $user = null;
        if (count($userInfo) == 0) {
            $message = '該当するユーザは存在しません。';
        } else {
            $user = $userInfo[0];
        }
        $data = array(
            'Message' => $message,
            'UserInfo' => $user
        );
        $this->smarty->testView('showUser', $data);
    }
    // ログイン認証・確認
    public function checkLogin()
    {
        $this->smarty->testView('checkLogin');
    }

    public function doLogin()
    {
        $loginId = $this->input->post('LID');
        $password = $this->input->post('PWD');
        echo 'LoginId：' . $loginId . '<br />';
        echo 'Password：' . $password. '<br />';
        $this->load->model('test/User', 'testUser');
        $result = $this->testUser->auth($loginId, $password);
        if ($result) {
            echo 'ログイン認証成功<br />';
            echo '<a href="./">戻る</a>';
            return;
        }
        echo 'ログイン認証失敗<br />';
        echo '<a href="./">戻る</a>';
    }
    /********************************************************
     * ユーザ関連２   lib: GamePlayer
     ********************************************************/
    public function formGamePlayer()
    {
        $data = array(
            'Message' => ''
        );
        $this->load->model('test/GamePlayer', 'testGamePlayer');
        $data['GameInfos'] = $this->testGamePlayer->formGamePlayer();
        $this->smarty->testView('formGamePlayer', $data);
    }
    public function addGamePlayer()
    {
        $gameId = $this->input->post('GID');
        $playerId = $this->input->post('GPID');
        $nickname = $this->input->post('NNAME');

        echo 'DEBUG:<br />';
        echo 'GameID:&nbsp;' . $gameId . '<br />';
        echo 'GamePlayerId:&nbsp;' . $playerId . '<br />';
        echo 'GameNickname:&nbsp;' . $nickname . '<br />';
        echo '<hr />';
        $data = array(
            'Message' => ''
        );

        $this->load->model('test/GamePlayer', 'testGamePlayer');
        $result = $this->testGamePlayer->addGamePlayer($gameId, $playerId, $nickname);
        if (count($result['PlayerInfo']) == 0) {
            $data['Message'] = '登録に失敗しました';
        }
        $data['PlayerInfo'] = $result['PlayerInfo'];
        $data['GameInfo'] = $result['GameInfo'];
        $this->smarty->testView('addGamePlayer', $data);
    }
    public function listGamePlayer()
    {
        $this->load->model('test/GamePlayer', 'testGamePlayer');
        $data = $this->testGamePlayer->listGamePlayer();
        $this->smarty->testView('listGamePlayer', $data);
    }
    public function showGamePlayer()
    {
        $data = array(
            'Message' => '対象のゲームプレイヤーは登録されていません',
            'GamePlayer' => null
        );
        $gamePlayerId = $this->input->get('GPID');
        $this->load->model('test/GamePlayer', 'testGamePlayer');
        $data['GamePlayer'] = $this->testGamePlayer->showGamePlayer((int) $gamePlayerId);
        $this->smarty->testView('showGamePlayer', $data);
    }
    /********************************************************
     * ユーザ関連３   lib: AttachGame
     ********************************************************/
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
        $this->smarty->testView('formAttachGame', $data);
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
        $this->smarty->testView('showAttachGame', $data);
    }

    public function showAttachGame()
    {
        $gamePlayerId = $this->input->get('GPID');
        // データ作成
        $data = array(
            'SubTitle' => 'ゲームプレイヤー詳細',
            'Message' => '',
            'GamePlayer' => null
        );
        $this->load->model('test/AttachGame', 'testAttachGame');
        $data['GamePlayer'] = $this->testAttachGame->showAttachGame($gamePlayerId);
        $this->smarty->testView('showAttachGame', $data);
    }

    /********************************************************
     * グループ関連   lib: Group
     ********************************************************/
    // グループ追加
    public function formGroup()
    {
        // ゲーム一覧取得
        $this->load->model('test/Group', 'testGroup');
        $games = $this->testGroup->formGroup();
        // データ作成
        $data = array(
            'Message' => '',
            'Games' => $games
        );
        $this->smarty->testView('formGroup', $data);
    }
    public function addGroup()
    {
        $data = array(
            'Message' => ''
        );
        // 入力値チェック
        $targetGame  = $this->input->post('TRGT');
        $groupName   = $this->input->post('GNAME');
        $description = $this->input->post('DESCRIP');
        if (mb_strlen($groupName) == 0) {
            $data['Message'] .= 'グループ名が入力されていません。<br />';
        }
        if (mb_strlen($description) == 0) {
            $data['Message'] .= '説明が入力されていません。<br />';
        }
        if (mb_strlen($data['Message']) > 0) {
            $this->load->model('test/Group', 'testGroup');
            $games = $this->testGroup->formGroup();
            $data['Games'] = $games;
            $this->smarty->testView('formGroup', $data);
            return;
        }
        // グループ登録
        $this->load->model('test/Group', 'testGroup');
        $groupInfo = $this->testGroup->addGroup($targetGame, $groupName, $description);
        if (count($groupInfo) == 0) {
            echo '登録に失敗しました<br /><a href="./">戻る</a>';
            return;
        }
        // グループ内容表示
        $this->load->model('dao/GameInfos', 'daoGameInfos');
        $gameInfo = $this->daoGameInfos->getByGameId($groupInfo['GameId']);
        $data['GroupInfo'] = $groupInfo;
        $data['GameName'] = $gameInfo['Name'];
        $this->smarty->testView('showGroup', $data);
    }
    // グループ一覧表示
    public function listGroup()
    {
        $this->load->model('test/Group', 'testGroup');
        $groupList = $this->testGroup->listGroup();
        $games = $this->testGroup->formGroup();
        $data = array(
            'Message' => '',
            'Games' => $games,
            'GroupList' => $groupList
        );
        if (count($groupList) == 0) {
            $data['Message'] = 'グループは登録されていません。';
        }
        $this->smarty->testView('listGroup', $data);
    }
    // グループ情報表示
    public function showGroup()
    {
        $gpid = $this->input->get('GPID');
        $this->load->model('test/Group', 'testGroup');
        $groupInfo = $this->testGroup->showGroup($gpid);
        $data = array(
            'Message' => '',
            'GroupInfo' => $groupInfo
        );
        if (count($groupInfo) == 0) {
            $data['Message'] = '該当するグループはありません！';
        }
        $data['GameName'] = $groupInfo['GameName'];
        $this->smarty->testView('showGroup', $data);
    }
    /********************************************************
     * グループメンバー関連   lib: GroupMember
     ********************************************************/
    // グループメンバー追加１
    public function formAddGroupMember()
    {
        $groupId = $this->input->get('GPID');
        $data = array(
            'Message' => '',
            'GroupId' => $groupId,
            'RegistedMembers' => null,
            'BookingMembers' => null
        );
        $this->load->model('test/GroupMember', 'testGroupMember');
        $members = $this->testGroupMember->formAddGroupMember((int)$groupId);
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('formAddGroupMember', $data);
    }
    public function addGroupMember()
    {
        $playerId       = $this->input->post('GPID');
        $authCode       = $this->input->post('ACD');
        $gameNickName   = $this->input->post('GNIN');
        $groupId        = $this->input->post('GID');
        // データ登録
        $this->load->model('test/GroupMember', 'testGroupMember');
        $result = $this->testGroupMember->addGroupMember((int)$groupId, $playerId, $authCode, $gameNickName);
        $data = array(
            'Message' => '',
            'RegistedMembers' => $result['RegistedMembers'],
            'BookingMembers' => $result['BookingMembers'],
            'BookingMember' => $result['BookingMember']
        );
        $this->smarty->testView('addGroupMember', $data);
    }
    // グループメンバー追加２
    public function formSearchGroupMember()
    {
        $groupId = $this->input->get('GPID');
        $data = array(
            'Message' => '',
            'GroupId' => $groupId,
            'GroupInfo' => null,
            'RegistedMembers' => null,
            'BookingMembers' => null
        );
        $this->load->model('test/GroupMember', 'testGroupMember');
        $members = $this->testGroupMember->formSearchGroupMember((int)$groupId);
        $data['GroupInfo'] = $members['GroupInfo'];
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('formSearchGroupMember', $data);
    }
    public function resultSearchGroupMember()
    {
        $playerId = $this->input->post('GPID');
        $groupId = $this->input->post('GID');
        $data = array(
            'Message' => '',
            'GroupId' => $groupId,
            'PlayerInfo' => null,
            'RegistedMembers' => null,
            'BookingMembers' => null,
        );
        $this->load->model('test/GroupMember', 'testGroupMember');
        $result = $this->testGroupMember->resultSearchGroupMember((int)$groupId, $playerId);
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('formResultSearchGroupMember', $data);
    }
    public function addSearchGroupMember()
    {
        $playerId = $this->input->post('PID');
        $groupId = $this->input->post('GID');
        $gameId = $this->input->post('GMID');
        $data = array(
            'Message' => '',
            'GroupId' => $groupId,
            'RegistedMembers' => null,
            'BookingMembers' => null,
        );
        $this->load->model('test/GroupMember', 'testGroupMember');
        // メンバーを追加し、追加後のグループの状態情報を取得
        $members = $this->testGroupMember->addSearchGroupMember((int)$groupId, (int)$gameId, $playerId);
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('formSearchGroupMember', $data);
    }
    // グループメンバー一覧表示
    public function listGroupMember()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('listGroupMember', $data);
    }
    // グループメンバー除名
    public function formDelGroupMember()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('formDelGroupMember', $data);
    }
    public function delGroupMember()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('delGroupMember', $data);
    }
    /********************************************************
     * 掲示板関連    lib: GroupMessage / UserMessage
     ********************************************************/
    // グループ掲示板へメッセージ追加
    public function formGroupMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('formGroupMessage', $data);
    }
    public function addGroupMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('addGroupMessage', $data);
    }
    // グループ掲示板表示
    public function showGroupMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('showGroupMessage', $data);
    }
    // ユーザ掲示板へメッセージ追加
    public function formUserMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('formUserMessage', $data);
    }
    public function addUserMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('showUserMessage', $data);
    }
    // ユーザ掲示板表示
    public function showUserMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('showUserMessage', $data);
    }
}
