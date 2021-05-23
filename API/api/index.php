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
        private static $entityManager;
        private static $respond;
        private static $errorCode;

        //Method invoked on script execution
        public static function run(){
            self::$entityManager = Bootstrap::getEntityManager();
            self::$respond = array();
            self::$errorCode = ErrorCode::NoError;

            try{
                if(self::$entityManager->getRepository('dbVersion')->findAll()!=null){
                    Logger::getLogger()->log("DEBUG", "DBVerson found. A database exists.");
                    (new MigrationsManager())->doMigrations();
                }else{
                    Logger::getLogger()->log("DEBUG", "No DBVersion found. Database should be corrupted or empty.");
                    $dbFinishCode = (new DbManager())->syncDb();
                }
            }catch (DBALException $e){
                Logger::getLogger()->log("DEBUG", "DBALException occured. Force a reset to the current DB scheme.");
                $dbFinishCode = (new DbManager())->syncDb();
            }

            if($dbFinishCode==ErrorCode::DatabaseValidationFailed){
                self::$errorCode = ErrorCode::DatabaseValidationFailed;
            }else{
                //ToDo: Run the db check manually (Not with Execution checker)
    
                SslKeyManager::createOrReplaceKeyPair();
               
                UserManager::createDefaultUser();
    
                if((ApiKeyManager::creator())->createDefaultApiKey()){
                    $standardApiKey = self::$entityManager->getRepository('apiKey')->findBy(array('name' => "standardApiKey"))[0];
                    self::$respond = self::$respond + array('apiKey' => $standardApiKey->getId());
                }
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