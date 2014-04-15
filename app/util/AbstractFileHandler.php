<?php

require_once('../conf/config.inc.php');

abstract class AbstractFileHandler
{
    protected function getSourceFile($file)
    {
        return require_once $file->getPathname();
    }

    protected function getForeignFileAsArray($file, $lang)
    {
        $foreignPathname = str_replace(Config::$prior_language."\\", $lang."\\", $file->getPathname());
        if(is_file($foreignPathname)) {
            return require_once $foreignPathname;
        }
    }

    protected function getForeignFileWithPath($file, $lang)
    {
        return str_replace(Config::$prior_language."\\", $lang."\\", $file->getPathname());
    }

    protected function getForeignPath($file, $lang)
    {
        return str_replace(Config::$prior_language, $lang, $file->getPath());
    }
}