<!--
-- Database.php
--
-- PHP file containing the specific database config for our project.
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-06-11 / Maximilian T. | Kontr0x
-->
<?php
    final class Database extends General{
        //Hostname or ip of database 
        private $dbHost = null;

        //Database name for active db
        private $dbName = null;

        //Crendetianls to use for active db connection (username)
        private $dbUser = null;

        //Crendetianls to use for active db connection (password)
        private $dbPassword = null;

        public function __construct(){}

        //Function to validate the properties values
        public function checkCompleteness(){
            if(!(empty($this->dbHost))){
                if(!(preg_match('/[a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b[a-zA-Z0-9()@:%_\+.~#?&=\/]+/', $this->dbHost))){
                    Logger::getFatalLogger()->log('CRITICAL', 'database host "'.$this->dHost.'" contains invalid characters');
                    exit();
                }
            } else{
                Logger::getFatalLogger()->log('CRITICAL', 'database host not set');
                exit();
            }

            if(!(empty($this->dbName))){
                if(!(preg_match('/[a-zA-Z0-9._-]+/',$this->dbName))){
                    Logger::getFatalLogger()->log('CRITICAL', 'database name "'.$this->dbName.'" contains invalid characters');
                    exit();
                }
            } else{
                Logger::getFatalLogger()->log('CRITICAL', 'database name not set');
                exit();
            }

            if(!(empty($this->dbUser))){
                if(!(preg_match('/[a-zA-Z0-9._-]+/',$this->dbUser))){
                    Logger::getFatalLogger()->log('CRITICAL', 'database user not set') ;
                    exit();
                }
            }else{
                Logger::getFatalLogger()->log('CRITICAL', 'database user "'.$this->dbUser.'" contains invalid characters');
                exit();
            }

            if($this->dbPassword==null){ //todo regex
                Logger::getFatalLogger()->log('CRITICAL', 'database password not set');
                exit();
            }
        }
        
        // prevent to get unserialized
        private function __wakeup(){}

        //prevent the instance from being cloned which would create a second instance of config
        private function __clone(){}
    }
?>