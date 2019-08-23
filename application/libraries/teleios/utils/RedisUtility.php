<?php

namespace teleios\utils;

/**
 * redis 操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category Utility
 * @package teleios
 */

class RedisUtility
{
    /******************************************************/
    /* valiables                                          */
    /******************************************************/
    private static $hCache = array();

    /******************************************************/
    /* functions                                          */
    /******************************************************/
    /**
     * redis に値をセットする
     *
     * @param String $key キー名
     * @param mix $value 設定する値
     * @param String $cacheGroup 設定ファイル上のグループ名 - 省略時:default
     * @param int $expireTime タイムアウト時間（秒） - 省略時:300
     * @return bool
     **/
    public static function set(
        $key,
        $value,
        $cacheGroup = 'default',
        $expireTime = 300
    ) : bool {
        // コネクション設定
        if (self::locConnect($cacheGroup)) {
            // 値をセット
            $result = self::$hCache[$cacheGroup]->set($key, serialize($value));
            if ($result) {
                // 有効期間を設定
                $now = self::$hCache[$cacheGroup]->time(null);
                self::$hCache[$cacheGroup]->expireAt($now + $expireTime);
            }
            return $result;
        }
        return false;
    }

    /**
     * redis から値を取得する
     *
     * @param String $key キー名
     * @param String $cacheGroup 設定ファイル上のグループ名 - 省略時:default
     * @return mix 設定した値
     **/
    public static function get($key, $cacheGroup = 'default')
    {
        // コネクション設定
        if (self::locConnect($cacheGroup)) {
            // 値をゲット
            $value = self::$hCache[$cacheGroup]->get($key);
            if (!$value) {
                return unserialize($value);
            }
        }
        return false;
    }

    /**
     * redis から値を削除する
     *
     * @param String $key キー名
     * @param String $cacheGroup 設定ファイル上のグループ名 - 省略時:default
     * @return bool
     */
    public static function delete($key, $cacheGroup = 'default') : bool
    {
        // コネクション設定
        if (self::locConnect($cacheGroup)) {
            // 値確認し、値を削除
            return (self::$hCache[$cacheGroup]->del($key) > 0 ? true : false);
        }
        return false;
    }

    /**
     * redis から値を全て削除する
     *
     * @param String $cacheGroup 設定ファイル上のグループ名 - 省略時:default
     * @return bool
     */
    public static function flush($cacheGroup = 'default') : bool
    {
        // コネクション設定
        if (self::locConnect($cacheGroup)) {
            // キャッシュを全削除
            return self::$hCache[$cacheGroup]->flushAll();
        }
    }

    /**
     * Redis へコネクションを張る
     * @param  string $cacheGroup 設定ファイル上のグループ名 - 省略時:default
     * @return bool               コネクションを張れたら true を、失敗した場合には false を返す
     */
    private static function locConnect($cacheGroup = 'default') : bool
    {
        $mConf = self::locGetRedisConf($cacheGroup);
        if (empty($mConf)) {
            return false;
        }
        self::$hCache[$cacheGroup] = new Radis();
        self::$hCache[$cacheGroup]->pconnect($mConf['hostname'], $mConf['port']);
        if (empty(self::$hCache[$cacheGroup])) {
            return false;
        }
        return true;
    }

    /**
     * 設定ファイルからmemcacheサーバの情報をを取得する
     *
     * @param String $memcacheGroup 設定ファイル上のグループ名
     * @return なし
     */
    private static function locGetRedisConf($cacheGroup)
    {
        $CI =& get_instance();
        $CI->config->load('redis', true);
        $mconf = $CI->config->item('redis');
        if (array_key_exists($cacheGroup, $mconf)) {
            return $mconf[$cacheGroup];
        }
        return null;
    }
}
