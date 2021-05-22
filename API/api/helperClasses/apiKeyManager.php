<?php
    /**
     * apiKeyManager.php
     * 
     * PHP file containing class to manage the api keys.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    class ApiKeyManager{

        private $eM = null; //Variable to store entity manager in
        private $apiKey = null; //Variable to store an apiKey object
        private $errorCode = ErrorCode::NoError; // The error code associated with this object

        public function __construct($apiKey){
            Logger::getLogger()->log('DEBUG', 'Called api key manager');
            //Getting entity manager for database access
            $this->eM = Bootstrap::getEntityManager();
            if(!empty($apiKey)){
                //Looking for api key in database
                $apiKeysFromDB = $this->eM->getRepository('ApiKey')->findAll();
                foreach($apiKeysFromDB as $apiKeyFromDB){
                    if($apiKeyFromDB->getId() === $apiKey){
                        $this->apiKey = $apiKeyFromDB;
                    }
                }

                //$this->apiKey = $this->eM->find('ApiKey', $apiKey);
                if($this->apiKey == null){
                    Logger::getLogger()->log('ERROR', "Api key ".$apiKey." doesn't exist in database");
                    $this->errorCode = ErrorCode::InvalidApiKey;
                }
            }else{
                Logger::getLogger()->log('WARNING', 'Parameter Api key is empty. Class probably created as ApiKeyManager::creator');
                $this->errorCode = ErrorCode::NoApiKeyGiven;
            }
        }

        public static function checker() {

        }

        public static function creator() {

        }

        //Returning true if the api key is valid otherwise the script will stop due to code in constructor
        function checkApiKey(){
            if($this->errorCode == ErrorCode::NoError){
                Logger::getLogger()->log('DEBUG', 'api key '.$this->apiKey->getId().' valid');
            }
            return $this->errorCode;
        }
        
        //Adding an api key
        function addApiKey($apiKeyName, $permissions){
            $this->errorCode = ErrorCode::NoError;
            if($this->eM->getRepository('apiKey')->findBy(array('name' => $apiKeyName)) != null){
                //Creating new api key
                $apiKey = new ApiKey();
                $apiKey->setName($apiKeyName);
                $apiKeyId = "";
                do{
                    $apiKeyId = generateRandomString(20);
                }while($this->eM->getRepository('apiKey')->findBy(array('Id' => $apiKeyId)) != null);
                $apiKey->setid($apiKeyId);
                //Storeing created api key
                self::$entityManager->persist($standardApiKey);
                //Flushing changes
                self::$entityManager->flush();
            }else{
                $this->errorCode = ErrorCode::ApiKeyNameAlreadyInUse;
            }
            return $this->errorCode;
        }

        //Checking specific permissions of api key
        function checkPermission($permissions){
            if($this->errorCode==ErrorCode::NoError){
                Logger::getLogger()->log('DEBUG', 'Checking permissions "'. implode("\", \"", $permissions).'" for api key '.$this->apiKey->getId());
                foreach($permissions as $permission){
                    $permissionCall = "get".$permission;
                    if($this->apiKey->{$permissionCall}()){
                        Logger::getLogger()->log('DEBUG', 'Api key '.$this->apiKey->getId().' has '.$permission.' permission');
                    }else{
                        Logger::getLogger()->log('ERROR', 'Api key '.$this->apiKey->getId().' has not '.$permission.' permission');
                        Logger::getLogger()->log('INFO', 'Script stopped due to missing permissions');
                        $this->errorCode = ErrorCode::MissingApiKeyPermission;
                    }
                }
            }
            return $this->errorCode;
        }

        public static function createDefaultApiKey(){
            $eM = Bootstrap::getEntityManager();
            if($eM->getRepository('apiKey')->findAll()==null){
                //Creating new api key
                $standardApiKey = new ApiKey();
                $standardApiKey->setid(generateRandomString(20));
                $standardApiKey->setName('standardApiKey');
                //Storeing created api key
                $eM->persist($standardApiKey);
                //Flushing changes
                $eM->flush();
                return true;
            }
            return false;
        }
    }
?>