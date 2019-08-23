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
        $libPath = "libraries/"
    ) {
        $this->_autoload($class, $libPath);
    }

    private function _autoload (
        $className,
        $libPath
    ) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = APPPATH . $libPath . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        if (file_exists($fileName)) {
            require $fileName;
        }
    }
}
