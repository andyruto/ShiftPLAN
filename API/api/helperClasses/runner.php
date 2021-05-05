<?php
    /**
     * runner.php
     * 
     * PHP file containing executing function.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-05 / Maximilian T. | Kontr0x
     */

    abstract class Runner{

        //Method that calls class code to execute
        static public function run(){
            Main::logUrl();
            Main::run();
        }
    }
?>