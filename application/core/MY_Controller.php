<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    private $browserType = BROWSER_TYPE_FULL;

    public function __construct()
    {
        parent::__construct();
        // ユーザ情報
        $this->load->library('user_agent');
        // セッション情報
        $this->load->library('session');
    }
}
