<?php
    /**
     * dbManager.php
     * 
     * PHP file containing class to manage the database.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    class DbManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;
        
        function __construct(){
            $this->eM = Bootstrap::getEntityManager();
        }

        public function syncDb(){
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->eM);
            $classes = $this->eM->getMetadataFactory()->getAllMetadata();
            $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

            if (sizeof($SchemaUpdate) == 0) {
                Logger::getLogger()->log('INFO', 'The database is synchronized. Nothing to do.');
                $this->syncDbVersion();
            } else {
                Logger::getLogger()->log('INFO', 'The database isn\'t synchronized. DB update will be triggered.');

                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->eM);
                $classes = $this->eM->getMetadataFactory()->getAllMetadata();
                $schemaTool->updateSchema($classes);

                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->eM);
                $classes = $this->eM->getMetadataFactory()->getAllMetadata();
                $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

                if (sizeof($SchemaUpdate) == 0) {
                    Logger::getLogger()->log('INFO', 'The database successfully synchronized.');
                    $this->syncDbVersion();
                    return $this->errorCode;
                } else {
                    Logger::getLogger()->log('ERROR', 'Error while validation of the database.' . '\n' . 'Please run \'php vendor/bin/doctrine orm:schema-tool:update\' manually.');
                    $this->errorCode = ErrorCode::DatabaseValidationFailed;
                    return $this->errorCode;
                }
            }
        }

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