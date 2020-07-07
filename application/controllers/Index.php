<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\gmboard\libs\SystemNotice;

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
        $notices = null;
        $libSystemNotice = new SystemNotice();
        $notices = $libSystemNotice->getTopNotices();
        // ・ ログインエリア
        // ・ 新規登録誘導
        // ー フッター部
    	//$this->load->view('welcome_message');
    	$data = array(
            'Notices' => $notices,
            'Login' => 'MyPage/login',
            'Regist' => 'MyPage/regist'
        );
    	$this->smarty->view('top', $data);
    }

    /**
     * [showNotices description]
     * @return [type] [description]
     */
    public function showNotices()
    {
        $page = $this->input->get('page');  // 表示ページ番号
echo "もっと見る($page)";
    }

}
