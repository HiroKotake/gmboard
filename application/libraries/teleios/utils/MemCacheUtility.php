<?php
namespace teleios\utils;

/**
 * memcachd 操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category Utility
 * @package teleios
 */
class MemCacheUtility
{
    /******************************************************/
    /* valiables                                          */
    /******************************************************/
    private static $hCache = array();

    /******************************************************/
    /* functions                                          */
    /******************************************************/
    /**
     * memcachedに値をセットする
     *
     * @param String $key キー名
     * @param mix $value 設定する値
     * @param String $memcacheGroup 設定ファイル上のグループ名 - 省略時:default
     * @param int $expireTime タイムアウト時間（秒） - 省略時:300
     * @return bool
     **/
    public static function set($key, $value, $memcacheGroup = "default", $expireTime = 300) : bool
    {
        if (self::locConnect($memcacheGroup)) {
            $check = self::$hCache[$memcacheGroup]->get($key);
            if (!$check) {
                return self::$hCache[$memcacheGroup]->set($key, serialize($value), 0, $expireTime);
            }
            return self::$hCache[$memcacheGroup]->replace($key, serialize($value), 0, $expireTime);
        }
        return false;
    }

    /**
     * memcachedから値を取得する
     *
     * @param String $key キー名
     * @param String $memcacheGroup 設定ファイル上のグループ名 - 省略時:default
     * @return mix 設定した値
     **/
    public static function get($key, $memcacheGroup = "default")
    {
        $result = null;
        if (self::locConnect($memcacheGroup)) {
            $value = self::$hCache[$memcacheGroup]->get($key);
            $result = unserialize($value);
        }
        return $result;
    }

    /**
     * memcachedから値を削除する
     *
     * @param String $key キー名
     * @param String $memcacheGroup 設定ファイル上のグループ名 - 省略時:default
     * @return bool
     */
    public static function delete($key, $memcacheGroup = "default") : bool
    {
        if (self::locConnect($memcacheGroup)) {
            return self::$hCache[$memcacheGroup]->delete($key);
        }
        return false;
    }

    /**
     * memcachedから値を全て削除する
     *
     * @param String $memcacheGroup 設定ファイル上のグループ名 - 省略時:default
     * @return bool
     */
    public static function flush($memcacheGroup = "default") : bool
    {
        if (self::locConnect($memcacheGroup)) {
            return self::$hCache[$memcacheGroup]->flush();
        }
        return false;
    }

    private static function locConnect($memcacheGroup) : bool
    {
        // handleの確認 #1 - キー名
        if (!array_key_exists($memcacheGroup, self::$hCache)) {
            // コネクションを確立
            return self::locSubConnect($memcacheGroup);
        }
        // handleの確認 #2 - コネクションプールの実在
        if (empty(self::$hCache)) {
            // コネクションを確立
            return self::locSubConnect($memcacheGroup);
        }
        return is_object(self::$hCache[$memcacheGroup]);
    }

    // コネクションを確立
    private static function locSubConnect($memcacheGroup) : bool
    {
        $mConf = self::locGetMemcacheConf($memcacheGroup);
        if (empty($mConf)) {
            return false;
        }
        self::$hCache[$memcacheGroup] = new memcache();
        self::$hCache[$memcacheGroup]->pconnect($mConf['hostname'], $mConf['port']);
        return is_object(self::$hCache[$memcacheGroup]);
    }

    /**
     * 設定ファイルからmemcacheサーバの情報をを取得する
     *
     * @param String $memcacheGroup 設定ファイル上のグループ名
     * @return なし
     */
    private static function locGetMemcacheConf($memcacheGroup)
    {
        $CI =& get_instance();
        $CI->config->load('memcached', true);
        $mconf = $CI->config->item('memcached');
        if (array_key_exists($memcacheGroup, $mconf)) {
            return $mconf[$memcacheGroup];
        }
        return null;
    }
}
