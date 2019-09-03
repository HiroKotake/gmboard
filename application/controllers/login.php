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

    public function index()
    {
        $this->smarty->viewWithoutHeaderAndFooter("login");
    }
}
