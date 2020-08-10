<?php
/**
 * logPool.php
 * 
 * LogPool class managing all log files in the log directory and
 * realizing the log rotation functionality.
 * 
 * author: Andreas G.
 * last edit / by: 2020-08-10 / Maximilian T. | Kontr0x 
 */

    final class LogPool {

        //Array storing the log files in the log path
        private $logFiles = array();

        //Property storing the number of files in the logPath
        private $maxLogCount = 0;

        //Property storing the log path provided at object constuction
        private $logPath = '';

        //Constructor preparing the log folder information
        public function __construct(string $logPath, int $logCount) {
            //Initialize the properties
            $this->maxLogCount = $logCount;
            $this->logPath = $logPath;

            //Build the resulting path string
            $logPathResult = null;

            //Prepare the path string for usage
            if (starts_with($logPath, '/') || starts_with($logPath, '\\')) {
                if (ends_with($logPath, '/') || ends_with($logPath, '\\')) {
                    $logPathResult = ROOT . '' . $logPath;
                } else {
                    $logPathResult = ROOT . '' . $logPath . '/';
                }
            } else {
                if (ends_with($logPath, '/') || ends_with($logPath, '\\')) {
                    $logPathResult = ROOT . '/' . $logPath;
                } else {
                    $logPathResult = ROOT . '/' . $logPath . '/';
                }
            }

            $this->logPath = $logPathResult;
            if(!is_dir($this->logPath)){
                createWebHiddenFolder(ROOT . '/logs/');
            }
            //Refreshing the logFiles array
            $this->refreshLogFilesArray();
        }
        
        //Method returning the active and valid log file
        //for the current execution.
        //@return: The full log path for the current log file
        public function getActiveLogFileString() : string {
            if (sizeof($this->logFiles) < $this->maxLogCount) {
                //Create a new log file with unique name
                $destLogFile = new File($this->logPath, preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()) . '.log');

                for ($i = 0; $i < sizeof($this->logFiles); $i++) {
                    if ($destLogFile->getFullPath() == $this->logFiles[$i]->getFullPath()) {
                        $destLogFile = new File($this->logPath, preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()) . '.log');
                        $i = 0;
                    }
                }

                //Create the log folder if not exists. Adding a .htaccess file for denying log access
                //over the web server.
                createWebHiddenFolder($destLogFile->getPath());

                return $destLogFile->getFullPath();
            } else {
                //Delete the oldest log file if max log count is reached
                $oldest = $this->logFiles[0];

                for ($i = 0; $i < sizeof($this->logFiles); $i++) {
                    if (intval($this->logFiles[$i]->getFileName()) < intval($oldest->getFileName())) {
                        $oldest = $this->logFiles[$i];
                    }
                }

                unlink($oldest->getFullPath());
                $this->refreshLogFilesArray();
                return $this->getActiveLogFileString();
            }
        }

        //Method refreshing the logFiles array list property
        private function refreshLogFilesArray() {
            $this->logFiles = array();

            //Scan all the current files in the path to an file object array
            foreach (scandir($this->logPath) as $filename) {                
                if (is_file($this->logPath . '' . $filename) && $filename != '.htaccess') {
                    array_push($this->logFiles, new File($this->logPath, $filename));
                }
            }
        }
    }
?>