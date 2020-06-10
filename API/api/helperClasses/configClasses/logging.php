<!--
-- Logging.php
--
-- PHP file containing the specific logging config for our project.
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-06-10 / Maximilian T. | Kontr0x
-->
<?php
    final class Logging extends General{

        //Default values as constants
        const PATH = '/logs'.'/';
        const LOG_LEVEL = 'DEBUG';
        const LOG_COUNT = 200;

        //Path to write log entries. (optinal)
        private $path = self::PATH;

        //Loglevel to define what type of log types should be logged. (optinal)
        //possible values {DEBUG, INFO, WARNING, ERROR, CRITICAL}
        private $logLevel = self::LOG_LEVEL;

        //Log count is the maximum of stored log files. 
        //If max value is exited old logs will be deleted
        private $logCount = self::LOG_COUNT;

        public function __construct(){}

        //Function to validate the properties values
        public function checkCompleteness(){
            //todo regex
        }

        //Getter function to get the logging path
        //@return: Logging path string
        public function getLoggingPath() : string{
            return $this->path;
        }

        //Getter function to get the log level
        //@return: Log level string
        public function getLoggingLevel() : string{
            return $this->logLevel;
        }

        //Getter function to get the log count to set a maximum of log files
        //@return: Log count int
        public function getlogCount() : int{
            return $this->logCount;
        }
        
        // prevent to get unserialized
        private function __wakeup(){}

        //prevent the instance from being cloned which would create a second instance of config
        private function __clone(){}
    }
?>