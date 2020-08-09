<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\libs\common\Game;

/**
 * ゲームページ関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class GamePage extends Game
{
    public $pubVar;

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
