<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-06-01 / Maximilian T. | Kontr0x
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
                    $eC = ExecutionChecker::apiKeyPermissionSessionChecker($request->apiKey, array(), $session);
                    $sM = SessionManager::obj($session);
                    self::$errorCode = $sM->getFinishCode();
                    //Checking the session manager succesfully found a valid session
                    if(self::$errorCode == ErrorCode::NoError){
                        $userNameFromSession = $sM->getUserName();
                        //Checking if execution privileges a granted
                        $eC->check($userNameFromSession == 'admin');
                        //Creating a user manager for modifications
                        $uM = UserManager::obj($userNameFromSession);
                        self::$errorCode = $uM->getFinishCode();
                        //Checking if the user manager was successfull finding a valid user
                        if(self::$errorCode == ErrorCode::NoError){
                            $user = $uM->getDbObject();
                            if($rP->hasParameters(array('nonce'))){
                                if($rP->hasParameters(array('password'))){
                                    self::$errorCode = $ssM->sDecrypt($request->password, $request->nonce, $uM->getPasswordHash());
                                    if(self::$errorCode == ErrorCode::NoError){
                                        $user->setPassword_hash($ssM->getResult());
                                    }
                                }
                            }
                            if($rP->hasParameters(array('hidden'))){
                                if(preg_match(Validation::BooleanValue, $request->hidden)){
                                    Logger::getLogger()->log('INFO', 'Changing value of hidden to '.$request->hidden);
                                    $user->setHidden($request->hidden);
                                }else{
                                    self::$errorCode = ErrorCode::ValidationFailed;
                                    self::$respondArray = array_merge(self::$respondArray,array('hidden'));
                                }
                            }
                            if($rP->hasParameters(array('type'))){
                                if(preg_match(Validation::UserType, $request->type)){
                                    Logger::getLogger()->log('INFO', 'Changing value of type to '.$request->type);
                                    $user->setUser_type($request->type);
                                }else{
                                    self::$errorCode = ErrorCode::ValidationFailed;
                                    self::$respondArray = array_merge(self::$respondArray,array('type'));
                                }
                            }
                            if($rP->hasParameters(array('name'))){
                                if(preg_match(Validation::UserName, $request->name)){
                                    Logger::getLogger()->log('INFO', 'Changing value of name to '.$request->name);
                                    $user->setName($request->name);
                                }else{
                                    self::$errorCode = ErrorCode::ValidationFailed;
                                    self::$respondArray = array_merge(self::$respondArray,array('name'));
                                }
                            }
                            if($rP->hasParameters(array('overtime'))){
                                if(preg_match(Validation::Overtime, $request->overtime)){
                                    Logger::getLogger()->log('INFO', 'Changing value of overtime to '.$request->overtime);
                                    $user->setOvertime($request->overtime);
                                }else{
                                    self::$errorCode = ErrorCode::ValidationFailed;
                                    self::$respondArray = array_merge(self::$respondArray,array('overtime'));
                                }
                            }
                            if($rP->hasParameters(array('weeklyWorkingMinutes'))){
                                if(preg_match(Validation::WeeklyWorkingMinutes, $request->weeklyWorkingMinutes)){
                                    Logger::getLogger()->log('INFO', 'Changing value of weekly working minutes to '.$request->weeklyWorkingMinutes);
                                    $user->setWeekly_working_minutes($request->weeklyWorkingMinutes);
                                }else{
                                    self::$errorCode = ErrorCode::ValidationFailed;
                                    self::$respondArray = array_merge(self::$respondArray,array('weeklyWorkingMinutes'));
                                }
                            }
                            if($rP->hasParameters(array('weeklyWorkingDays'))){
                                if(preg_match(Validation::WeeklyWorkingDays, $request->weeklyWorkingDays)){
                                    Logger::getLogger()->log('INFO', 'Changing value of weekly working days to '.$request->weeklyWorkingDays);
                                    $user->setWeekly_working_days($request->weeklyWorkingDays);
                                }else{
                                    self::$errorCode = ErrorCode::ValidationFailed;
                                    self::$respondArray = array_merge(self::$respondArray,array('weeklyWorkingDays'));
                                }
                            }
                            if($rP->hasParameters(array('yearVacationDays'))){
                                if(preg_match(Validation::YearVacationDays, $request->weeklyWorkinyearVacationDaysgDays)){
                                    Logger::getLogger()->log('INFO', 'Changing value of weekly working minutes to '.$request->yearVacationDays);
                                    $user->setYear_vacation_days($request->yearVacationDays);
                                }else{
                                    self::$errorCode = ErrorCode::ValidationFailed;
                                    self::$respondArray = array_merge(self::$respondArray,array('yearVacationDays'));
                                }
                            }
                            (Bootstrap::getEntityManager())->flush();
                        }
                    }
                }
            }else{
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