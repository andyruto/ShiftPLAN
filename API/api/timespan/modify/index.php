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
                    $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(), $session);
                    //Checking if execution privileges a granted
                    $eC->check(false);
                    //Creating a task manager for modifications
                    $tsM = TimeSpanManager::obj($request->id);
                    self::$errorCode = $tsM->getFinishCode();
                    if(self::$errorCode == ErrorCode::NoError){
                        //Creating session manager
                        $sM = SessionManager::obj($session);
                        self::$errorCode = $sM->getFinishCode();
                        //Checking if the session manager succeded
                        if(self::$errorCode == ErrorCode::NoError){
                            $arrayOfChanges = array('lastModifiedBy' => $sM->getUserName());
                            if($rP->hasParameters(array('appointedDay'))){
                                $arrayOfChanges['appointedDay'] = $request->appointedDay;
                            }
                            if($rP->hasParameters(array('start'))){
                                $arrayOfChanges['start'] = $request->start;
                            }
                            if($rP->hasParameters(array('end'))){
                                $arrayOfChanges['end'] = $request->end;
                            }
                            if($rP->hasParameters(array('requiredEmployees'))){
                                $arrayOfChanges['requiredEmployees'] = $request->requiredEmployees;
                            }
                            if($rP->hasParameters(array('connectedTaskId'))){
                                $arrayOfChanges['connectedTaskId'] = $request->connectedTaskId;
                            }
                            self::$errorCode = $tsM->modifyTimeSpan($arrayOfChanges);
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
            Logger::getLogger()->log('INFO', 'Api path /timespan/modify/ was called');
        }
    }
    Runner::run();
?>