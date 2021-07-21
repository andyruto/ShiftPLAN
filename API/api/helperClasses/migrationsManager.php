<?php
    /**
     * migrationsManager.php
     * 
     * PHP file for making migrations.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-23 / Maximilian T. | Kontr0x
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
        private $migrations = array(); // Array of available migrations
        private $isUpMigrationPending = null; // Property storing the state (up, down or no migration pending)
        private $dbVersion = ""; // Storing the current version of the database scheme

        //Constructor preparing everything for probably upcoming migrations
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

        //Function triggering all sql queries for upgrading to current version 
        private function up() {
            //Variable triggering the migration for the next loop round
            $migrationNext = $this->dbVersion == "0.0.1";

            //Variable triggering the migration in the current loop round
            $doMigration = $this->dbVersion == "0.0.1";

            //Looping over all migrations found in the migrations folder
            while($migrationClass = current($this->migrations)) {
                //Storing the current key containing the verison number only
                $version = key($this->migrations);

                //Check if the migration was triggered for the current round
                if ($migrationNext) {
                    $doMigration = true;
                } 

                //Trigger the migration for the next loop round if dbVersion reached
                if ($version == $this->dbVersion) {
                    $migrationNext= true;
                }

                //Execute the current migration's up queries
                if ($doMigration && $migrationNext) {
                    Logger::getLogger()->log("DEBUG", $version." update triggered");
                    (new $migrationClass())->up();
                    $this->updateDbVersion($version);
                }

                //Stop looping after reaching the api's version
                if ($version == API_VERSION) {
                    break;
                }

                //Step forward to the next migration field in array
                next($this->migrations);
            }
        }

        //Function triggering all sql queries for downgrading to current version 
        private function down() {
            //Buffering a migration array in reverse order because we downgrade
            $tmpArray = array_reverse($this->migrations);

            //Variable storing wether to execute the current down migration or not
            $doMigrate = false;
            $version = "";

            //Looping over the reverse migrations buffer
            while($migrationClass = current($tmpArray)) {
                //Storing the current key containing the verison number only
                $version = key($tmpArray);

                //Check if the migration was triggered for the current round and update the dbVersion
                if ($doMigrate) {
                    $this->updateDbVersion($version);
                }

                //Stop looping when reaching the current api version
                if ($version == API_VERSION) {
                    break;
                }

                //Triggering the downgrade if doMigrate true or reaching the buffer field with the current dbVersion
                if ($doMigrate || $version == $this->dbVersion) {
                    $doMigrate = true;
                    Logger::getLogger()->log("DEBUG", $version." downgrade triggered");
                    (new $migrationClass())->down();
                }

                //Step forward to next array field
                next($tmpArray);
            }

            //Check if migrations where made
            if (!$doMigrate) {
                //Log output for the admin
                Logger::getLogger()->log("ERROR", "The migration for the current db version is missing. Downgrade not possible.");
                Logger::getLogger()->log("ERROR", "Please make sure the migration files from ".API_VERSION." to ".$this->dbVersion." are included in the migration folder.");
                Logger::getLogger()->log("ERROR", "Migrations folder: /helperClasses/migrations/");
                Logger::getLogger()->log("ERROR", "Otherwise download the missing migrations from following link.");
                Logger::getLogger()->log("ERROR", "Download link: https://github.com/andyruto/ShiftPLAN/tree/master/API/api/helperClasses/migrations");
                Logger::getLogger()->log("ERROR", "Please run the api path / afterwards again.");

                //Exiting applicaton because migration are not possible
                $respond = array();
                sendOutput(ErrorCode::MigrationMissing, $respond);
                exit();
            } else {
                //Update the dbVersion one last time if migrations where made
                if ($version != API_VERSION) {
                    $this->updateDbVersion(API_VERSION);
                }
            }
        }

        //Logging the versions of the db and api for debugging purposes
        private function logVersions() {
            Logger::getLogger()->log('DEBUG', 'DBVersion: '.$this->dbVersion);
            Logger::getLogger()->log('DEBUG', 'APIVersion: '.API_VERSION);
        }

        //Update the dbVersion with the version provided as parameter
        private function updateDbVersion($newVersion){
            $dbVersion = $this->eM->find('DbVersion', '1');
            $dbVersion->setVersion($newVersion);
            $this->eM->flush();
        }
    }
?>