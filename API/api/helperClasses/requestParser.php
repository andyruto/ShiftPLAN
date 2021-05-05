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
            //Storeing current request as string
            $this->requestBody = file_get_contents('php://input');
        }

        //Checking and if body is not null returning the json encoded version
        public function getBodyObject(){
            if($this->requestBody != null){
                try{
                    //When request body is not empty returning the json encodede version
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

        //Returning the request body
        public function getJson(){
            return $this->requestBody;
        }
    }
?>