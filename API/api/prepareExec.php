<!--
-- prepareExec.php
--
-- Script preparing all imports and requirements for
-- all the script executions.
--
-- author: Andreas G.
-- last edit / by: 2020-06-14 / Andreas G.
-->
<?php
    if(!defined('ROOT')) {
        define('ROOT', __DIR__);
    }

    //Function to fix new line error on fgets
    //@param $recource: source string to apply fix
    //@return: returning the fixed string
    function read_line($recource) : string {
        $buffer = fgets($recource);
        $buffer = str_replace("\n", "", $buffer);
        $buffer = str_replace("\r", "", $buffer);
        $buffer = str_replace("\r\n", "", $buffer);
        return $buffer;
    }

    //Function checking if string starts with a specific string
    //@param $string: The string to be checked.
    //@param $pattern: The pattern to search for in the string
    function starts_with(string $string, string $pattern) : bool {
        $length = strlen($pattern);

        return (substr($string, 0, $length) === $pattern);
    }

    //Function checking if string ends with a specific string
    //@param $string: The string to be checked.
    //@param $pattern: The pattern to search for in the string
    function ends_with(string $string, string $pattern) : bool {
        $length = strlen($pattern);

        if ($length == 0) {
            return true;
        }

        return (substr($string, -$length) === $pattern);
    }
    
    //Loading composer packages
    require ROOT . '/vendor/autoload.php';

    //Importing all classes from path
    foreach (scandir(ROOT . '/helperClasses'.'/') as $filename) {
        $path = ROOT . '/helperClasses'.'/'.$filename;
        if (is_file($path)) {
            require $path;
        }
    }
?>