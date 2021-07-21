<?php
    /**
     * Logging.php
     * 
     * PHP file containing the specific web server settings.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-17 / Maximilian T. | Kontr0x
     */

    final class Webserver extends General{

        //Default values as constants
        const SAME_ORIGIN = "true";

        //Same origin policy setting
        private $sameOrigin = null;

        public function __construct(){}

        //Function to print all variables with values of object
        public function printAllValues(){
            Logger::getLogger()->log('DEBUG','sameOrigin = '.$this->getValue('sameOrigin'));
            if($this->sameOrigin==null){
                Logger::getLogger()->log('DEBUG','Used default value for web server setting same origin policy');
            }
            if($this->sameOrigin == "false"){
                Logger::getLogger()->log("WARNING", "same origin policy disabled");
            }
        }

        //Function to validate the properties values
        public function checkCompleteness(){

            //Checking validity of value in same origin policy setting
            if(!(empty($this->sameOrigin))){
                if(!(preg_match('/(?:true)|(?:false)/', $this->sameOrigin))){
                    Logger::getFatalLogger()->log('CRITICAL', 'web server setting same origin value "'.$this->sameOrigin.'" is invalid.');
                    exit();
                }
            }
        }

        // prevent to get unserialized
        private function __wakeup(){}

        //prevent the instance from being cloned which would create a second instance of config
        private function __clone(){}
    }
?>