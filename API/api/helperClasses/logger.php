<!--
-- logger.php
--
-- PHP file containing the specific monolog implementation
-- for our project. It's a logger singleton class.
--
-- author: Andreas G.
-- last edit / by: 2020-06-08 / Andreas G.
-->
<?php
    class Logger {
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
            $this->monoLogger = new Monolog\Logger('shiftPLAN-API');
            $this->monoLogger->pushHandler(new Monolog\Handler\StreamHandler($path, $logLevel));
        }

        //Method checking if a static logger object already exists
        //otherwise calling the constructor.
        //@return: The singleton instance of Logger.
        public static function getLogger() : self {
            if (self::$logger == null) {
                self::$logger = new self('./logs/shiftPLAN-API.log', Monolog\Logger::DEBUG);
            }

            return self::$logger;
        }

        //Method analysing the logType for that entry and
        //calling the desired method.
        //@param $logType: The logType for that entry.
        //@param $message: The message to write for that entry
        public function putLogEntry(int $logType, string $message) {
            switch ($logType) {
                case Monolog\Logger::DEBUG:
                    $this->DEBUG($message);
                break;
                case Monolog\Logger::INFO:
                    $this->INFO($message);
                break;
                case Monolog\Logger::WARNING:
                    $this->WARNING($message);
                break;
                case Monolog\Logger::ERROR:
                    $this->ERROR($message);
                break;
                case Monolog\Logger::CRITICAL:
                    $this->CRITICAL($message);
                break;
                default:
                    throw new InvalidArgumentException();
                break;
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