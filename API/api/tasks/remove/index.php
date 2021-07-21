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
                    //Checking execution rights
                    $eC = ExecutionChecker::apiKeyPermissionSessionUserTypeChecker($request->apiKey, array(Permission::TasksWrite), $session, 1);
                    $eC->check(false);
                    //Validating the given parameters
                    if(is_integer($request->id)){
                        $tM = TaskManager::obj($request->id);
                        self::$errorCode = $tM->getFinishCode();
                        if(self::$errorCode == ErrorCode::NoError){
                            self::$errorCode = $tM->removeTask();
                        }
                    }else{
                        self::$errorCode = ErrorCode::ValidationFailed;
                        Logger::getLogger()->log('ERROR', 'Id validation failed');
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
            Logger::getLogger()->log('INFO', 'Api path /task/remove/ was called');
        }
    }
    Runner::run();
?>
