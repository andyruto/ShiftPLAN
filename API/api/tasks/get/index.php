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
        private static $eM = null; //Variable to store entity manager in

        //Method invoked on script execution
        public static function run(){
            // Takes raw data from the request
            $rP = new RequestParser();
            $request = $rP->getBodyObject();
            //Looking for parameters
            if($rP->hasParameters(array('apiKey', 'session', 'id'))){
                if(preg_match(Validation::IdOfNumbers, $request->id)){
                    $ssM = new SslKeyManager();
                    //Decrypting the session
                    self::$errorCode = $ssM->aDecrypt($request->session);
                    //Checking if the decryption succeded
                    if(self::$errorCode == ErrorCode::NoError){
                        //Saving the decrytped session
                        $session = $ssM->getResult();
                        $sM = SessionManager::obj($session);
                        self::$errorCode = $sM->getFinishCode();
                        //Checking if the session manager succeded
                        if(self::$errorCode == ErrorCode::NoError){
                            //Checking execution rights
                            $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(Permission::TasksRead), $session);
                            $eC->check(false);
                            //Creating entity manager for db access
                            self::$eM = Bootstrap::getEntityManager();
                            //Searching for the task in database
                            $tM = TaskManager::obj($request->id);
                            self::$errorCode = $tM->getFinishCode();
                            if(self::$errorCode == ErrorCode::NoError){
                                //Formatting output
                                $taskArray = array('id' => $tM->getDbObject()->getId(), 'name' => $tM->getDbObject()->getName());
                                //Adding the found task to the output
                                self::$respondArray = array_merge(self::$respondArray, array('task' => $taskArray));
                            }
                        }
                    }
                }else{
                    Logger::getLogger()->log('ERROR', 'id failed regex validation');    
                    $finishCode = ErrorCode::ValidationFailed;
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
            Logger::getLogger()->log('INFO', 'Api path /tasks/get/ was called');
        }
    }
    Runner::run();
?>