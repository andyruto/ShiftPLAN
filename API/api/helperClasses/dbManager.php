<?php
    /**
     * dbManager.php
     * 
     * PHP file containing class to manage the database.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-23 / Maximilian T. | Kontr0x
     */

    class DbManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError; //The error code for the current instance
        
        //Constructor preparing the entity manager
        function __construct(){
            $this->eM = Bootstrap::getEntityManager();
        }

        //Sync method for the db making the db sync with the data model
        public function syncDb(){
            //Getting pending changes for the current db scheme
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->eM);
            $classes = $this->eM->getMetadataFactory()->getAllMetadata();
            $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

            //Checking if changes are pending
            if (sizeof($SchemaUpdate) == 0) {
                //No pending changes, just sync the DB version if required
                Logger::getLogger()->log('INFO', 'The database is synchronized. Nothing to do.');
                $this->syncDbVersion();
            } else {
                //There are pending changes
                Logger::getLogger()->log('INFO', 'The database isn\'t synchronized. DB update will be triggered.');

                //Do execute the required SQL queries to update the DB scheme
                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->eM);
                $classes = $this->eM->getMetadataFactory()->getAllMetadata();
                $schemaTool->updateSchema($classes);

                //Getting pending changes for the current db scheme
                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->eM);
                $classes = $this->eM->getMetadataFactory()->getAllMetadata();
                $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

                //Checking if changes are pending
                if (sizeof($SchemaUpdate) == 0) {
                    //No changes pending. Everything fine.
                    Logger::getLogger()->log('INFO', 'The database successfully synchronized.');
                    $this->syncDbVersion();
                } else {
                    //Something went wrong. Admin has to stop coffee break.
                    Logger::getLogger()->log('ERROR', 'Error while validation of the database.' . '\n' . 'Please run \'php vendor/bin/doctrine orm:schema-tool:update\' manually.');
                    $this->errorCode = ErrorCode::DatabaseValidationFailed;
                }
            }

            return $this->errorCode;
        }

        //Method updating the db version to be sync with the api version
        private function syncDbVersion(){
            $dbVersion = $this->eM->find('DbVersion', 1);
            if ($dbVersion == null) {
                $dbVersion = new DbVersion();
                $dbVersion->setVersion(API_VERSION);
                $this->eM->persist($dbVersion);
                $this->eM->flush();
            } else {
                $dbVersion->setVersion(API_VERSION);
                $this->eM->flush();
            }
        }
    }
?>