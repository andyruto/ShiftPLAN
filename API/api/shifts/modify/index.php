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
                    $eC = ExecutionChecker::apiKeyPermissionSessionUserTypeChecker($request->apiKey, array(Permission::ShiftWrite), $session, 1);
                    //Checking if execution privileges a granted
                    $eC->check(false);
                    //Creating a task manager for modifications
                    $sfM = ShiftManager::obj($request->id);
                    self::$errorCode = $sfM->getFinishCode();
                    if(self::$errorCode == ErrorCode::NoError){
                        //Creating session manager
                        $sM = SessionManager::obj($session);
                        self::$errorCode = $sM->getFinishCode();
                        //Checking if the session manager succeded
                        if(self::$errorCode == ErrorCode::NoError){
                            $uM = UserManager::obj($sM->getUserName());
                            self::$errorCode = $uM->getFinishCode();
                            if(self::$errorCode == ErrorCode::NoError){
                                $arrayOfChanges = array('lastModifiedBy' => $uM->getDbObject()->getId());
                                if($rP->hasParameters(array('assignedUser'))){
                                    $arrayOfChanges['assignedUser'] = $request->assignedUser;
                                }
                                if($rP->hasParameters(array('supervisorUser'))){
                                    $arrayOfChanges['supervisorUser'] = $request->supervisorUser;
                                }
                                if($rP->hasParameters(array('connectedTaskId'))){
                                    $arrayOfChanges['connectedTaskId'] = $request->connectedTaskId;
                                }
                                if($rP->hasParameters(array('shiftStart'))){
                                    $arrayOfChanges['shiftStart'] = $request->shiftStart;
                                }
                                if($rP->hasParameters(array('shiftEnd'))){
                                    $arrayOfChanges['shiftEnd'] = $request->shiftEnd;
                                }
                                if($rP->hasParameters(array('comment'))){
                                    $arrayOfChanges['comment'] = $request->comment;
                                }
                                self::$errorCode = $sfM->modifyShift($arrayOfChanges);
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
            Logger::getLogger()->log('INFO', 'Api path /shifts/modify/ was called');
        }
    }
    Runner::run();
?>