<?php
namespace teleios\utils;

/**
 * LangUtils
 * 多言語対応用ユーティリティーライブラリ
 *
 * CSVファイルの内容
 * メッセージ名称, 実際のメッセージ(’’で囲った文字列)
 * 表示するメッセージ内で置換したい文字列がある場合は、%で囲われた対象を置換対象とすることが出来る。
 * 置換対象は英数半角で構成する。
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category utility
 * @package teleios
 */
class LangUtils
{
    // constants
    const MODE_NORMAL = 0;
    const MODE_REDIS  = 1;
    const LANGAGE_DIR = __DIR__  . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'langages' . DIRECTORY_SEPARATOR;
    const CACHE_EXPIRE_TIME = 60 * 60 * 24;
    const CACHED_KEY_COMMON_CODE = 'MultiLangCommon';
    const CACHED_KEY_NAME = 'MultiLangCached';
    // class variables
    private $mode = null;
    private $langage = null;
    private $code = array();
    private $messages = array();
    private $commonCacheNumber = 0;

    /**
     * コンストラクタ
     * @param string $langage 表示する言語(言語ファイルのファイル名の拡張子を除いた部分(ディフォルト"japanese"))
     *                        言語ファイルは、このクラスの存在するフォルダの親フォルダ直下の"langages"フォルダ直下に配置する
     * @param int    $mode    0でインスタンス生成時にファイルからデータを読み込むモードとなり、1で初回のインスタンス生成時のみファイルから読み込み、次回移行はRedisサーバからデータを逐次読み込む形式になる。
     */
    public function __construct(string $langage = "japanese", int $mode = self::MODE_NORMAL)
    {
        $this->mode = $mode;
        $this->langage = $langage;
        $messageFile = self::LANGAGE_DIR . 'message.csv';
        $langageFile = self::LANGAGE_DIR . $langage . '.csv';
        try {
            $commonCacheFlag = true;
            $cachedFlag = true;
            if ($mode == self::MODE_REDIS) {
                $commonCacheFlag = RedisUtility::exist(self::CACHED_KEY_COMMON_CODE);
                if ($commonCacheFlag) {
                    // 共通コードの個数を取得する(確認用)
                    $this->commonCacheNumber = RedisUtility::get(self::CACHED_KEY_COMMON_CODE);
                }
                $cachedFlag = RedisUtility::exist(self::CACHED_KEY_NAME . $langage);
            }
            if ($mode == self::MODE_NORMAL || !$cachedFlag) {
                if (!file_exists($messageFile)) {
                    throw new \Exception("Message Code file is not exception!!", 1);
                }
                if (!file_exists($langageFile)) {
                    throw new \Exception("Langeage(" . $langage . ") file is not exception!!", 1);
                }
                // メッセージ対応コード読み込み
                if (($mFile = fopen($messageFile, "r")) !== FALSE) {
                    // CSV： 言語間共通名称,表示文字列
                    while(($data = fgetcsv($mFile, 0, ',', "'")) !== FALSE) {
                        $this->code[$data[0]] = $data[1];
                        // キャッシュ対応
                        if (self::MODE_REDIS && !$commonCacheFlag) {
                            RedisUtility::set(self::CACHED_KEY_COMMON_CODE . $data[0], $data[1]);
                            $this->commonCacheNumber += 1;
                        }
                    }
                    // キャッシュ対応
                    if ($mode == self::MODE_REDIS) {
                        RedisUtility::set(self::CACHED_KEY_COMMON_CODE, $this->commonCacheNumber);
                    }
                    fclose($mFile);
                }
                // 言語ファイル読み込み
                if (($hFile = fopen($langageFile, "r")) !== FALSE) {
                    // CSV： 言語間共通名称,表示文字列
                    while(($data = fgetcsv($hFile, 0, ',', "'")) !== FALSE) {
                        $this->messages[$data[0]] = $data[1];
                        // キャッシュ対応
                        if ($mode == self::MODE_REDIS) {
                            RedisUtility::set(self::CACHED_KEY_NAME . $langage . '_' . $data[0], $data[1]);
                        }
                    }
                    // キャッシュ対応
                    if ($mode == self::MODE_REDIS) {
                        RedisUtility::set(self::CACHED_KEY_NAME . $langage, 1);
                    }
                    fclose($hFile);
                }
            }
        } catch (\Exception $e) {
            echo 'Fatal Error: ', $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * 指定した表示メッセージに対応する文字列を取得する
     * @param  string $msgName  表示メッセージ名
     * @param  array  $replaces 表示するメッセージに代替文字列がある場合は対象文字列をキーとして、置換する文字列を値とした連想配列
     * @return string           対象の表示メッセージがある場合は指定された文字列を、存在しない場合は空文字を返す
     */
    public function getMessage(string $msgName, array $replaces = null) : string
    {
        if ($this->mode == self::MODE_REDIS) {
            $key = self::CACHED_KEY_NAME . $this->langage . '_' . $msgName;
            $this->messages[$msgName] = RedisUtility::get($key);
        }
        if (!in_array($msgName, array_keys($this->messages))) {
            return "";
        }
        if (!empty($replaces)) {
            $tempKeys = array();
            $tempWords = array();
            foreach ($replaces as $key => $var) {
                $tempKeys[] = '%' . $key . '%';
                $tempWords[] = $var;
            }
            return str_replace($tempKeys, $tempWords, $this->messages[$msgName]);
        }
        return $this->messages[$msgName];
    }

    /**
     * 指定した表示メッセージコードに対応する文字列を取得する
     * @param  int    $code     表示メッセージコード
     * @param  array  $replaces 表示するメッセージに代替文字列がある場合は対象文字列をキーとして、置換する文字列を値とした連想配列
     * @return string           対象の表示メッセージがある場合は指定された文字列を、存在しない場合は空文字を返す
     */
    public function getMessageByCode(int $code, array $replaces = null) : string
    {
        if ($this->mode == self::MODE_REDIS) {
            $this->code[$code] = RedisUtility::get(self::CACHED_KEY_COMMON_CODE . $code);
        }
        $msgName = $this->code[$code];
        return $this->getMessage($msgName, $replaces);
    }

    /**
     * テスト用：メッセージの全配列を取得する
     * @return array $this->messageを取得する
     */
    public function getMessageList() : array
    {
        return ['code' => $this->code, 'msg' => $this->messages];
    }

    /**
     * テスト用：メッセージの全配列を取得する（キャッシュ確認用)
     * @return array $this->messageを取得する
     */
    public function getAllFromCache() : array
    {
        $code = array();
        $message = array();
        if (count($this->code) <= 0) {
            for($i = 1 ; $i <= $this->commonCacheNumber; $i++) {
                $this->code[$i] = RedisUtility::get(self::CACHED_KEY_COMMON_CODE . $i);
            }
        }
        foreach ($this->code as $key => $value) {
            $code[$key] = $value;
            $message[$value] = $this->getMessageByCode($key);
        }
        return ['code' => $code, 'msg' => $message];
    }
}
