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
            if($rP->hasParameters(array('apiKey', 'session', 'appointedDay', 'start', 'end', 'requiredEmployees', 'connectedTaskId'))){
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
                    //Creating session manager
                    $sM = SessionManager::obj($session);
                    self::$errorCode = $sM->getFinishCode();
                    //Checking if the session manager succeded
                    if(self::$errorCode == ErrorCode::NoError){
                        $tsM = TimeSpanManager::creator();
                        $uM = UserManager::obj($sM->getUserName());
                        self::$errorCode = $uM->getFinishCode();
                        if(self::$errorCode == ErrorCode::NoError){
                            //Creating time span
                            self::$errorCode = $tsM->createTimeSpan($request->appointedDay, $request->start, $request->end, $request->requiredEmployees, $uM->getDbObject()->getId(), $request->connectedTaskId);
                            if(self::$errorCode == ErrorCode::NoError){
                                Logger::getLogger()->log('INFO', 'Time span was created');
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
            Logger::getLogger()->log('INFO', 'Api path /timespan/create/ was called');
        }
    }
    Runner::run();
?>