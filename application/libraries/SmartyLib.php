<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'third_party/smarty/libs/Smarty.class.php');

class SmartyLib extends Smarty
{

    public function __construct()
    {
        parent::__construct();

        // Define directories, used by Smarty:
        $this->setTemplateDir(APPPATH . 'views');
        $this->setCompileDir(APPPATH . 'cache/smarty_templates_cache');
        $this->setCacheDir(APPPATH . 'cache/smarty_cache');
    }

    /**
     * ユーザ用ビュー表示
     * UserAgnetにより PC用フルブラウザとSmartPhone用サブセットに応じたテンプレートに振り分けて表示する
     *
     * @param  string $template 表示対象のテンプレート名
     * @param  array  $data     テンプレートに適用するデータ配列
     * @return boolean $return  trueを指定することで、画面表示するのではなくテンプレートのスクリプトを返す
     */
    public function view(
        $template,
        $data = array(),
        $return = false
    ) {
        // テンプレート用引数セット
        foreach ($data as $key => $value) {
            $this->assign($key, $value);
        }
        // インスタンス取得
        $CI =& get_instance();
        // テンプレートファイル名補完
        $filePeace = explode('.', $template);
        $lastPeace = $filePeace[count($filePeace) - 1];
        if ($lastPeace != 'tpl') {
            $template .= '.tpl';
        }
        // インスタンス取得し、リクエスト元のブラウザのリファラからPCかスマフォを判定
        $browserType = 'full';
        if ($CI->agent->is_mobile()) {
            $browserType = 'sp';
        }
        // $returnの値により振り分けし、テンプレートに反映
        if (!$return) {
            if (method_exists($CI->output, 'append_output')) {
                $headerTemplate = $browserType . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.tpl';
                $footerTemplate = $browserType . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'footer.tpl';
                if (file_exists($headerTemplate)) {
                    $CI->output->append_output($this->fetch($headerTemplate));
                }
                $CI->output->append_output($this->fetch($browserType . DIRECTORY_SEPARATOR . $template));
                if (file_exists($footerTemplate)) {
                    $CI->output->append_output($this->fetch($footerTemplate));
                }
                return;
            }
            $CI->output->final_output($browserType . DIRECTORY_SEPARATOR . $template);
            return;
        }

        // デバッグ用
        return $this->fetch($browserType . DIRECTORY_SEPARATOR . $template);
    }


    /**
     * ユーザ用ビュー表示 （ヘッダ及びフッダーを除外）
     * UserAgnetにより PC用フルブラウザとSmartPhone用サブセットに応じたテンプレートに振り分けて表示する
     *
     * @param  string $template 表示対象のテンプレート名
     * @param  array  $data     テンプレートに適用するデータ配列
     * @return boolean $return  trueを指定することで、画面表示するのではなくテンプレートのスクリプトを返す
     */
    public function viewWithoutHeaderAndFooter (
        $template,
        $data = array(),
        $return = false
    ) {
        // テンプレート用引数セット
        foreach ($data as $key => $value) {
            $this->assign($key, $value);
        }
        // インスタンス取得
        $CI =& get_instance();
        // テンプレートファイル名補完
        $filePeace = explode('.', $template);
        $lastPeace = $filePeace[count($filePeace) - 1];
        if ($lastPeace != 'tpl') {
            $template .= '.tpl';
        }
        // インスタンス取得し、リクエスト元のブラウザのリファラからPCかスマフォを判定
        $browserType = 'full';
        if ($CI->agent->is_mobile()) {
            $browserType = 'sp';
        }
        // $returnの値により振り分けし、テンプレートに反映
        if (!$return) {
            if (method_exists($CI->output, 'append_output')) {
                $CI->output->append_output($this->fetch($browserType . DIRECTORY_SEPARATOR . $template));
                return;
            }
            $CI->output->final_output($browserType . DIRECTORY_SEPARATOR . $template);
            return;
        }

        // デバッグ用
        return $this->fetch($browserType . DIRECTORY_SEPARATOR . $template);
    }

    /**
     * 管理者用ビュー表示
     * @param  string $template 表示対象のテンプレート名
     * @param  array  $data     テンプレートに適用するデータ配列
     * @return boolean $return  trueを指定することで、画面表示するのではなくテンプレートのスクリプトを返す
     */
    public function adminView(
        $template,
        $data = array(),
        $return = false
    ) {
        // テンプレート用引数セット
        foreach ($data as $key => $value) {
            $this->assign($key, $value);
        }
        // インスタンス取得
        $CI =& get_instance();
        // テンプレートファイル名補完
        $filePeace = explode('.', $template);
        $lastPeace = $filePeace[count($filePeace) - 1];
        if ($lastPeace != 'tpl') {
            $template .= '.tpl';
        }
        // 管理者用ビューへ振り分け
        if (!$return) {
            if (method_exists($CI->output, 'append_output')) {
                $headerTemplate = 'administration' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.tpl';
                $footerTemplate = 'administration' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'footer.tpl';
                if (file_exists($headerTemplate)) {
                    $CI->output->append_output($this->fetch($headerTemplate));
                }
                $CI->output->append_output($this->fetch('administration' . DIRECTORY_SEPARATOR . $template));
                if (file_exists($footerTemplate)) {
                    $CI->output->append_output($this->fetch($footerTemplate));
                }
                return;
            }
            $CI->output->final_output('administration' . DIRECTORY_SEPARATOR . $template);
            return;
        }

        // デバッグ用
        return $this->fetch('administration'. DIRECTORY_SEPARATOR . $template);
    }
}
