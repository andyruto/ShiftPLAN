<!--
-- prepareExec.php
--
-- Script preparing all imports and requirements for
-- all the script executions.
--
-- author: Andreas G.
-- last edit / by: 2020-08-08 / Maximilian T. | Kontr0x
-->
<?php
    if(!defined('ROOT')) {
        define('ROOT', __DIR__);
    }

    //Function to fix new line error on fgets
    //@param $recource: source string to apply fix
    //@return: returning the fixed string
    function read_line($recource) : string{
        $buffer = fgets($recource);
        $buffer = str_replace("\n", "", $buffer);
        $buffer = str_replace("\r", "", $buffer);
        $buffer = str_replace("\r\n", "", $buffer);
        return $buffer;
    }
    
    require ROOT . '/vendor/autoload.php'; //loading composer packages

    //Importing all classes from path
    foreach (scandir(ROOT . '/helperClasses'.'/') as $filename) {
        $path = ROOT . '/helperClasses'.'/'.$filename;
        if (is_file($path)) {
            require $path;
        }
    }

    require ROOT . '/src/bootstrap.php'; //loading main doctrine config class

?>