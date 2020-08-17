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
// システム固定値
defined('EXCLUDE_USER_CHECK')   OR define('EXCLUDE_USER_CHECK', [               // 起動時"UserID"チェック除外"URI"
    'MyPage/login',
    'MyPage/regist'
]);
defined('EXCLUDE_USER_CHECK_NON_PRD')OR define('EXCLUDE_USER_CHECK_NON_PRD', [  // 起動時"UserID"チェック除外"URI"
    'MyPage/login',
    'MyPage/regist',
    'Test/Top/index'
]);
defined('LINE_NUMBER_BOARD')    OR define('LINE_NUMBER_BOARD', 100);            // 掲示板の１ページあたりの最大表示数
defined('LINE_NUMBER_SEARCH')   OR define('LINE_NUMBER_SEARCH', 20);            // 検索結果の１ページあたりの最大表示数
// ユーザエージェント関連
defined('BROWSER_TYPE_FULL')    OR define('BROWSER_TYPE_FULL', 'full');         // User's Browser Type is PC browser
defined('BROWSER_TYPE_SP')      OR define('BROWSER_TYPE_SP', 'sp');             // User's Browser type is Smart Phone
// セッション関連
defined('SESSION_USER_ID')            OR define('SESSION_USER_ID', 'userId');                // UserID
defined('SESSION_INFO_GAME')          OR define('SESSION_INFO_GAME', 'games');               // 登録ゲーム
defined('SESSION_INFO_GROUP')         OR define('SESSION_INFO_GROUP', 'groups');             // 登録グループ
defined('SESSION_LIST_GENRE')         OR define('SESSION_LIST_GENRE', 'genreList');          // ドロップダウンメニュー用ユーザ個別ゲームカテゴリリスト
defined('SESSION_LIST_GAME')          OR define('SESSION_LIST_GAME', 'gameList');            // ドロップダウンメニュー用ユーザ個別ゲームリスト
defined('SESSION_LIST_GROUP_GENRE')   OR define('SESSION_LIST_GROUP_GENRE', 'gGenreList');   // ドロップダウンメニュー用ユーザ個別ゲームカテゴリリスト
defined('SESSION_LIST_GROUP_GAME')    OR define('SESSION_LIST_GROUP_GAME', 'gGameList');     // ドロップダウンメニュー用ユーザ個別ゲームリスト
defined('SESSION_LIST_ALIAS')         OR define('SESSION_LIST_ALIAS', 'aliasList');          // セッションIDのリスト
// システム固有キー名
defined('SYSTEM_KEY_GAMELIST_VER') OR define('SYSTEM_KEY_GAMELIST_VER', 'GamesListVer'); // 設定ゲームのリストのバージョンを示すキー名
// DBステータス
defined('DB_STATUS_NO_EXISTED') OR define('DB_STATUS_NO_EXISTED', 100);         // 対象のレコードが存在しない
defined('DB_STATUS_EXISTED')    OR define('DB_STATUS_EXISTED', 101);            // 対象のレコードは既に存在する
defined('DB_STATUS_ADDED')      OR define('DB_STATUS_ADDED', 102);              // レコードの追加が完了
defined('DB_STATUS_UPDATED')    OR define('DB_STATUS_UPDATED', 103);            // レコードの更新が完了
defined('DB_STATUS_FALIED')     OR define('DB_STATUS_FALIED', 190);             // DB操作に失敗
// 運営のID
defined('SYSTEM_USER_ID')       OR define('SYSTEM_USER_ID', '999999999999');    // System UserId
defined('SYSTEM_USER_NAME')     OR define('SYSTEM_USER_NAME', '運営');           // System User名
defined('SYSTEM_NOTICE_ID')     OR define('SYSTEM_NOTICE_ID', '999999999998');  // System UserId (Notice)
defined('SYSTEM_NOTICE_NAME')   OR define('SYSTEM_NOTICE_NAME', 'システム告知');  // System User名
defined('SYSTEM_GROUP_ID')      OR define('SYSTEM_GROUP_ID', '999999999999');   // System GroupId
defined('SYSTEM_GROUP_NAME')    OR define('SYSTEM_GROUP_NAME', '運営');          // System GroupId
// メール送信関連
defined('MAIL_SEND_SUCCESS')    OR define('MAIL_SEND_SUCCESS', 0);              // メール送信成功
defined('MAIL_SEND_FAILED')     OR define('MAIL_SEND_FAILED', 1);               // メール送信不良
// 告知関連コード
defined('NOTICE_GLOBAL')        OR define('NOTICE_GLOBAL', 0);                  // 全体告知
defined('NOTICE_MEMBER')        OR define('NOTICE_MEMBER', 1);                  // メンバー告知
defined('NOTICE_GROUP_ADMIN')   OR define('NOTICE_GROUP_ADMIN', 10);            // グループ管理者告知
defined('NOTICE_GROUP_MEMBER')  OR define('NOTICE_GROUP_MEMBER', 11);           // グループメンバー告知
// 認証関連
defined('AUTH_MATCH_PASSWORD')      OR define('AUTH_MATCH_PASSWORD', 100);      // パスワード認証正常
defined('AUTH_NO_EXIST_USER')       OR define('AUTH_NO_EXIST_USER', 110);       // ログインIDが合致するものが存在しない
defined('AUTH_UNMATCH_PASSWORD')    OR define('AUTH_UNMATCH_PASSWORD', 111);    // パスワード不一致
defined('AUTH_DELETED_USER')        OR define('AUTH_DELETED_USER', 121);        // 退会ユーザ
defined('AUTH_EXPLODE_USER')        OR define('AUTH_EXPLODE_USER', 122);        // アカBangユーザ
defined('AUTH_REGIST_SUCCESS')      OR define('AUTH_REGIST_SUCCESS', 200);      // 新規登録正常終了
defined('AUTH_REGIST_DEV_SUCCESS')  OR define('AUTH_REGIST_DEV_SUCCESS', 201);  // 開発環境でのユーザ新規登録正常終了
defined('AUTH_REGIST_DONE')         OR define('AUTH_REGIST_DONE', 210);         // レジストレーション正常終了
defined('AUTH_REGIST_ERROR')        OR define('AUTH_REGIST_ERROR', 290);        // ユーザ新規登録失敗
defined('AUTH_ACTIVATE_SUCCESS')    OR define('AUTH_ACTIVATE_SUCCESS', 300);    // アクティベーション成功
defined('AUTH_ACTIVATE_NOEXIST')    OR define('AUTH_ACTIVATE_NOEXIST', 312);    // 対象のアクティベーションコードが存在しない
defined('AUTH_ACTIVATE_EXPIRE')     OR define('AUTH_ACTIVATE_EXPIRE', 312);     // アクティベーション期限切れ
defined('AUTH_ACTIVATE_UNMATCH')    OR define('AUTH_ACTIVATE_UNMATCH', 313);    // アクティベーションコード不一致
// マイページ関連
defined('PAGE_ID_PERSONAL')          OR define('PAGE_ID_PERSONAL', 1);               // マイページ画面モード：個人トップ表示
defined('PAGE_ID_GROUP_MAIN')        OR define('PAGE_ID_GROUP_MAIN', 101);           // グループページ：メイン
defined('PAGE_ID_GROUP_MEMBER_LIST') OR define('PAGE_ID_GROUP_MEMBER_LIST', 102);    // グループページ：メンバーリスト
defined('PAGE_ID_GROUP_REQEST_LIST') OR define('PAGE_ID_GROUP_REQEST_LIST', 103);    // グループページ：申請者リスト
defined('PAGE_ID_GROUP_INVITATION')  OR define('PAGE_ID_GROUP_INVITATION', 104);     // グループページ：招待者
defined('PAGE_ID_GROUP_EXTENTION')   OR define('PAGE_ID_GROUP_INVITATION', 110);     // グループページ：拡張機能用
defined('PAGE_ID_GAME_MAIN')         OR define('PAGE_ID_GAME_MAIN', 201);            // ゲームページ：メイン


