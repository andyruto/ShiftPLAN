<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    require '../../prepareExec.php';

    final class Main extends ApiRunnable{

        //Method invoked on script execution
        public static function run(){
            Logger::getLogger()->log('DEBUG', 'logging in');
            // Takes raw data from the request
            $request = (new RequestParser())->getBodyObject();
            // Checking Api Key
            $eC = ExecutionChecker::apiKeyChecker($request->apiKey);
            $eC.check();
            // Checking password for user
            if(!empty($request->username)&&!empty($request->passwordHash)){
                $entityManager = Bootstrap::getEntityManager();
                $errorCode = ErrorCode::NoError;
                // Looking for user with username in database
                $user = $entityManager->getRepository('User')->findBy(array('name' => $request->username));
                // If user not found the entity managaer returns null
                if($user[0]==null){
                    Logger::getLogger()->log('INFO', $request->username.' user not found');
                    $errorCode = ErrorCode::UserNotFound;
                // Checking of password hash matches stored password hash
                }elseif($user[0]->getPassword_hash()==$request->passwordHash){
                    Logger::getLogger()->log('INFO', 'password for '.$request->username.' ok');                 
                }else{
                    // user were found in db but password was incorrect
                    Logger::getLogger()->log('INFO', 'wrong password for'.$request->username);
                    $errorCode = ErrorCode::WrongPassword;
                }
            }
            //Creating session
            $sM = new SessionManager();
            $finishCode = $sM.createSession($request->user, $request->passwordHash);
            //Preparing output
            $respondArray = array();
            sendOutput($finishCode, $respondArray);
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