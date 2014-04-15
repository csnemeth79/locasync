<?php

require_once('../app/util/StrAppender.php');

class ErrorFixer
{
    public static function writeNewLanguageFile($path, $name, $content)
    {
        if(!is_dir($path))
        {
            mkdir($path);
        }

        $out = new StrAppender();

        $out->append("<?php\r\n\r\n");
        $out->append("return array(");
        $out->append(ErrorFixer::convertArrayFirstDimension($content));
        $out->append("\r\n);");
        file_put_contents($path."\\".$name, $out->get());
    }

    public static function writeMissingKeys($misses)
    {
        $file = file_get_contents($misses->getFile());
        $file = ErrorFixer::removeLineBrakeOnEnd($file);
        $out = new StrAppender();
        $out->append($file);

        if(!ErrorFixer::hasCommaAtTheEnd(explode(");", $file)[0])) {
            $out->append(",");
        }

        $out->append(ErrorFixer::convertArrayFirstDimension($misses->getMissed()));
        $out->append("\r\n);");
        file_put_contents($misses->getFile(), $out->get());
    }

    private static function convertArrayFirstDimension($content)
    {
        $out = new StrAppender();
        foreach($content as $key=>$value)
        {
            if(is_array($value)) {
                $out->append("\r\n\t\"".$key."\" =>  array(");
                $out->append(ErrorFixer::convertArraySecondDimension($value));
                $out->append("\r\n\t),");
            } else {
                $out->append("\r\n\t\"".$key."\" => \"#".$value."\",");
            }
        }
        return $out->get();
    }

    private static function convertArraySecondDimension($content)
    {
        $out = new StrAppender();
        foreach($content as $key=>$value)
        {
            $out->append("\r\n\t\t\"".$key."\" => \"#".$value."\",");
        }
        return $out->get();
    }

    private static function hasCommaAtTheEnd($str)
    {
        return substr(trim(preg_replace('/\s\s+/', ' ', $str)), -1) == ",";
    }

    private static function hasLineBreakAtTheEnd($str)
    {
        return substr($str, -1) == "\n" || substr($str, -1) == "\r\n";
    }

    private static function removeLineBrakeOnEnd($file)
    {
        $file = explode(");", $file)[0];
        if(ErrorFixer::hasLineBreakAtTheEnd($file)) {
            return rtrim(rtrim($file, "\n"), "\r");
        }
        return $file;
    }


}
?>