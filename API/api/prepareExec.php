<!--
-- prepareExec.php
--
-- Script preparing all imports and requirements for
-- all the script executions.
--
-- author: Andreas G.
-- last edit / by: 2020-06-10 / Maximilian T. | Kontr0x
-->
<?php
    if(!defined('ROOT')) {
        define('ROOT', __DIR__);
    }
    
    require ROOT . '/vendor/autoload.php';

    foreach (scandir(ROOT . '/helperClasses'.'/') as $filename) {
        $path = ROOT . '/helperClasses'.'/'.$filename;
        if (is_file($path)) {
            require $path;
        }
    }
?>