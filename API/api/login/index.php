<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-30 / Maximilian T. | Kontr0x
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
            if($rP->hasParameters(array('apiKey', 'userName')) && !$rP->hasParameters(array('chlgSolved', 'nonce'))){
                $eC = ExecutionChecker::apiKeyChecker($request->apiKey);
                $uM = UserManager::obj($request->userName);
                if($uM->getFinishCode() == ErrorCode::NoError){
                    //Checking if the user is hidden to hinder a login
                    if(!($uM->getDbObject())->getHidden()){
                        $eC->check($request->userName == 'admin');
                        $chlg = $uM->createChallenge();
                        //Adding the challenge to the respond
                        $respondArray = array_merge($respondArray, array('chlg' => $chlg));
                        Logger::getLogger()->log('DEBUG', 'appending challenge to output');
                    }else{
                        self::$errorCode = ErrorCode::UserIsHidden;
                    }
                } 
            }else{
                //Checking if request has parameters to validate challenge
                if($rP->hasParameters(array('apiKey', 'userName', 'chlgSolved', 'nonce'))){
                    $eC = ExecutionChecker::apiKeyChecker($request->apiKey);
                    $uM = UserManager::obj($request->userName);
                    if($uM->getFinishCode() == ErrorCode::NoError){
                        //Checking if user is hidden to hinder a login
                        if(!($uM->getDbObject())->getHidden()){
                            $eC->check($request->userName == 'admin');
                            //Validating the challenge
                            self::$errorCode = $uM->checkChallenge($request->chlgSolved, $request->nonce, $uM->getPasswordHash());
                            if(self::$errorCode == ErrorCode::NoError){
                                //Creating session mananger
                                $sM = SessionManager::creator();
                                self::$errorCode = $sM->createSession($request->userName);
                                if(self::$errorCode == ErrorCode::NoError){
                                    $nonce = SslKeyManager::getNonce();
                                    $ssM = new SslKeyManager();
                                    //Encrypting session for respond
                                    self::$errorCode = $ssM->sEncrypt(($sM->getDbObject())->getId(), $nonce, $uM->getPasswordHash());
                                    if(self::$errorCode == ErrorCode::NoError){
                                        $respondArray = array_merge($respondArray, array('session' => ($ssM->getResult()), 'nonce' => $nonce));
                                    }
                                }
                            }
                        }else{
                            self::$errorCode = ErrorCode::UserIsHidden;
                        }
                    }                
                }else{
                    //If request has parameters to login through a session or to check if session is still valid
                    if($rP->hasParameters(array('apiKey', 'session'))){
                        $ssM = new SslKeyManager();
                        //Decrypting the session
                        self::$errorCode = $ssM->aDecrypt($request->session);
                        //Checking if the decryption succeded
                        if(self::$errorCode == ErrorCode::NoError){
                            //Saving the decrytped session
                            $session = $ssM->getResult();
                            //Calling execution checker for validating parameters
                            $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(), $session);
                            $sM = SessionManager::obj($session);
                            self::$errorCode = $sM->getFinishCode();
                            //Checking if the session manager succeded
                            if(self::$errorCode == ErrorCode::NoError){
                                $userNameFromSession = $sM->getUserName();
                                $eC->check($userNameFromSession == 'admin');
                                $uM = UserManager::obj($userNameFromSession);
                                self::$errorCode = $uM->getFinishCode();
                                if(self::$errorCode == ErrorCode::NoError){
                                    //Adding the found users to the output
                                    $respondArray = array_merge($respondArray, array('userType' => $uM->getUserType()));
                                }
                            }
                        }
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