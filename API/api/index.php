<?php
    /**
     * index.php

     * Script checking the structure of the current api version. If
     * the structure is missing this script will provide the functionality
     * to create the api structure and DB. If it exists and is valid for
     * the current version it'll show a message.
     * 
     * author: Andreas G.
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    require 'prepareExec.php';

    //Including files from framework
    use Doctrine\DBAL\DBALException;

    //Class launching the main script checking the DB and API
    //and invoking the prepare script if required.
    final class Main extends ApiRunnable{
        private static $entityManager;

        //Method invoked on script execution
        public static function run() {
            self::$entityManager = Bootstrap::getEntityManager();

            try{
                if(self::$entityManager->getRepository('dbVersion')->findAll()!=null){
                    Logger::getLogger()->log("DEBUG", "DBVerson found. A database exists.");
                    (new MigrationsManager())->doMigrations();
                }else{
                    Logger::getLogger()->log("DEBUG", "No DBVersion found. Database should be corrupted or empty.");
                    self::syncDb();
                }
            }catch (DBALException $e){
                Logger::getLogger()->log("DEBUG", "DBALException occured. Force a reset to the current DB scheme.");
                self::syncDb();
            }
            
            //ToDo: Run the db check manually (Not with Execution checker)

            //ToDo: Create a public private key pair

            //ToDo: Standard user if not exist

            //ToDo: Standard apikey if not exist


            if(self::$entityManager->getRepository('apiKey')->findAll()==null){
                //Creating new user
                $standarduser = new User();
                $standarduser->setName('admin');
                $standarduser->setPassword_hash('8122cba12b897aa5546baf90b6c82c9f646f976b3555033cbc5e0b72d4f7a5bc');
                //Storeing created user
                self::$entityManager->persist($standarduser);
                //Creating new api key
                $standardApiKey = new ApiKey();
                $standardApiKey->setid(generateRandomString(20));
                $standardApiKey->setName('standardApiKey');
                //Storeing created api key
                self::$entityManager->persist($standardApiKey);
                //Flushing changes
                self::$entityManager->flush();
                $respond = array('apiKey' => $standardApiKey->getId());
                sendOutput(ErrorCode::NoError, $respond);
                exit();
            }
        }

        //Method invoked before script execution
        public static function logUrl(){
            //Logging the called script location
            Logger::getLogger()->log('INFO', 'Api path / was called');
        }

        private static function syncDb(){
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool(self::$entityManager);
            $classes = self::$entityManager->getMetadataFactory()->getAllMetadata();
            $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

            if (sizeof($SchemaUpdate) == 0) {
                Logger::getLogger()->log('INFO', 'The database is synchronized. Nothing to do.');
                self::syncDbVersion();
            } else {
                Logger::getLogger()->log('INFO', 'The database isn\'t synchronized. DB update will be triggered.');

                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool(self::$entityManager);
                $classes = self::$entityManager->getMetadataFactory()->getAllMetadata();
                $schemaTool->updateSchema($classes);

                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool(self::$entityManager);
                $classes = self::$entityManager->getMetadataFactory()->getAllMetadata();
                $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

                if (sizeof($SchemaUpdate) == 0) {
                    Logger::getLogger()->log('INFO', 'The database successfully synchronized.');
                    self::syncDbVersion();
                } else {
                    Logger::getLogger()->log('ERROR', 'Error while validation of the database.' . '\n' . 'Please run \'php vendor/bin/doctrine orm:schema-tool:update\' manually.');
                    exit();
                }
            }
        }

        private static function syncDbVersion(){
            $dbVersion = self::$entityManager->getRepository('DbVersion')->findBy(array('id' => 1))[0];
            if ($dbVersion == null) {
                $dbVersion = new DbVersion();
                $dbVersion->setVersion(API_VERSION);
                self::$entityManager->persist($dbVersion);
            } else {
                $dbVersion->setVersion(API_VERSION);
                self::$entityManager->flush();
            }
        }
    }
    Runner::run();
?>