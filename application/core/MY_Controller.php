<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\gmboard\dao\SystemCommon;

class MY_Controller extends CI_Controller
{
    private $browserType = BROWSER_TYPE_FULL;

    public $redis = null;
    public $userId = null;
    public $sysComns = null;

    public function __construct()
    {
        parent::__construct();
        // タイムゾーン設定
        date_default_timezone_set('Asia/Tokyo');
        // ユーザ情報
        $this->load->library('user_agent');
        // セッション情報
        $this->load->library('session');
        // URLヘルパー
        $this->load->helper('url');
        // システム用DBテーブル
        $this->sysComns = new SystemCommon();
        // UserIdの確認
        if (isset($_SESSION['userId'])) {
            // 存在するならば$userIdに入れる
            $this->userId = $_SESSION['userId'];
        }
        // ログイン状態にない場合はトップ画面へ遷移
        $uri = uri_string();
        if (ENVIRONMENT == 'production') {
            if (!in_array($uri, EXCLUDE_USER_CHECK) || $uri == "") {
                if (empty($this->userId) && !empty(mb_strlen($uri))) {
                    redirect("");
                }
            }
        } else {
            if (!in_array($uri, EXCLUDE_USER_CHECK_NON_PRD)) {
                if (empty($this->userId) && !empty(mb_strlen($uri))) {
                    redirect("");
                }
            }
        }
        // redis
        $this->redis = new \Redis();
        $this->config->load('redis', true);
        $servers = $this->config->item('redis');
        $this->redis->pconnect($servers['default']['hostname'], $servers['default']['port']);
    }

    /**
     * ユーザIDをセッションに入れ、基底変数として保持する
     * @param int $userId [description]
     */
    public function setUserId(int $userId)
    {
        $this->session->set_userdata('userId', $userId);
        $this->session->mark_as_temp('userId', 24 * 60 * 60);
        $this->userId = $userId;
    }
}
