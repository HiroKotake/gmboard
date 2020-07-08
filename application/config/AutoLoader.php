<?php

/**
 * hooks/AutoLodaerクラス用定義 namespace未対応クラスを呼び出すための定義を連想配列として設定
 * @var array  クラス名をキーとして、対応するファイルの所在を値とする連想配列を定義する
 */
$autoLoderClasses = array(
    'MY_Model' => APPPATH . 'core' . DIRECTORY_SEPARATOR . 'MY_Model.php',
    'MY_Controller' => APPPATH .  'core' . DIRECTORY_SEPARATOR . 'MY_Controller.php',
    'CI_Model' => BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'Model.php',
    'CI_Controller' => BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'Controller.php',
    'Smarty_Autoloader' => APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'AutoLoader.php'
);