defined('KEY_ALIAS_GAME')       OR define('KEY_ALIAS_GAME', 'aliasGame');       // ゲームエイリアス
defined('KEY_ALIAS_GROUP')      OR define('KEY_ALIAS_GROUP', 'aliasGroup_');    // グループエイリアス(ゲーム別)
defined('KEY_GAME_INFO')        OR define('KEY_GAME_INFO', 'GameInfomation');   // Redisキー名 (ゲーム情報)
defined('KEY_GAME_CATEGORY')    OR define('KEY_GAME_CATEGORY', 'GameCategory'); // Redisキー名 (カテゴリ別ゲームリスト)
defined('GAME_CATEGORY')        OR define('GAME_CATEGORY', [                    // ゲームカテゴリ
    'RPG'           => 1,
    'MMO'           => 2,
    'FPS'           => 3,
    'TPS'           => 4,
    'シミュレーション' => 5,
    'レース'         => 6,
    'スポーツ'        => 7,
    'テーブルゲーム'   => 8,
    'カードゲーム'    => 9,
]);
defined('GAME_CATEGORY_RB')     OR define('GAME_CATEGORY_RB', [                 // ゲームカテゴリ(インデックス)
    1   =>  'RPG',
    2   =>  'MMO',
    3   =>  'FPS',
    4   =>  'TPS',
    5   =>  'シミュレーション',
    6   =>  'レース',
    7   =>  'スポーツ',
    8   =>  'テーブルゲーム',
    9   =>  'カードゲーム',
]);
// 自動作成テーブル名
defined('TABLE_PREFIX_GAME_PLAYER')    OR define('TABLE_PREFIX_GAME_PLAYER', 'GamePlayers_');
defined('TABLE_PREFIX_GROUP_BOARD')    OR define('TABLE_PREFIX_GROUP_BOARD', 'GBoard_');
defined('TABLE_PREFIX_GROUP_NOTICE')   OR define('TABLE_PREFIX_GROUP_NOTICE', 'GNotice_');
defined('TABLE_PREFIX_GROUP')          OR define('TABLE_PREFIX_GROUP', 'Groups_');
defined('TABLE_PREFIX_REGIST_BOOKING') OR define('TABLE_PREFIX_REGIST_BOOKING', 'RegistBooking_');
defined('TABLE_PREFIX_USER_BOARD')     OR define('TABLE_PREFIX_USER_BOARD', 'UBoard_');
// テーブル名
defined('TABLE_NAME_CI_SESSIONS')         OR define('TABLE_NAME_CI_SESSIONS', 'CiSessions');
defined('TABLE_NAME_GAME_INFOS')          OR define('TABLE_NAME_GAME_INFOS', 'GameInfos');
defined('TABLE_NAME_GAME_EXTEND')         OR define('TABLE_NAME_GAME_EXTEND', 'GameExtend');
defined('TABLE_NAME_GAME_PLAYERS_ALIAS')  OR define('TABLE_NAME_GAME_PLAYERS_ALIAS', 'GamePlayersAlias');
defined('TABLE_NAME_GROUP_ALIAS')         OR define('TABLE_NAME_GROUP_ALIAS', 'GroupAlias');
defined('TABLE_NAME_GROUP_BOARD_ALIAS')   OR define('TABLE_NAME_GROUP_BOARD_ALIAS', 'GroupBoardAlias');
defined('TABLE_NAME_GROUP_NOTICES_ALIAS') OR define('TABLE_NAME_GROUP_NOTICES_ALIAS', 'GroupNoticesAlias');
defined('TABLE_NAME_NOTICES')             OR define('TABLE_NAME_NOTICES', 'Notices');
defined('TABLE_NAME_PLAYER_INDEX')        OR define('TABLE_NAME_PLAYER_INDEX', 'PlayerIndex');
defined('TABLE_NAME_REGISTRATION')        OR define('TABLE_NAME_REGISTRATION', 'Registration');
defined('TABLE_NAME_SYSTEM_COMMON')       OR define('TABLE_NAME_SYSTEM_COMMON', 'SystemCommon');
defined('TABLE_NAME_USER_INFOS')          OR define('TABLE_NAME_USER_INFOS', 'UserInfos');
defined('TABLE_NAME_USERS')               OR define('TABLE_NAME_USERS', 'Users');
// グループ権限タイプ
defined('GROUP_AUTHORITY_LEADER')      OR define('GROUP_AUTHORITY_LEADER', 1);      // リーダー： 任命権、招待操作可能、申請操作可能、告知読み書き可能、掲示板読み書き可能
defined('GROUP_AUTHORITY_SUB_LEADER')  OR define('GROUP_AUTHORITY_SUB_LEADER', 2);  // サブリーダー： 申請操作可能、告知読み書き可能、掲示板読み書き可能
defined('GROUP_AUTHORITY_MENBER')      OR define('GROUP_AUTHORITY_MENBER', 3);      // 一般メンバー： 告知読み書き可能、掲示板読み書き可能
defined('GROUP_AUTHORITY_OBSERVER')    OR define('GROUP_AUTHORITY_OBSERVER', 4);    // オブザーバー: 掲示板読み書き可能
defined('GROUP_AUTHORITY_GUEST')       OR define('GROUP_AUTHORITY_GUEST', 5);       // ゲスト： 掲示板リードオンリー
// ID種別 (各テーブルのプリマリーキーを記述。teleios/gmboard/dao/Beanと連動させる)
defined('ID_TYPE_CI_SESSION')       OR define("ID_TYPE_CI_SESSION", "id");                  // idx:0
defined('ID_TYPE_USER')             OR define("ID_TYPE_USER", "UserId");                    // idx:1
defined('ID_TYPE_GAME_INFOS')       OR define("ID_TYPE_GAME_INFOS", "GameId");              // idx:2
defined('ID_TYPE_GAME_EXTEND')      OR define("ID_TYPE_GAME_EXTEND", "GameExtendId");       // idx:3
defined('ID_TYPE_GAME_PLAYER')      OR define("ID_TYPE_GAME_PLAYER", "GamePlayerId");       // idx:4
defined('ID_TYPE_GROUP')            OR define("ID_TYPE_GROUP", "GroupId");                  // idx:5

