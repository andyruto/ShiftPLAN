<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-06-30 / Maximilian T. | Kontr0x
     */

    require '../../prepareExec.php';

    final class Main extends ApiRunnable{
        private static $errorCode = ErrorCode::NoError;
        private static $respondArray = array();

        //Method invoked on script execution
        public static function run(){
            // Takes raw data from the request
            $rP = new RequestParser();
            $request = $rP->getBodyObject();
            //Looking for parameters
            if($rP->hasParameters(array('apiKey', 'session', 'id'))){
                $ssM = new SslKeyManager();
                //Decrypting the session
                self::$errorCode = $ssM->aDecrypt($request->session);
                //Checking if the decryption succeded
                if(self::$errorCode == ErrorCode::NoError){
                    //Saving the decrytped session
                    $session = $ssM->getResult();
                    $eC = ExecutionChecker::apiKeyPermissionSessionUserTypeChecker($request->apiKey, array(Permission::TasksWrite), $session, 1);
                    //Checking if execution privileges a granted
                    $eC->check(false);
                    //Creating a task manager for modifications
                    $tM = TaskManager::obj($request->id);
                    self::$errorCode = $tM->getFinishCode();
                    if(self::$errorCode == ErrorCode::NoError){
                        $task = $tM->getDbObject();
                        if($rP->hasParameters(array('name'))){
                            Logger::getLogger()->log('DEBUG', 'found name in request');
                            if(preg_match(Validation::NameOfNumbersAndCharacters, $request->name)){
                                Logger::getLogger()->log('INFO', 'Changing value of name for task '.$task->getName());
                                $task->setName($request->name);
                                (Bootstrap::getEntityManager())->flush();
                            }else{
                                self::$errorCode = ErrorCode::ValidationFailed;
                                self::$respondArray = array_merge(self::$respondArray,array('name'));
                            }
                        }
                    }
                }
            }else{
                self::$errorCode = ErrorCode::ParameterMissmatch;
                Logger::getLogger()->log('WARNING', 'Parameters doenst match function requirements');
            }
            //Preparing output
            sendOutput(self::$errorCode, self::$respondArray);
            exit();
        }

        //Method invoked before script execution
        public static function logUrl(){
            //Logging the called script location
            Logger::getLogger()->log('INFO', 'Api path /task/modify/ was called');
        }
    }
    Runner::run();
?>