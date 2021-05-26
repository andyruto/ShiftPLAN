<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-26 / Maximilian T. | Kontr0x
     */

    require '../prepareExec.php';

    final class Main extends ApiRunnable{
        private static $errorCode = ErrorCode::NoError;

        //Method invoked on script execution
        public static function run(){
            $respondArray = array();
            // Takes raw data from the request
            $rP = new RequestParser();
            $request = $rP->getBodyObject();
            //Looking for parameters
            if($rP->hasParameters(array('apiKey', 'session'))){
                //Decrypting parameters
                $ssM = new SslKeyManager();
                self::$errorCode = $ssM->aDecrypt($request->session);
                if(self::$errorCode == ErrorCode::NoError){
                    //Getting decrypted parameter
                    $request->session = $ssM->getResult();
                    $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(), $request->session);
                    $sM = SessionManager::obj($request->session);
                    self::$errorCode = $sM->getFinishCode();
                    if(self::$errorCode == ErrorCode::NoError){
                        $userNameFromSession = $sM->getUserName();
                        $eC->check($userNameFromSession == 'admin');
                        $sM->removeSession();
                    }
                }
            }
            //Preparing output
            sendOutput(self::$errorCode, $respondArray);
            exit();
        }

        //Method invoked before script execution
        public static function logUrl(){
            //Logging the called script location
            Logger::getLogger()->log('INFO', 'Api path /login/ was called');
        }
    }
    Runner::run();
?>