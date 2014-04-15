<?php

require_once('../app/util/AbstractFileHandler.php');
require_once('../conf/config.inc.php');
require_once('Error.php');
require_once('ErrorCodes.php');
require_once('../app/fixer/ErrorFixer.php');
require_once('../app/fixer/MissingKeyEntity.php');

class SyncService extends AbstractFileHandler
{
    private $sourceArray;

    public function sync(&$errors, $file)
    {
        $this->sourceArray = $this->getSourceFile($file);

        $this->validateFileNotExist($errors, $file);
        $this->validateKeys($errors, $file);
    }

    public function eraseDumpFiles(&$errors)
    {
        $dir = Config::$main_directory;
        $it = new RecursiveDirectoryIterator($dir);
        foreach(new RecursiveIteratorIterator($it) as $file) {
            if(is_file($file) && !SyncService::isPriorLanguage($file)) {
                if(!SyncService::isPriorFile($file)) {
                    array_push($errors,
                        new Error(ErrorCodes::$dump_file,
                            SyncService::getLangByFilePath($file->getPathName()),
                            $file->getPathName(),
                            "dump file was deleted")
                    );
                    if(Config::$auto_fix) {
                        unlink($file);
                    }
                }
            }
        }
    }

    private function validateFileNotExist(&$errors, $file)
    {
        foreach (Config::$additional_languages as $lang) {
            $foreignFileWithPath = $this->getForeignFileWithPath($file, $lang);
            $foreignPath = $this->getForeignPath($file, $lang);

            if(!is_file($foreignFileWithPath)) {
                if(Config::$auto_fix) {
                    ErrorFixer::writeNewLanguageFile($foreignPath, $file->getFilename(), $this->sourceArray);
                }
                array_push($errors,
                    new Error(ErrorCodes::$file_not_exist,
                        $lang,
                        $file->getFilename(),
                        "whole file generated")
                );
            }
        }
    }

    private function validateKeys(&$errors, $file)
    {
        foreach (Config::$additional_languages as $lang) {
            $foreignArray = $this->getForeignFileAsArray($file, $lang);
            if($foreignArray)
            {
                $misses = new MissingKeyEntity($this->getForeignFileWithPath($file, $lang));

                foreach ($this->sourceArray as $key=>$value) {
                    if(!array_key_exists($key, $foreignArray))
                    {
                        $misses->add($key, $value);
                        array_push($errors,
                            new Error(ErrorCodes::$key_not_exist,
                                $lang,
                                $file->getFilename(),
                                "key generated: [".$key."]"));
                    }
                }

                if(Config::$auto_fix && !$misses->isEmpty()) {
                    ErrorFixer::writeMissingKeys($misses);
                }
            }
        }
    }

    public static function isPriorFile($file)
    {
        $file = Config::$main_directory."\\".Config::$prior_language."\\".$file->getFileName();
        return is_file($file);
    }

    public static function getLangByFilePath($filePath)
    {
        $chopPos = strlen(Config::$main_directory)+1;
        $fileWithLang = substr($filePath, $chopPos, strlen($filePath));
        return strstr($fileWithLang, '\\', true);
    }

    public static function isPriorLanguage($file)
    {
        if (strpos($file, Config::$prior_language .'\\') !== FALSE)
        {
            return true;
        }
        return false;
    }

}