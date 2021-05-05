<?php
    /**
     * apiRunable.php
     * 
     * PHP file containing functions to create a clear class structure.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-05 / Maximilian T. | Kontr0x
     */

    abstract class ApiRunnable{

        //Function to execute code from class
        abstract static public function run();

        //Function to log the called api URL
        abstract static public function logUrl();
    }
?>