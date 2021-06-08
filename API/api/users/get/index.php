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
                            //Searching for users in database with filter
                            $users = self::$eM->getRepository('user')->findBy(array($request->filter => $request->value));
                            $usersArray = array();
                            //Formatting the output of the found users
                            foreach($users as $user){
                                $userName = $user->getName();
                                $userArray = array('id' => $user->getId(),'type' => $user->getUser_type(), 'hidden' => $user->getHidden());
                                $usersArray = array_merge($usersArray, array($userName => $userArray));    
                            }
                            //Adding the found users to the output
                            self::$respondArray = array_merge(self::$respondArray, array('users' => $usersArray));
                        }else{
                            self::$errorCode = ErrorCode::ValidationFailed;
                            Logger::getLogger()->log('ERROR', 'Filter validation failed');
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
            Logger::getLogger()->log('INFO', 'Api path /user/get/ was called');
        }
    }
    Runner::run();
?>