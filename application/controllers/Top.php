<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ユーザ登録画面
 */
class regist extends MY_Controller
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

    public function index()
    {
        $this->smarty->view('top');
    }
}
