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

        private $eM = null;
        private $apiKey = null;

        function __construct($apiKey){
            Logger::getLogger()->log('DEBUG', 'Called api key manager');
            $this->eM = Bootstrap::getEntityManager();
            if(!empty($apiKey)){
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

        function checkApiKey() : bool{
            Logger::getLogger()->log('DEBUG', 'api key '.$this->apiKey->getId().' valid');
            return true;
        }
        
        //todo
        function addApiKey(){}

        //todo
        function checkPermission(){}
    }
?>