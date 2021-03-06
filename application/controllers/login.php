<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ログイン画面
 */
class Login extends MY_Controller
{
    /******************************************************/
    /* valiables                                          */
    /******************************************************/

    /******************************************************/
    /* functions                                          */
    /******************************************************/
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ログイン機能を含むトップページを表示させる
     * @return [type] [description]
     */
    public function index()
    {
        $this->smarty->viewWithoutHeaderAndFooter("login");
    }

    /**
     * 認証を試行し、当該ページへ遷移させる
     * @return [type] [dscription]
     */
    public function auth()
    {
        $loginId = $this->input->post('gid');
        $pwd = $this->input->post('pwd');
        // 認証確認
        $this->load->model('User');
        $result = $this->User($loginId, $pwd);
        if ($result) {
            // 認証失敗
            $data = array(
                'Warning' => 'ログインIDもしくはパスワードが間違っていいます。'
            );
            $this->smarty->viewWithoutHeaderAndFooter("login", $data);
        }
        // マイページを表示
        $data = array();
        $this->smarty->view("mypage", $data);
    }
}
