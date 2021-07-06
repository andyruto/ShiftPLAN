<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-07-05 / Maximilian T. | Kontr0x
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
            if($rP->hasParameters(array('apiKey', 'session', 'filter', 'value'))){
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
                        $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(Permission::UserRead), $session);
                        $eC->check(false);
                        //Creating entity manager for db access
                        self::$eM = Bootstrap::getEntityManager();
                        //Validating the given filter
                        if(preg_match(Validation::UserFilters, $request->filter)){
                            $uM = UserManager::obj($sM->getUserName());
                            $users = array();
                            //Searching for users in database with filter and adding the found users to the output
                            if($request->filter == "all"){
                                if(preg_match(Validation::AllUserReducedFilters, $request->value)){
                                    Logger::getLogger()->log('DEBUG', 'Searching for all users with reduced output');
                                    $users = $uM->getUsers($request->value);
                                    self::$errorCode = $uM->getFinishCode();
                                }else{
                                    if(preg_match(Validation::AllUserFilters, $request->value)){
                                        Logger::getLogger()->log('DEBUG', 'Searching for all users with complete output');
                                        $users = $uM->getUsersWithFilter($request->filter, $request->value);
                                        self::$errorCode = $uM->getFinishCode();
                                    }else{
                                        Logger::getLogger()->log('ERROR', 'Searching for all users with a undefined value');
                                        self::$errorCode = ErrorCode::UnknownValue;
                                    }
                                }
                            }else{
                                Logger::getLogger()->log('DEBUG', 'Searching for users with filter '.$request->value);
                                $users = $uM->getUsersWithFilter($request->filter, $request->value);
                                self::$errorCode = $uM->getFinishCode();
                            }
                            if(self::$errorCode == ErrorCode::NoError){
                                self::$respondArray = array_merge(self::$respondArray, $users);
                            }
                        }else{
                            self::$errorCode = ErrorCode::ValidationFailed;
                            Logger::getLogger()->log('ERROR', 'Filter validation failed');
                        }
                    }
                }
            }else{
                if($rP->hasParameters(array('apiKey', 'session'))){
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
                            $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(), $session);    
                            $eC->check(false);
                            $uM = UserManager::obj($sM->getUserName());
                            self::$respondArray = array_merge(self::$respondArray, $uM->getUserProfile());
                            self::$errorCode = $uM->getFinishCode();
                        }
                    }else{
                        self::$errorCode = ErrorCode::ParameterMissmatch;
                        Logger::getLogger()->log('WARNING', 'Parameters doenst match function requirements');
                    }
                }
            }
            //Preparing output
            sendOutput(self::$errorCode, self::$respondArray);
            exit();
        }

        //Method invoked before script execution
        public static function logUrl(){
            //Logging the called script location
            Logger::getLogger()->log('INFO', 'Api path /user/get/ was called');
        }
    }
    Runner::run();
?>
