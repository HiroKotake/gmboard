<?php
namespace teleios\gmboard\libs;

class Auth
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
    }

    /**
     * 新規登録ログインID重複確認
     * @param  string $loginId [description]
     * @return bool            [description]
     */
    public function checkDeplicateLid(string $loginId) : bool
    {
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $data = $this->cIns->daoUsers->getByLoginId($loginId);
        if (count($data) == 0) {
            return true;
        }
        return false;
    }

    /**
     * 新規登録（認証コード発行）
     * @param  string $loginId [description]
     * @return string          [description]
     */
    public function buildRegistCode(string $loginId) : string
    {
        return sha1(microtime() . $loginId);
    }

    /**
     * ログインパスワード確認
     * @param  string $loginId [description]
     * @param  string $pwd     [description]
     * @return int             [description]
     */
    public function checkPassword(string $loginId, string $pwd) : int
    {
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $data = $this->cIns->daoUsers->getByLoginId($loginId);
        if (count($data) == 0) {
            return AUTH_NO_EXIST_USER;
        }
        if (!password_verify($pwd, $data['Password'])) {
            return AUTH_UNMATCH_PASSWORD;
        }
        return AUTH_MATCH_PASSWORD;
    }
}
