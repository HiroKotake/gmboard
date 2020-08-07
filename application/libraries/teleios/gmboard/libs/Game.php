<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\libs\common\Personal;

/**
 * Game
 *
 * @access public
 * @author
 * @copyright Teleios All Rights Reserved
 * @category
 */
class Game extends Personal
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
