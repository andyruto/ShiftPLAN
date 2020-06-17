<!--
-- logPool.php
--
-- LogPool class managing all log files in the log directory and
-- realizing the log rotation functionality.
--
-- author: Andreas G.
-- last edit / by: 2020-06-14 / Andreas G.
-->
<?php
    final class LogPool {
        //Array storing the log files in the log path
        private $logFiles = null;

        //Property storing the number of files in the logPath
        private $maxLogCount = 0;

        //Constructor preparing the log folder information
        public function __construct(string $logPath, int $logCount) {
            //Initialize the array with number of logs specified in config
            $this->maxLogCount = $logCount;

            //Build the resulting path string
            $logPathResult = null;

            if (preg_match('/^[a-zA-Z]?:?(?:\\|\/).*$/', $logPath, $matchInRow, PREG_UNMATCHED_AS_NULL) == 1) {
                if (ends_with($logPath, '/') || ends_with($logPath, '\\')) {
                    $logPathResult = $logPath;
                } else {
                    $logPathResult = $logPath . '/';
                }
            } else {
                if (ends_with($logPath, '/') || ends_with($logPath, '\\')) {
                    $logPathResult = ROOT . '' . $logPath;
                } else {
                    $logPathResult = ROOT . '' . $logPath . '/';
                }
            }


            foreach (scandir($logPathResult) as $filename) {
                $path = $logPathResult.$filename;
                
                if (is_file($path)) {
                }
            }
        }
    }
?>