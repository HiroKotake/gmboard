<?php
namespace gmboard\application\models;

use teleios\utils\StringUtility;

/**
 * ユーザ関連
 */
class User
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
    }

    /****************************************************************************************************
     * データ登録系メソッド
     ****************************************************************************************************/
    /**
     * ログインIDが重複していないかチェック
     * @param  string $loginId ログインID
     * @return bool            重複していない場合には true を返し、重複している場合には false を返す
     */
    public function checkDuplicate(string $loginId) : bool
    {
        $cIns->load->model('dao/Users', 'daoUsers');
        $result = $cIns->daoUsers->getByLoginId($loginId);
        return count($result) > 0 ? false : true;
    }

    /**
     * ユーザを新規登録する
     * @param  string $loginId  ログインID
     * @param  string $passwd   平文パスワード
     * @param  string $nickname ニックネーム
     * @param  string $mailAddr 承認用メールアドレス
     * @return int              正常に登録した場合には 0以上の値を返し、失敗した場合には 0 を返す
     */
    public function addNewUser(
        string $loginId,    //`LoginId` 'ログインID',
        string $passwd,     //`Password` 'ハッシュ済みパスワード',
        string $nickname,   //`Nickname` 'ニックネーム',
        string $mailAddr    //`Mail` '連絡先メールアドレス',
    ) : int {
        // Table : User
        $cIns->load->model('dao/Users', 'daoUsers');
        $stringUtil = new StringUtility();
        $data = array(
            'LoginId'   => $loginId,
            'Password'  => $stringUtil->getHashedPassword($passwd),
            'Nickname'  => $nickname,
            'Mail'      => $mailAddr
        );
        $userId = $cIns->daoUsers->addNewUser($data);
        // Table : UserBoard
        if ($userId > 0) {
            $cIns->load->model('dao/UserBoard', 'daoUserBoard');
            $cIns->daoUserBoard->createBoard($userId);
            return $userId;
        }
        return false;
    }

    /****************************************************************************************************
     * 認証系メソッド
     ****************************************************************************************************/
    /**
     * ログイン認証を実施
     * @param  string $loginId ログインID
     * @param  string $passwd  平文パスワード
     * @return bool            認証に成功した場合は true を、失敗した場合は false を返す
     */
    public function auth(string $loginId, string $passwd) : bool
    {
        $cIns->load->model('dao/Users', 'daoUsers');
        $records = $cIns->daoUsers->getByLoginId($loginId);
        if (count($records) > 0) {
            $pwdHash = $this->getHashedPassword($passwd);
            $userRecord = $records[0];
            if ($pwdHash === $userRecord['Password']) {
                return true;
            }
        }
        return false;
    }

    /****************************************************************************************************
     * 更新系メソッド
     ****************************************************************************************************/
}
