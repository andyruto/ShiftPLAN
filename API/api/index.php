<?php
    /**
     * index.php

     * Script checking the structure of the current api version. If
     * the structure is missing this script will provide the functionality
     * to create the api structure and DB. If it exists and is valid for
     * the current version it'll show a message.
     * 
     * author: Andreas G.
     * last edit / by: 2021-05-23 / Maximilian T. | Kontr0x
     */

    require 'prepareExec.php';

    //Including files from framework
    use Doctrine\DBAL\DBALException;

    //Class launching the main script checking the DB and API
    //and invoking the prepare script if required.
    final class Main extends ApiRunnable{
        private static $entityManager; //Static property storing the entity manager
        private static $respond; //Static property storing the respond to be sent to the client
        private static $errorCode; //Static property storing the errorCode during the execution

        //Method invoked on script execution
        public static function run() {
            //Initializing the static properties
            self::$entityManager = Bootstrap::getEntityManager();
            self::$respond = array();
            self::$errorCode = ErrorCode::NoError;

            //Try some actions with the entity manager and catch a possible exception
            try{
                //Check if dbVersion is existing in the database
                if(self::$entityManager->getRepository('dbVersion')->findAll()!=null){
                    //Check if migrations are pending
                    Logger::getLogger()->log("DEBUG", "DBVerson found. A database exists.");
                    (new MigrationsManager())->doMigrations();
                }else{
                    //No dbVersion. DB probably corrupted and should be synced
                    Logger::getLogger()->log("DEBUG", "No DBVersion found. Database should be corrupted or empty.");
                    $dbFinishCode = (new DbManager())->syncDb();
                }
            }catch (DBALException $e){
                //Exception is most likely caused by unsync db scheme. Trigger scheme sync
                Logger::getLogger()->log("DEBUG", "DBALException occured. Force a reset to the current DB scheme.");
                $dbFinishCode = (new DbManager())->syncDb();
            }

            //Check if the dbSync was successful
            if($dbFinishCode==ErrorCode::DatabaseValidationFailed){
                self::$errorCode = ErrorCode::DatabaseValidationFailed;
            }else{
                SslKeyManager::createOrReplaceKeyPair();
               
                $uM = UserManager::creator();
                $uM->createDefaultUser();

                //Checking if default user still uses the default password
                $defaultUserChangedPassword = (UserManager::obj('admin'))->getPasswordHash() != \Sodium\bin2hex(\Sodium\crypto_generichash('admin'));

                $aM = ApiKeyManager::creator();
                if($aM->createDefaultApiKey() || !$defaultUserChangedPassword){
                    $standardApiKey = self::$entityManager->getRepository('apiKey')->findBy(array('name' => "standardApiKey"))[0];
                    self::$respond = self::$respond + array('apiKey' => $standardApiKey->getId());
                }

                $dbM = new DbManager();
                self::$errorCode = $dbM->checkDb(true);
            }
            if(self::$errorCode != ErrorCode::NoError){
                self::$respond = array();
            }
            sendOutput(self::$errorCode, self::$respond);
            exit();
        }

        //Method invoked before script execution
        public static function logUrl(){
            //Logging the called script location
            Logger::getLogger()->log('INFO', 'Api path / was called');
        }
    }
    Runner::run();
?>