<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-06-29 / Maximilian T. | Kontr0x
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
            if($rP->hasParameters(array('apiKey', 'session'))){
                $ssM = new SslKeyManager();
                //Decrypting the session
                self::$errorCode = $ssM->aDecrypt($request->session);
                //Checking if the decryption succeded
                if(self::$errorCode == ErrorCode::NoError){
                    //Saving the decrytped session
                    $session = $ssM->getResult();
                    $sM = SessionManager::obj($session);
                    if($rP->hasParameters(array('user')) && !$rP->hasParameters(array('nonce'))){
                        $eC = ExecutionChecker::apiKeyPermissionSessionUserTypeChecker($request->apiKey, array(Permission::UserWrite), $session, 1);
                        //Checking if execution privileges a granted
                        $eC->check($sM->isAdmin());
                        Logger::getLogger()->log('DEBUG', 'modifying users with admin rights');
                        $isAdmin = true;
                    }else{
                        $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(), $session);
                        //Checking if execution privileges a granted
                        $eC->check($sM->isAdmin());
                        Logger::getLogger()->log('DEBUG', 'modifying users without admin rights');
                        $isAdmin = false;
                    }
                    self::$errorCode = $sM->getFinishCode();
                    //Checking the session manager succesfully found a valid session
                    if(self::$errorCode == ErrorCode::NoError){
                        //Creating a user manager for modifications
                        if($isAdmin && $rP->hasParameters(array('user'))){
                            $uM = UserManager::obj($request->user);
                        }else{
                            $uM = UserManager::obj($sM->getUserName());
                        }
                        self::$errorCode = $uM->getFinishCode();
                        //Checking if the user manager was successfull finding a valid user
                        if(self::$errorCode == ErrorCode::NoError){
                            $user = $uM->getDbObject();
                            if($rP->hasParameters(array('nonce'))){
                                Logger::getLogger()->log('DEBUG', 'found nonce in request');
                                if($rP->hasParameters(array('password'))){
                                    Logger::getLogger()->log('DEBUG', 'found password in request');
                                    self::$errorCode = $ssM->sDecrypt($request->password, $request->nonce, $uM->getPasswordHash());
                                    if(self::$errorCode == ErrorCode::NoError){
                                        Logger::getLogger()->log('INFO', 'Changing value of password for user '.$uM->getUserName());
                                        $user->setPassword_hash($ssM->getResult());
                                    }
                                }
                            }
                            if($isAdmin === true){
                                if($rP->hasParameters(array('hidden'))){
                                    Logger::getLogger()->log('DEBUG', 'found hidden in request');
                                    if(!$request->hidden || $request->hidden){
                                        Logger::getLogger()->log('INFO', 'Changing value of hidden for user '.$uM->getUserName());
                                        $user->setHidden($request->hidden);
                                    }else{
                                        self::$errorCode = ErrorCode::ValidationFailed;
                                        self::$respondArray = array_merge(self::$respondArray,array('hidden'));
                                    }
                                }
                                if($rP->hasParameters(array('type'))){
                                    Logger::getLogger()->log('DEBUG', 'found type in request');
                                    if($request->type>=0 && $request->type<=2){
                                        Logger::getLogger()->log('INFO', 'Changing value of type for user '.$uM->getUserName());
                                        $user->setUser_type($request->type);
                                    }else{
                                        self::$errorCode = ErrorCode::ValidationFailed;
                                        self::$respondArray = array_merge(self::$respondArray,array('type'));
                                    }
                                }
                                if($rP->hasParameters(array('name'))){
                                    Logger::getLogger()->log('DEBUG', 'found name in request');
                                    if(preg_match(Validation::UserName, $request->name)){
                                        Logger::getLogger()->log('INFO', 'Changing value of name for user '.$uM->getUserName());
                                        $user->setName($request->name);
                                    }else{
                                        self::$errorCode = ErrorCode::ValidationFailed;
                                        self::$respondArray = array_merge(self::$respondArray,array('name'));
                                    }
                                }
                                if($rP->hasParameters(array('overtime'))){
                                    Logger::getLogger()->log('DEBUG', 'found overtime in request');
                                    if($request->overtime>=0){
                                        Logger::getLogger()->log('INFO', 'Changing value of overtime for user '.$uM->getUserName());
                                        $user->setOvertime($request->overtime);
                                    }else{
                                        self::$errorCode = ErrorCode::ValidationFailed;
                                        self::$respondArray = array_merge(self::$respondArray,array('overtime'));
                                    }
                                }
                                if($rP->hasParameters(array('weeklyWorkingMinutes'))){
                                    Logger::getLogger()->log('DEBUG', 'found weeklyWorkingMinutes in request');
                                    if($request->weeklyWorkingMinutes>=0 || $request->weeklyWorkingMinutes<=24*60*7){
                                        Logger::getLogger()->log('INFO', 'Changing value of weekly working minutes for user '.$uM->getUserName());
                                        $user->setWeekly_working_minutes($request->weeklyWorkingMinutes);
                                    }else{
                                        self::$errorCode = ErrorCode::ValidationFailed;
                                        self::$respondArray = array_merge(self::$respondArray,array('weeklyWorkingMinutes'));
                                    }
                                }
                                if($rP->hasParameters(array('weeklyWorkingDays'))){
                                    Logger::getLogger()->log('DEBUG', 'found weeklyWorkingDays in request');
                                    if($request->weeklyWorkingDays>=0 || $request->weeklyWorkingDays<=7){
                                        Logger::getLogger()->log('INFO', 'Changing value of weekly working days for user '.$uM->getUserName());
                                        $user->setWeekly_working_days($request->weeklyWorkingDays);
                                    }else{
                                        self::$errorCode = ErrorCode::ValidationFailed;
                                        self::$respondArray = array_merge(self::$respondArray,array('weeklyWorkingDays'));
                                    }
                                }
                                if($rP->hasParameters(array('yearVacationDays'))){
                                    Logger::getLogger()->log('DEBUG', 'found yearVacationDays in request');
                                    if($request->weeklyWorkinyearVacationDaysgDays>=0 || $request->weeklyWorkinyearVacationDaysgDays<=366){
                                        Logger::getLogger()->log('INFO', 'Changing value of weekly working minutes for user '.$uM->getUserName());
                                        $user->setYear_vacation_days($request->yearVacationDays);
                                    }else{
                                        self::$errorCode = ErrorCode::ValidationFailed;
                                        self::$respondArray = array_merge(self::$respondArray,array('yearVacationDays'));
                                    }
                                }
                                if($rP->hasParameters(array('password')) && self::$errorCode == ErrorCode::NoError){
                                    Logger::getLogger()->log('DEBUG', 'found password in request');
                                    self::$errorCode = $ssM->aDecrypt($request->password);
                                    if(self::$errorCode == ErrorCode::NoError){
                                        Logger::getLogger()->log('INFO', 'Changing value of password for user '.$uM->getUserName());
                                        $user->setPassword_hash($ssM->getResult());
                                    }
                                }
                            }
                            (Bootstrap::getEntityManager())->flush();
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
            Logger::getLogger()->log('INFO', 'Api path /user/modify/ was called');
        }
    }
    Runner::run();
?>
