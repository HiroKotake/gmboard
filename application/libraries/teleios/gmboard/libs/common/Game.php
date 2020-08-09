<?php
namespace teleios\gmboard\libs\common;

/**
 * ゲーム関連基本クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class Game extends GmbCommon
{
    public $gameid;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * デストラクタ
     */
    public function __destruct()
    {
    }
}
