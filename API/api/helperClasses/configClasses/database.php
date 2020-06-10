<!--
-- Database.php
--
-- PHP file containing the specific database config for our project.
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-06-10 / Maximilian T. | Kontr0x
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
            if($this->dbHost!=null&&$this->dbName!=null&&$this->dbUser!=null&&$this->dbPassword!=null):
                //todo regex checks
            endif;
        }

        //Getter function to get the database host
        //@return: Database host string
        public function getDatabaseHost() : string{
            return $this->dbHost;
        }

        //Getter function to get the database name
        //@return: Database name string
        public function getDatabaseName() : string{
            return $this->dbName;
        }

        //Getter function to get the database user
        //@return: Database user string
        public function getDatabaseUser() : string{
            return $this->dbUser;
        }

        //Getter function to get the database user password
        //@return: Database user password string
        public function getDatabasePassword() : string{
            return $this->dbPassword;
        }
        
        // prevent to get unserialized
        private function __wakeup(){}

        //prevent the instance from being cloned which would create a second instance of config
        private function __clone(){}
    }
?>