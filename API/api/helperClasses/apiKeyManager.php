<?php
    /**
     * apiKeyManager.php
     * 
     * PHP file containing class to manage the api keys.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-05 / Maximilian T. | Kontr0x
     */

    class ApiKeyManager{

        private $eM = null; //Variable to store entity manager in
        private $apiKey = null;

        function __construct($apiKey){
            Logger::getLogger()->log('DEBUG', 'Called api key manager');
            //Getting entity manager for database access
            $this->eM = Bootstrap::getEntityManager();
            if(!empty($apiKey)){
                //Looking for api key in database
                $this->apiKey = $this->eM->find('ApiKey', $apiKey);
                if($this->apiKey == null){
                    Logger::getLogger()->log('ERROR', "Api key ".$apiKey." doesn't exist in database");
                    $respondJSON = array('success' => false, 'errorCode' => ErrorCode::InvalidApiKey);
                    echo(json_encode($respondJSON));
                    exit();
                }
            }else{
                Logger::getLogger()->log('ERROR', 'Api key is empty');
                $respondJSON = array('success' => false, 'errorCode' => ErrorCode::NoApiKeyGiven);
                echo(json_encode($respondJSON));
                exit();
            }
        }

        //Returning true if the api key is valid otherwise the script will stop due to code in constructor
        function checkApiKey() : bool{
            Logger::getLogger()->log('DEBUG', 'api key '.$this->apiKey->getId().' valid');
            return true;
        }
        
        //Adding an api key
        function addApiKey(){
            //todo
        }

        //Checking specific permissions of api key
        function checkPermission(){
            //todo
        }
    }
?>