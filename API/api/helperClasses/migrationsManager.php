<?php
    /**
     * migrationsManager.php
     * 
     * PHP file for making migrations.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    //Importing migrations
    foreach (scandir(ROOT . '/helperClasses/migrations'.'/') as $filename) {
        $path = ROOT . '/helperClasses/migrations'.'/'.$filename;
        if (is_file($path)) {
            require $path;
        }
    }

    class MigrationsManager{
        private $eM = null; // Variable to store entity manager in
        private $migrations = array();
        private $isUpMigrationPending = null;
        private $dbVersion = "";

        public function __construct(){
            Logger::getLogger()->log('DEBUG', 'Called migrations manager');
            // Getting entity manager for database access
            $this->eM = Bootstrap::getEntityManager();

            // Scanning all availible migrations files stored in /helperClasses/migrations/
            foreach (scandir(ROOT . '/helperClasses/migrations'.'/') as $filename) {
                $path = ROOT . '/helperClasses/migrations'.'/'.$filename;
                if (is_file($path)) {
                    // Adding a migration php file to the array that contains all fetched migration files
                    $this->migrations = array_merge($this->migrations, $this->getMigrationElementFromFilename($filename));
                }
            }
            // Sorting the migration version ascending for easier up and down grades
            ksort($this->migrations, SORT_NUMERIC);

            // Getting the current database version
            $this->dbVersion = $this->eM->getRepository('DbVersion')->findBy(array('id' => 1))[0]->getVersion();

            // Checking if the api and the database are running on the same version
            if (API_VERSION != $this->dbVersion) {
                $tmpVersionCheck = array(API_VERSION, $this->dbVersion);
                // Sorting the api, database version ascending to see which version is higher
                sort($tmpVersionCheck, SORT_NUMERIC);

                // If the database version is higher then die api version downgrading the database
                if ($tmpVersionCheck[0] == API_VERSION) {
                    Logger::getLogger()->log('INFO', 'DBVersion is newer then the api version. Down migration is pending');
                    $this->logVersions();
                    $this->isUpMigrationPending = false;
                // If the api version is higher then the database version updating the database
                } else {
                    Logger::getLogger()->log('INFO', 'DBVersion is older then the api version. Up migration is pending');
                    $this->logVersions();
                    $this->isUpMigrationPending = true;
                }
            // If the api and database are both on the same version continue the script
            } else {
                Logger::getLogger()->log('INFO', 'DBVersion is matching the api version. No action required');
                $this->logVersions();
            }
        }

        // Function that checks if an upgrade or downgrade is necessary
        public function doMigrations() {
            Logger::getLogger()->log('INFO', 'DBMigrations triggered');

            if ($this->isUpMigrationPending !== null) {
                if ($this->isUpMigrationPending) {
                    // Upgrading the database
                    Logger::getLogger()->log('DEBUG', 'DBUpMigration is starting');
                    $this->up();
                } else {
                    // Downgrading the database
                    Logger::getLogger()->log('DEBUG', 'DBDownMigration is starting');
                    $this->down();
                }
            } 
        }

        // Function that gets the version and the classname out of the filename
        private function getMigrationElementFromFilename($filename){
            // Get rid of the .php extention
            $filename = explode('.', $filename);
            // convert the first letter to uppercase to get a the valid class name
            $className = ucfirst($filename[0]);
            // taking out the numbers to put them together as version number
            $filename = explode('_', $filename[0]);
            $version = $filename[1];
            for($i = 2; $i < sizeof($filename); $i++){
                $version = $version.'.'.$filename[$i];
            }
            // returning a array with the version as key and classname as element 
            return array($version => $className);
        }

        
        private function up() {
            $migrationNext = $this->dbVersion == "0.0.1";
            $doMigration = $this->dbVersion == "0.0.1";
            while($migrationClass = current($this->migrations)) {
                $version = key($this->migrations);
                if ($migrationNext) {
                    $doMigration = true;
                } 
                if ($version == $this->dbVersion) {
                    $migrationNext= true;
                }
                if ($doMigration && $migrationNext) {
                    Logger::getLogger()->log("DEBUG", $version." update triggered");
                    (new $migrationClass())->up();
                    $this->updateDbVersion($version);
                }
                if ($version == API_VERSION) {
                    break;
                }
                next($this->migrations);
            }
        }

        private function down() {
            $tmpArray = array_reverse($this->migrations);
            $doMigrate = false;
            $version = "";
            while($migrationClass = current($tmpArray)) {
                $version = key($tmpArray);
                if ($doMigrate) {
                    $this->updateDbVersion($version);
                }
                if ($version == API_VERSION) {
                    break;
                }
                if ($doMigrate || $version == $this->dbVersion) {
                    $doMigrate = true;
                    Logger::getLogger()->log("DEBUG", $version." downgrade triggered");
                    (new $migrationClass())->down();
                }
                next($tmpArray);
            }

            if (!$doMigrate) {
                //ToDo: Please put the migration
            } else {
                if ($version != API_VERSION) {
                    $this->updateDbVersion(API_VERSION);
                }
            }
        }

        private function logVersions() {
            Logger::getLogger()->log('DEBUG', 'DBVersion: '.$this->dbVersion);
            Logger::getLogger()->log('DEBUG', 'APIVersion: '.API_VERSION);
        }

        private function updateDbVersion($newVersion){
            $dbVersion = $this->eM->find('DbVersion', '1');
            $dbVersion->setVersion($newVersion);
            $this->eM->flush();
        }
    }
?>