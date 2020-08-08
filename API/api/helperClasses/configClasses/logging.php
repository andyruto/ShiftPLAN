<!--
-- Logging.php
--
-- PHP file containing the specific logging config for our project.
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-06-11 / Maximilian T. | Kontr0x
-->
<?php
    final class Logging extends General{

        //Default values as constants
        const PATH = '/logs'.'/';
        const LOG_LEVEL = 'DEBUG';
        const LOG_COUNT = 200;

        //Path to write log entries. (optinal)
        private $path = null;

        //Loglevel to define what type of log types should be logged. (optinal)
        //possible values {DEBUG, INFO, WARNING, ERROR, CRITICAL}
        private $logLevel = null;

        //Log count is the maximum of stored log files. 
        //If max value is exited old logs will be deleted
        private $logCount = null;

        public function __construct(){}

        //Function to validate the properties values
        public function checkCompleteness(){

            //Checking validity of the path with regex
            if(!(empty($this->path))){
                if(!(preg_match('/^\/\s*[a-zA-Z0-9\/\_\s\-]+\s*\/$/', $this->path))){
                    Logger::getFatalLogger()->log('CRITICAL', 'logging path "'.$this->path.'" contains invalid characters.');
                    exit();
                }
            }

            //Checking validity of the log level with default parameters
            if(!(empty($this->logLevel))){
                if(!(in_array($this->logLevel, array('DEBUG', 'INFO', 'WARNING', 'ERROR', 'CRITICAL')))){
                    Logger::getFatalLogger()->log('CRITICAL', 'logging Level "'.$this->logLevel.'" unknown parameter. Allowed parameters are "DEBUG, INFO, WARNING, ERROR, CRITICAL"');
                    exit();
                }
            }

            //Checking validity if log count with checking the value if above 0
            if(!(empty($this->logCount))){
                if(!($this->logCount>0)){
                    Logger::getFatalLogger()->log('CRITICAL', 'logging Count "'.$this->logCount.'" below minimum value (1).');
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