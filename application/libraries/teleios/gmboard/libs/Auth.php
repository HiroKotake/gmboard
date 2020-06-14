<?php
namespace teleios\gmboard\libs;

use teleios\utils\StringUtility;

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
    public function buildActivationCode(string $loginId) : string
    {
        return sha1(microtime() . $loginId);
    }

    /**
     * レジストレーションテーブルに追加
     * @param  int    $userId  [description]
     * @param  string $regCode [description]
     * @return int             レジストレーションID
     */
    public function addRegistration(int $userId, string $regCode) : int
    {
        $this->cIns->load->model('dao/Registration', 'daoRegist');
        $data = array(
            'UserId' => $userId,
            'Rcode' => $regCode,
            'ExpireDate' => (date('Y-m-d h:i:s', strtotime("+ 6hour")))
        );
        return $this->cIns->daoRegist->add($data);
    }

    /**
     * 新規登録時レジストれションメール送信
     * @param  string $mail    [description]
     * @param  int    $regId   [description]
     * @param  string $regCode [description]
     * @return int             [description]
     */
    public function sendRegistrationMail(string $mail, int $regId, string $regCode) : int
    {
        if (ENVIRONMENT == 'production') {
            // 本番環境ならメール送信
            // メールに添付するリンク先URLにはGET値として、レジストレーションIDを付与する
        }
        return MAIL_SEND_SUCCESS;
    }

    /**
     * レジストレーションをチェックし、仮登録状態から正式登録状態にする
     * @param  int    $regId   [description]
     * @param  string $regCode [description]
     * @return int             ユーザID
     */
    public function checkRegCode(string $regId, string $regCode) : int
    {
        $this->cIns->load->model('dao/Registration', 'daoRegist');
        $resultSet = $this->cIns->daoRegist->get($regId);
        // 対象レコードがない
        if (count($resultSet) <= 0) {
            return AUTH_ACTIVATE_NOEXIST;
        }
        // 期限切れチェック
        $current = time();
        if (strtotime($resultSet['ExpireDate']) < $current) {
            // 期限切れ
            return AUTH_ACTIVATE_EXPIRE;
        }
        // アクティベーションコードチェック
        if ($regCode != $resultSet['Rcode']) {
            return AUTH_ACTIVATE_UNMATCH;
        }
        // 仮登録を正規登録に変更
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $this->cIns->daoUsers->mailAuthed($resultSet['UserId']);
        $this->cIns->daoRegist->set($regId, ['ActivatedDate' => date('Y-m-d H:i:s')]);
        return AUTH_ACTIVATE_SUCCESS;
    }

    /**
     * ログインパスワード確認
     * @param  string $loginId [description]
     * @param  string $pwd     [description]
     * @return array           ['status' => 検証結果、 'userId' => パスワードが一致したらユーザIDが含まれる]
     */
    public function checkPassword(string $loginId, string $pwd) : array
    {
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $data = $this->cIns->daoUsers->getByLoginId($loginId);
        $result = array(
            'status' => null,
            'userId' => null
        );
        if (count($data) == 0) {
            $result['status'] = AUTH_NO_EXIST_USER;
            return $result;
        }
        if (!password_verify($pwd, $data['Password'])) {
            $result['status'] = AUTH_UNMATCH_PASSWORD;
            return $result;
        }
        $result['status'] = AUTH_MATCH_PASSWORD;
        $result['userId'] = $data['UserId'];
        return $result;
    }

    /**
     * ユーザを追加する
     * @param  string $nickname [description]
     * @param  string $loginId  [description]
     * @param  string $password [description]
     * @return int              システム内部用ユーザID
     */
    public function newUser(string $nickname, string $loginId, string $password) : int
    {
        // Usersテーブルへ追加
        $data = array(
            'Mail'      => $loginId,
            'Password'  => StringUtility::getHashedPassword($password),
            'Nickname'  => $nickname
        );
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $userId = $this->cIns->daoUsers->add($data);
        // ユーザ用掲示板作成
        $this->cIns->load->model('dao/UserBoard', 'daoUserBoard');
        $this->cIns->daoUserBoard->createTable($userId);
        // ウェルカムメッセージ追加
        $welcomeMessage = array(
            'FromUserId'    => SYSTEM_USER_ID,                // 送信者ユーザID
            'FromGroupId'   => SYSTEM_GROUP_ID,               // 送信者グループID
            'message'       => 'ようこそ、いらっしょいました！'    // メッセージテキスト
        );
        $this->cIns->daoUserBoard->add($userId, $welcomeMessage);

        return $userId;
    }
}
