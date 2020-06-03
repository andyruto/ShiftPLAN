<!--
-- logger.php
--
-- PHP file containing the specific monolog implementation
-- for our project. It's a logger class.
--
-- author: Andreas G.
-- last edit / by: 2020-06-03 / Andreas G.
-->
<?php
    class Logger {
        private $logger;

        private function __construct() {
        }

        public static function putLogEntry(int $logType, string $message) {

        }

        public static function putLogEntry(string $logType, string $message) {

        }

        private function warning() {
            $logger->warning();

            Logger::putLogEntry();

            // create a log channel
            $log = new Monolog\Logger('name');
            $log->pushHandler(new Monolog\Handler\StreamHandler();

            // add records to the log
            $log->warning('Foo');
            $log->error('Bar');
        } 
    }
?>