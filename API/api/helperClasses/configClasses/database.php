<?php
    /**
     * Database.php
     * 
     * PHP file containing the specific database config for our project.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-04-24 / Maximilian T. | Kontr0x
     */

    final class Database extends General{

        //Default values as constants
        const DB_PORT = 0;
        
        //Hostname or ip of database 
        private $dbHost = null;

        //Port for host
        private $dbPort = null;

        //Database name for active db
        private $dbName = null;

        //Crendetianls to use for active db connection (username)
        private $dbUser = null;

        //Crendetianls to use for active db connection (password)
        private $dbPassword = null;

        public function __construct(){}

        //Function to print all variables with values of object
        public function printAllValues(){
            Logger::getLogger()->log('DEBUG','dbHost = '.$this->getValue('dbHost'));
            if($this->dbPort != 0){Logger::getLogger()->log('DEBUG','dbPort = '.$this->getValue('dbPort'));}
            else{Logger::getLogger()->log('DEBUG', 'database port set to 0 using default port');} 
            Logger::getLogger()->log('DEBUG','dbName = '.$this->getValue('dbName'));
            Logger::getLogger()->log('DEBUG','dbUser = '.$this->getValue('dbUser'));
            Logger::getLogger()->log('DEBUG','dbPassword = '.$this->getValue('dbPassword'));
        }
        
        //Function to validate the properties values
        public function checkCompleteness(){
            if(!(empty($this->dbHost))){
                if(!(preg_match('/(?:^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})|(?=^(?:.){1,255}$)^(?:(?:(?:[-_a-zA-Z0-9])+(?:.)?)+(?:[-_a-zA-Z0-9])+$)/', $this->dbHost))){
                    Logger::getFatalLogger()->log('CRITICAL', 'database host "'.$this->dbHost.'" contains invalid characters');
                    exit();
                }
            } else{
                Logger::getFatalLogger()->log('CRITICAL', 'database host not set');
                exit();
            }

            if($this->dbPort != 0){
                if(!(preg_match('/^(?:[0-9]{1,4}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5])$/', $this->dbPort))){
                    Logger::getFatalLogger()->log('CRITICAL', 'database port "'.$this->dbPort.'" contains invalid characters');
                    exit();
                }
            }

            if(!(empty($this->dbName))){
                if(!(preg_match('/^[A-Za-z0-1_$#]+$/',$this->dbName))){
                    Logger::getFatalLogger()->log('CRITICAL', 'database name "'.$this->dbName.'" contains invalid characters');
                    exit();
                }
            } else{
                Logger::getFatalLogger()->log('CRITICAL', 'database name not set');
                exit();
            }

            if(!(empty($this->dbUser))){
                if(!(preg_match('/^[A-Za-z0-1_.]+$/',$this->dbUser))){
                    Logger::getFatalLogger()->log('CRITICAL', 'database user "'.$this->dbUser.'" contains invalid characters');
                    exit();
                }
            }else{
                Logger::getFatalLogger()->log('CRITICAL', 'database user not set') ;
                exit();
            }

            if($this->dbPassword==null){
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