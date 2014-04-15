<?php

class Logger
{
    const LOG_LOCATION = "..\logs\log.txt";
    private $errors;
    private $header;

    function __construct()
    {
        $this->header = "\r\n\r\n";
        $this->header.= "------------  ";
        $this->header.= "Generated at: ".date("Y-m-d G:i:s");
        $this->header.= "  ------------";
        $this->header.= "\r\n";
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function log()
    {
        $str = $this->header.json_encode($this->getErrors(), JSON_PRETTY_PRINT);
        $logfile = file_get_contents(Logger::LOG_LOCATION);
        $str = $str.$logfile;
        file_put_contents(Logger::LOG_LOCATION, $str);
    }

    public function trace()
    {
        print_r($this->errors);
    }

    public function filesWereGood()
    {
        $str = $this->header." Nothing to do. File were syncronized";
        $logfile = file_get_contents(Logger::LOG_LOCATION);
        $str = $str.$logfile;
        file_put_contents(Logger::LOG_LOCATION, $str);
    }


}