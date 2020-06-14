<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/*
|--------------------------------------------------------------------------
| Special Codes
|--------------------------------------------------------------------------
*/
defined('BROWSER_TYPE_FULL')   OR define('BROWSER_TYPE_FULL', 'full');          // User's Browser Type is PC browser
defined('BROWSER_TYPE_SP')     OR define('BROWSER_TYPE_SP', 'sp');              // User's Browser type is Smart Phone

defined('SYSTEM_USER_ID')      or define('SYSTEM_USER_ID', '999999999999');     // System UserId
defined('SYSTEM_GROUP_ID')     or define('SYSTEM_GROUP_ID', '999999999999');    // System GroupId
// メール送信関連
defined('MAIL_SEND_SUCCESS')    or define('MAIL_SEND_SUCCESS', 0);              // メール送信成功
defined('MAIL_SEND_FAILED')     or define('MAIL_SEND_FAILED', 1);               // メール送信不良
// 告知関連コード
defined('NOTICE_GLOBAL')       or define('NOTICE_GLOBAL', 0);                   // 全体告知
defined('NOTICE_MEMBER')       or define('NOTICE_MEMBER', 1);                   // メンバー告知
defined('NOTICE_GROUP_ADMIN')  or define('NOTICE_GROUP_ADMIN', 10);             // グループ管理者告知
defined('NOTICE_GROUP_MEMBER') or define('NOTICE_GROUP_MEMBER', 11);            // グループメンバー告知
// 認証関連
defined('AUTH_MATCH_PASSWORD')      or define('AUTH_MATCH_PASSWORD', 100);      // パスワード認証正常
defined('AUTH_NO_EXIST_USER')       or define('AUTH_NO_EXIST_USER', 110);       // ログインIDが合致するものが存在しない
defined('AUTH_UNMATCH_PASSWORD')    or define('AUTH_UNMATCH_PASSWORD', 111);    // パスワード不一致
defined('AUTH_DELETED_USER')        or define('AUTH_DELETED_USER', 121);        // 退会ユーザ
defined('AUTH_EXPLODE_USER')        or define('AUTH_EXPLODE_USER', 122);        // アカBangユーザ
defined('AUTH_REGIST_SUCCESS')      or define('AUTH_REGIST_SUCCESS', 200);      // 新規登録正常終了
defined('AUTH_REGIST_DEV_SUCCESS')  or define('AUTH_REGIST_DEV_SUCCESS', 201);  // 開発環境でのユーザ新規登録正常終了
defined('AUTH_REGIST_DONE')         or define('AUTH_REGIST_DONE', 210);         // レジストレーション正常終了
defined('AUTH_REGIST_ERROR')        or define('AUTH_REGIST_ERROR', 290);        // ユーザ新規登録失敗
defined('AUTH_ACTIVATE_SUCCESS')    or define('AUTH_ACTIVATE_SUCCESS', 300);    // アクティベーション成功
defined('AUTH_ACTIVATE_NOEXIST')    or define('AUTH_ACTIVATE_NOEXIST', 312);     // 対象のアクティベーションコードが存在しない
defined('AUTH_ACTIVATE_EXPIRE')     or define('AUTH_ACTIVATE_EXPIRE', 312);     // アクティベーション期限切れ
defined('AUTH_ACTIVATE_UNMATCH')    or define('AUTH_ACTIVATE_UNMATCH', 313);    // アクティベーションコード不一致
