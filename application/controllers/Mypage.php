<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\utils\StringUtility;
use teleios\gmboard\libs\SystemNotice;
use teleios\gmboard\libs\Auth;
use teleios\gmboard\libs\UserPage;

class MyPage extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ログイン
     * @return [type] [description]
     */
    public function login()
    {
        $loginId = $this->input->post('lid');
        $password = $this->input->post('pwd');
        $libAuth = new Auth();
        $authResult = $libAuth->checkPassword($loginId, $password);

        if ($authResult['status'] == AUTH_MATCH_PASSWORD) {
            // セッションにUserIDを放り込む
            $this->setUserId($authResult['userId']);
            // ユーザページ表示
            $libUserPage = new UserPage();
            $data = $libUserPage->getPageData($authResult['userId']);
            $data['Mode'] = MYPAGE_MODE_PERSONAL;
            $this->smarty->view('mypage', $data);
            return;
        }

        $data = array('Message' => '存在しないログインIDです。');
        if ($authResult == AUTH_UNMATCH_PASSWORD) {
            $data['Message'] = 'パスワードが違います。';
        }
        $this->smarty->view('top', $data);
    }

    /**
     * ユーザ新規登録(仮執行)
     *  [ページの目的]
     *  ログインIDの重複チェックをして、重複がなければ
     *  Userテーブルにレコード登録し、
     *  レジストレーションコードを生成、Registrationテーブルに登録
     *  インビテーションメール送信
     * @return none
     */
    public function regist()
    {
        $nickname   = $this->input->post('nickname');
        $mail       = $this->input->post('mail');
        $pwd        = $this->input->post('pwd');
        $rpd        = $this->input->post('rpd');
        $message    = "";

        // ニックネーム未入力
        if (empty($nickname)) {
            $message .= "ニックネーム未入力<br />";
        }
        // メイルアドレス不良
        $fCheckMailAddr = StringUtility::isMailAddr($mail);
        if (!$fCheckMailAddr) {
            $message .= "メールアドレス不良<br />";
        }
        if (empty($pwd)) {
            $message .= "パスワード未入力<br />";
        }
        if (empty($rpd)) {
            $message .= "確認用パスワード未入力<br />";
        }
        // パスワード確認
        if ($pwd != $rpd) {
            // 告知取得
            $message .= "パスワード同一性エラー<br />";
        }

        $libAuth = new Auth();
        // ログインID重複チェック
        if ($fCheckMailAddr) {
            if (!$libAuth->checkDeplicateLid($mail)) {
                $message .= "すでに使われいるログインID<br />";
            }
        }

        // エラーが発生している場合はここで終了
        if (!empty($message)) {
            // ・ 告知
            $notices = null;
            $libSystemNotice = new SystemNotice();
            $notices = $libSystemNotice->getTopNotices();
            $this->smarty->view('top', ['message' => $message]);
            return;
        }

        // ユーザ追加
        $userId = $libAuth->newUser($nickname, $mail, $pwd);
        // レジストレーションコード生成
        $regCode = $libAuth->buildActivationCode($mail);
        // レジストレーションコードを登録
        $regId = $libAuth->addRegistration($userId, $regCode);

        // レジストレーションデータ追加
        if (ENVIRONMENT == 'production') {
            // メール送信し、レジストレーションコードを入力する画面へ遷移
            $result = $libAuth->sendRegistrationMail($mail, $regId, $regCode);
            $this->smarty->view('mypage', ['regid' => $regId, 'mailResult' => $result]);
        } else {
            // レジストレーションを実施
            $result = $libAuth->checkRegCode($regId, $regCode);
            // デバッグ環境なのでレジスト済みにして、ユーザページへ遷移
            $libUserPage = new UserPage();
            $data = $libUserPage->getPageData($userId);
            $this->smarty->view('mypage', $data);
        }
    }

    public function getGames()
    {
        $libUserPage = new UserPage();
        $games = $libUserPage->getGamelistWithCategory();
        $joins = $libUserPage->getGameList($this->userId);
        $gamesList = $libUserPage->getGameListsModifedByPersonal($games, $joins);
        $data = json_encode($gamesList);
        echo $data;
    }

    public function attachGame()
    {
        $targetGameId = $this->input->post("target");
        $gamePlayerId = $this->input->post("gpid");
        $gameNickname = $this->input->post("gnn");
        // PlayerIndexテーブルとGamePlayers_xxxxxxxxテーブルへ情報を追加
        $libUserPage = new UserPage();
        $result = $libUserPage->attachGame($this->userId, (int)$targetGameId, $gamePlayerId, $gameNickname);
        $data = array(
            "Status" => $result["Status"]
        );
        echo json_encode($data);
    }
}