defined('ID_TYPE_USER_BOARD')       OR define("ID_TYPE_USER_BOARD", "UBoardMsgId");         // idx:50
defined('ID_TYPE_USER_INFOS')       OR define("ID_TYPE_USER_INFOS", "UserInfoId");          // idx:51
defined('ID_TYPE_GROUP_BOARD')      OR define("ID_TYPE_GROUP_BOARD", "GBoardMsgId");        // idx:52
defined('ID_TYPE_GROUP_NOTICE')     OR define("ID_TYPE_GROUP_NOTICE", "GNoticeId");         // idx:53
defined('ID_TYPE_NOTICE')           OR define("ID_TYPE_NOTICE", "NoticeId");                // idx:54
defined('ID_TYPE_PLAYER_INDEX')     OR define("ID_TYPE_PLAYER_INDEX", "PlayerIndexId");     // idx:55
defined('ID_TYPE_REGIST_BOOKING')   OR define("ID_TYPE_REGIST_BOOKING", "RegistBookingId"); // idx:56
defined('ID_TYPE_REGISTRATION')     OR define("ID_TYPE_REGISTRATION", "RegistrationId");    // idx:57
defined('ID_TYPE_SYSTEM_COMMON')    OR define("ID_TYPE_SYSTEM_COMMON", "SystemCommonId");   // idx:58

