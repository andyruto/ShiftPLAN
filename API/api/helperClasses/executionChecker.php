<?php
    /**
     * executionChecker.php
     * 
     * PHP file that checks all permissions to execute a request.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-14 / Maximilian T. | Kontr0x
     */

    class ExecutionChecker{

        private $apiKey = null; //Storing api key to check
        private function setApiKey($apiKey){$this->apiKey = $apiKey;}
        private $permissions = null; //Storing permission to check on api key
        private function setPermissions($permissions){$this->permissions = $permissions;}
        private $session = null; //Storing session to check if user loged in
        private function setSession($session){$this->session = $session;}
        private $userType = null; //Storing user type to check if user is supervised or not
        private function setUserType($userType){$this->userType = $userType;}

        private function __construct(){
            Logger::getLogger()->log('DEBUG', 'Called execution checker');

        }

        //Overloaded constructor to check api key
        public static function apiKeyChecker(String $apiKey){
            if(!empty($apiKey)){
                $obj = new self();
                //Checking if api key matches an api key pattern
                if(preg_match(Validation::ApiKey, $apiKey)){
                    $obj->setApiKey($apiKey);
                    return $obj;
                }else{
                    Logger::getLogger()->log('DEBUG', 'api key failed regex validation');    
                    $finishCode = ErrorCode::ValidationFailed;
                }
            }else{
                Logger::getLogger()->log('DEBUG', 'api key is empty');
                $finishCode = ErrorCode::EmptyParameter;
            }
            //If something failed in the overload constructor print the error code and stoping the script
            $respondArray = array();
            sendOutput($finishCode, $respondArray);
            exit();
        }

        //Overloaded constructor to check perssions on api key
        public static function apiKeyPermissionChecker(String $apiKey, Array $permissions){
            if(!empty($permissions)){
                $obj = self::apiKeyChecker($apiKey);
                $obj->setPermissions($permissions);
                return $obj;
            }else{
                Logger::getLogger()->log('DEBUG', 'execution failed permissions is empty');
                $finishCode = ErrorCode::EmptyParameter;
            }
            //If something failed in the overload constructor print the error code and stoping the script
            $respondArray = array();
            sendOutput($finishCode, $respondArray);
            exit();
        }

        //Overloaded constructor to check session validity
        public static function apiKeyPermissionSessionChecker(String $apiKey, Array $permissions, String $session){
            if(!empty($session)){
                $obj = self::apiKeyPermissionChecker($apiKey, $permissions);
                //Checking if session matches an session pattern
                if(preg_match(Validation::Session, $session)){
                    $obj->setSession($session);
                    return $obj;
                }else{
                    Logger::getLogger()->log('DEBUG', 'session failed regex validation');    
                    $finishCode = ErrorCode::ValidationFailed;
                }
            }else{
                Logger::getLogger()->log('DEBUG', 'execution failed session is empty');
                $finishCode = ErrorCode::EmptyParameter;
            }
            //If something failed in the overload constructor print the error code and stoping the script
            $respondArray = array();
            sendOutput($finishCode, $respondArray);
            exit();
        }

        //Overloaded constructor to check if user is supervised
        public static function apiKeyPermissionSessionUserTypeChecker(String $apiKey, Array $permissions, String $session, Int $userType){
            if(!empty($userType)){
                $obj = self::apiKeyPermissionSessionChecker($apiKey, $permissions, $session);
                $obj->setUserType($userType);
                return $obj;
            }else{
                Logger::getLogger()->log('DEBUG', 'execution failed user type is empty');
                $finishCode = ErrorCode::EmptyParameter;
            }
            //If something failed in the overload constructor print the error code and stoping the script
            $respondArray = array();
            sendOutput($finishCode, $respondArray);
            exit();
        }

        public function check($skipDefaultPwCheck){
            Logger::getLogger()->log('DEBUG', 'validating execution');
            $dbM = new DbManager();
            $finishCode = $dbM->checkDb($skipDefaultPwCheck);

            if($this->success($finishCode)){
                Logger::getLogger()->log('DEBUG', 'Database check successful');
                $apiKeyManager = ApiKeyManager::checker($this->apiKey);
                $finishCode = $apiKeyManager->checkApiKey();
    
                if($this->success($finishCode)){
                    if(!empty($this->permissions)){
                        $finishCode = $apiKeyManager->checkPermission($permissions);
                    }else{
                        Logger::getLogger()->log('DEBUG', 'check permission skipped');
                    }
                    if($this->success($finishCode)&&!empty($this->session)){
                        $sessionManager = new SessionManager($this->session);
                        $finishCode = $sessionManager.checkSession();
                    }else{
                        Logger::getLogger()->log('DEBUG', 'check session skipped');
                    }
                    //todo user type checker
                }
            }else{
                Logger::getLogger()->log('ERROR', 'Database check failed');
            }

            if(!$this->success($finishCode)){
                $respondArray = array();
                sendOutput($finishCode, $respondArray);
                exit();
            }
        }

        private function success($finishCode){
            return $finishCode==ErrorCode::NoError;
        }

        public function checkDb(){
            $entityManager = Bootstrap::getEntityManager();
            try{
                if($entityManager->getRepository('dbVersion')->findAll()!=null){
                    Logger::getLogger()->log("DEBUG", "DBVerson found. A database exists.");
                }else{
                    Logger::getLogger()->log("DEBUG", "No DBVersion found. Database could be corrupted or empty.");
                }
            }catch (DBALException $e){
                Logger::getLogger()->log("DEBUG", "DBALException occured. Force a reset to the current DB scheme.");
            }
        }
    }
?>