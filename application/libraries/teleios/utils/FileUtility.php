<?php
namespace teleios\utils;

class FileUtility
{

    /**
     * フォルダ存在確認
     *
     * @param String $dirName 存在するフォルダ名
     * @param String $targetPath (省略可)フォルダを存在するディレクトリへのパス。$dirNameでフルパス指定するならば不要
     * @return boolean 成功時：TRUE、失敗時：FALSE
     */
    public static function isExistsDir($dirName, $targetPath = null)
    {
        if (!empty($targetPath)) {
            $dirName = $targetDir . "/" . $dirName;
        }
        if (!file_exists($dirName)) {
            return false;
        }
        if (!is_dir($dirName)) {
            return false;
        }
        return true;
    }

    /**
     * フォルダ作成
     *
     * @param String $dirName 作成するフォルダ名
     * @param String $targetPath (省略可)フォルダを作成するディレクトリへのパス。$dirNameでフルパス指定するならば不要
     * @return boolean 成功時：TRUE、失敗時：FALSE
     */
    public static function makeDir($dirName, $targetPath = null)
    {
        if (!empty($targetPath)) {
            $dirName = $targetDir . "/" . $dirName;
        }
        return mkdir($dirName);
    }

    /**
     * フォルダ削除
     *
     * @param String $dirName 削除するフォルダ名
     * @param String $targetPath (省略可)フォルダを削除するディレクトリへのパス。$dirNameでフルパス指定するならば不要
     * @return boolean 成功時：TRUE、失敗時：FALSE
     */
    public static function deleteDir($dirName, $targetPath = null)
    {
        if (!empty($targetPath)) {
            $dirName = $targetDir . "/" . $dirName;
        }
        return rmdir($dirName);
    }

    /**
     * ファイル生成
     *
     * @param String $fileName 生成するファイル名
     * @param String $targetPath (省略可)ファイルを生成するディレクトリへのパス。$fileNameでフルパス指定するならば不要
     * @return boolean 成功時：TRUE、失敗時：FALSE
     */
    public static function touch($fileName, $targetPath = null)
    {
        if (empty($targetPath)) {
            $fileName = $targetPath . "/". $fileName;
        }
        if (file_exists($fileName)) {
            return false;
        }
        return touch($fileName);
    }
}