defined('ID_TYPE_CODE_LIST')        OR define("ID_TYPE_CODE_LIST", [
    // セッションで保持する
    ID_TYPE_CI_SESSION      => 0,
    ID_TYPE_USER            => 1,
    ID_TYPE_GAME_INFOS      => 2,
    ID_TYPE_GAME_EXTEND     => 3,
    ID_TYPE_GAME_PLAYER     => 4,
    ID_TYPE_GROUP           => 5,
    // セッションで保持しない
    ID_TYPE_USER_BOARD      => 50,
    ID_TYPE_USER_INFOS      => 51,
    ID_TYPE_GROUP_BOARD     => 52,
    ID_TYPE_GROUP_NOTICE    => 53,
    ID_TYPE_NOTICE          => 54,
    ID_TYPE_PLAYER_INDEX    => 55,
    ID_TYPE_REGIST_BOOKING  => 56,
    ID_TYPE_REGISTRATION    => 57,
    ID_TYPE_SYSTEM_COMMON   => 58
]);
defined('ID_TYPE_REV_CODE_LIST')    OR define("ID_TYPE_REV_CODE_LIST",[
    // セッションで保持する
     0 => ID_TYPE_CI_SESSION,
     1 => ID_TYPE_USER,
     2 => ID_TYPE_GAME_INFOS,
     3 => ID_TYPE_GAME_EXTEND,
     4 => ID_TYPE_GAME_PLAYER,
     5 => ID_TYPE_GROUP,
    // セッションで保持しない
    50 => ID_TYPE_USER_BOARD,
    51 => ID_TYPE_USER_INFOS,
    52 => ID_TYPE_GROUP_BOARD,
    53 => ID_TYPE_GROUP_NOTICE,
    54 => ID_TYPE_NOTICE,
    55 => ID_TYPE_PLAYER_INDEX,
    56 => ID_TYPE_REGIST_BOOKING,
    57 => ID_TYPE_REGISTRATION,
    58 => ID_TYPE_SYSTEM_COMMON
]);
