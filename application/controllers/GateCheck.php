<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\gmboard\libs\Auth;

class GateCheck extends MY_Controller
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
        $loginId = $this->input->get('lid');  // 表示ページ番号
        $password = $this->input->get('pwd');  // 表示ページ番号
        $libAuth = new Auth();
        $authResult = $libAuth->checkPassword($loginId, $password);

        if ($authResult == AUTH_MATCH_PASSWORD) {
            // ユーザページ表示
echo 'ユーザページ';
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
     * @return [type] [description]
     */
    public function regist()
    {
        $loginId    = $this->input->post('uid');
        $pwd        = $this->input->post('pwd');
        $rpd        = $this->input->post('rpd');
        $mail       = $this->input->post('mail');
        $libAuth = new Auth();
        // パスワード確認
        if ($pwd != $rpd) {
            // 告知取得
echo "パスワード同一性エラー";
            return;
        }
        // ログインID重複チェック
        if (!$libAuth->checkDeplicateLid($loginId)) {
            // 告知取得
echo "すでに使われいるログインID";
            return;
        }
        // ユーザ追加
        // レジストレーションデータ追加
        // メース送信
echo "新規登録";
    }
}
