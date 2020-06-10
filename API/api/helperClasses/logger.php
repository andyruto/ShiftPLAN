<!--
-- logger.php
--
-- PHP file containing the specific monolog implementation
-- for our project. It's a logger singleton class.
--
-- author: Andreas G.
-- last edit / by: 2020-06-10 / Andreas G.
-->
<?php
    final class Logger {
        //The static property to store the project logger object
        private static $logger = null;

        //The property used to store the monolog logger object
        private $monoLogger = null;

        //Constructor building the logger with all the information
        //required by monolog engine
        //@param $name: The name of the current logger in the log file.
        //@param $path: The path where the logger should log into.
        //@param $logLevel: The level considering what logType's should be written into log file
        private function __construct(string $path, int $logLevel = Monolog\Logger::DEBUG) {
            //Preparing stream handler with specific line format
            $dateFormat = "Y-m-d H:i:s";
            $output = "%datetime% > %level_name% > %message%\n";
            $formatter = new Monolog\Formatter\LineFormatter($output, $dateFormat);
            $streamHandler = new Monolog\Handler\StreamHandler($path, $logLevel);
            $streamHandler->setFormatter($formatter);

            //Preparing the monoLogger object for Logger class
            $this->monoLogger = new Monolog\Logger('shiftPLAN-API');
            $this->monoLogger->pushHandler($streamHandler);
        }

        //Preventing object from being cloned
        private function __clone() {
        }

        //Preventing object from being unserialized
        private function __wakeup() {
        }

        //Method checking if a static logger object already exists
        //otherwise calling the constructor.
        //@return: The singleton instance of Logger.
        public static function getLogger() : self {
            if (self::$logger == null) {
                self::$logger = new self(ROOT . '/logs/shiftPLAN-API.log', Monolog\Logger::DEBUG);
            }

            return self::$logger;
        }

        //Method checking if a static logger object already exists
        //otherwise calling the constructor.
        //@return: The singleton instance of Logger.
        public static function getFatalLogger() : self {
            if (self::$logger == null) {
                self::$logger = new self(ROOT . '/logs/shiftPLAN-FATAL.log', Monolog\Logger::DEBUG);
            }

            return self::$logger;
        }

        //Method analysing the logType for that entry and
        //calling the desired method.
        //@param $logType: The logType for that entry.
        //@param $message: The message to write for that entry
        public function log(string $logType, string $message) {
            if (method_exists($this, $logType)) {
                $this->$logType($message);
            } else {
                throw new InvalidArgumentException("The log type argument doesn't fit the possible values.");
            }
        }

        //Method creating a debug log entry
        //@param $message: The message to write for that entry 
        private function DEBUG(string $message) {
            $this->monoLogger->debug($message);
        }
        
        //Method creating a info log entry
        //@param $message: The message to write for that entry
        private function INFO(string $message) {
            $this->monoLogger->info($message);
        }

        //Method creating a warning log entry
        //@param $message: The message to write for that entry
        private function WARNING(string $message) {
            $this->monoLogger->warning($message);
        }

        //Method creating a error log entry
        //@param $message: The message to write for that entry
        private function ERROR(string $message) {
            $this->monoLogger->error($message);
        }

        //Method creating a critical log entry
        //@param $message: The message to write for that entry
        private function CRITICAL(string $message) {
            $this->monoLogger->critical($message);
        }
    }
?>