<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-06-08 / Maximilian T. | Kontr0x
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
            if($rP->hasParameters(array('apiKey', 'session', 'name'))){
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
                        $eC = ExecutionChecker::apiKeyPermissionSessionUserTypeChecker($request->apiKey, array(Permission::UserWrite), $session, 1);
                        $eC->check(false);
                        //Validating the given parameters
                        if(preg_match(Validation::UserName, $request->name)){
                            $uM = UserManager::obj($request->name);
                            self::$errorCode = $uM->getFinishCode();
                            if(self::$errorCode == ErrorCode::NoError){
                                $user = $uM->getDbObject();
                                $user->setHidden(1);
                                (Bootstrap::getEntityManager())->flush();
                                Logger::getLogger()->log('DEBUG', 'User with name '+$request->name+' set to hidden');
                            }
                        }else{
                            self::$errorCode = ErrorCode::ValidationFailed;
                            Logger::getLogger()->log('ERROR', 'Name validation failed');
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
            Logger::getLogger()->log('INFO', 'Api path /user/remove/ was called');
        }
    }
    Runner::run();
?>
