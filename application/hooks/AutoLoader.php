<?php

class AutoLoader
{

    public function __construct()
    {
    }

    public function autoLoad()
    {
        spl_autoload_register(array($this,'setAutoLoadByNamespace'));
    }

    public function setAutoLoadByNamespace(
        $class = "",
        $libPath = ["libraries/", "models/"]
    ) {
        $this->_autoload($class, $libPath);
    }

    private function _autoload (
        $className,
        array $libPath
    ) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $targetFiles = array();
            foreach ($libPath as $path) {
                $namespace = substr($className, 0, $lastNsPos);
                $className2 = substr($className, $lastNsPos + 1);
                $fileName  = APPPATH . $path . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
                $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className2) . '.php';
                $targetFiles[] = $fileName;
            }
            foreach ($targetFiles as $target) {
                if (file_exists($target)) {
                    require $target;
                    break;
                }
            }
            return;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        if (file_exists($fileName)) {
            require $fileName;
        }
    }
}
