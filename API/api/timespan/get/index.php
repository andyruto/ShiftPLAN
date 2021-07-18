<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-07-18 / Maximilian T. | Kontr0x
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
                    $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(), $session);
                    $eC->check(false);
                    //Searching for the time span in database
                    $tsM = TimeSpanManager::obj($request->id);
                    self::$errorCode = $tsM->getFinishCode();
                    if(self::$errorCode == ErrorCode::NoError){
                        //Adding the found time span to the output
                        self::$respondArray = array_merge(self::$respondArray, $tsM->getTimeSpan());
                    }
                }
            }else{
                if($rP->hasParameters(array('apiKey', 'session', 'filter', 'value'))){
                    $ssM = new SslKeyManager();
                    //Decrypting the session
                    self::$errorCode = $ssM->aDecrypt($request->session);
                    //Checking if the decryption succeded
                    if(self::$errorCode == ErrorCode::NoError){
                        //Saving the decrytped session
                        $session = $ssM->getResult();
                        //Checking execution rights
                        $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(), $session);
                        $eC->check(false);
                        //Searching for the time span in database
                        $tsM = TimeSpanManager::handler();
                        $timeSpans = $tsM->searchTimeSpans($request->filter, $request->value);
                        self::$errorCode = $tsM->getFinishCode();
                        if(self::$errorCode == ErrorCode::NoError){
                            //Adding the found time span to the output
                            self::$respondArray = array_merge(self::$respondArray, $timeSpans);
                        }
                    }
                }
                else{
                    if($rP->hasParameters(array('apiKey', 'session', 'all'))){
                        $ssM = new SslKeyManager();
                        //Decrypting the session
                        self::$errorCode = $ssM->aDecrypt($request->session);
                        //Checking if the decryption succeded
                        if(self::$errorCode == ErrorCode::NoError){
                            //Saving the decrytped session
                            $session = $ssM->getResult();
                            //Checking execution rights
                            $eC = ExecutionChecker::apiKeyPermissionSessionUserTypeChecker($request->apiKey, array(), $session, 1);
                            $eC->check(false);
                            //Searching for the time span in database
                            $tsM = TimeSpanManager::handler();
                            $timeSpans = $tsM->getAllTimeSpans();
                            self::$errorCode = $tsM->getFinishCode();
                            if(self::$errorCode == ErrorCode::NoError){
                                //Adding the all time spans to the output
                                self::$respondArray = array_merge(self::$respondArray, $timeSpans);
                            }
                        }
                    }
                    else{
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
            Logger::getLogger()->log('INFO', 'Api path /timespan/get/ was called');
        }
    }
    Runner::run();
?>