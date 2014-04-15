<?php

require_once('../conf/config.inc.php');

class Error
{
    var $fileName;
    var $language;
    var $errorCode;
    var $operation = "did nothing";

    public function Error($errorCode, $language, $file, $operation=null)
    {
        $this->errorCode = $errorCode;
        $this->language = $language;
        $this->fileName = $file;

        if(Config::$auto_fix) {
            $this->operation = $operation;
        }
    }


}