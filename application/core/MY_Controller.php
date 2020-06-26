<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    private $browserType = BROWSER_TYPE_FULL;

    public $redis = null;
    public $userId = null;

    public function __construct()
    {
        parent::__construct();
        // タイムゾーン設定
        date_default_timezone_set('Asia/Tokyo');
        // ユーザ情報
        $this->load->library('user_agent');
        // セッション情報
        $this->load->library('session');
        // システム用DBテーブル
        $this->load->model('dao/SystemCommon', 'sysComns');
        // UserIdの確認
        if (isset($_SESSION['userId'])) {
            // 存在するならば$userIdに入れる
            $this->userId = $_SESSION['userId'];
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
        $this->userId = $userId;
    }
}
