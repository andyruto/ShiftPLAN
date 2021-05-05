<?php
    /**
     * requestParser.php
     * 
     * PHP file containing class to parse request body.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-05 / Maximilian T. | Kontr0x
     */

    class RequestParser{

        private $requestBody = null;

        function __construct(){
            Logger::getLogger()->log('DEBUG', 'Called request parser');
            $this->requestBody = file_get_contents('php://input');
        }

        public function getBodyObject(){
            if($this->requestBody != null){
                try{
                    return json_decode($this->requestBody);
                }catch(Exception $e){
                    Logger::getLogger()->log('ERROR', "couldn't decode request body");
                    exit();
                }
            }else{
                Logger::getLogger()->log('ERROR', 'request body was empty');
                exit();
            }
        }

        public function getJson(){
            return $this->requestBody;
        }
    }
?>