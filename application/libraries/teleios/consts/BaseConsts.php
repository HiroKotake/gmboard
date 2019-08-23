<?php
namespace teleios\consts;

/**
 * フレームワーク共通定数定義ベースクラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category const
 * @package teleios
 */
class BaseConsts
{
    /*********************************************************************************************************/
    /* アプリケーションログ関連                                                                                  */
    /*********************************************************************************************************/
    const APP_LOG_DB_PREFIX = "log_sql_";        //  SQLログファイルのPREFIX
    const APP_LOG_DEBUG_PREFIX = "log_debug_";        //  デバッグ用ログファイルのPREFIX
    /*********************************************************************************************************/
    /* キャッシュ関連                                                                                          */
    /*********************************************************************************************************/
    const MEMCACHE_TYPE = "Radis";                              // KVPに関しては、'memcache','Redis'の指定が可能
    const MEMCACHE_SVR_LOCAL = "default";                       // ローカル用のキャッシュサーバのグループ名
    const MEMCACHE_KEY_DB_TABLE_LIST = "db_table_list_";        // テーブルリストのキャッシュ上のキー名
}
