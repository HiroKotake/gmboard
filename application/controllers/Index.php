<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * トップページ表示
     * @return [type] [description]
     */
    public function index()
    {
        // トップページ
        // [表示内容]
        // ・ 告知
        $this->load->library('SystemNotice');
        // ・ ログインエリア
        // ・ 新規登録誘導
        // ー フッター部
    	//$this->load->view('welcome_message');
    	$this->smarty->view('top');
    }
}
