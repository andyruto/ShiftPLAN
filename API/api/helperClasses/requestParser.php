<?php
    /**
     * requestParser.php
     * 
     * PHP file containing class to parse request body.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-26 / Maximilian T. | Kontr0x
     */

    class RequestParser{

        private $requestBody = null; //Storing the request body

        function __construct(){
            Logger::getLogger()->log('DEBUG', 'Called request parser');
            //Storeing current request as string
            $this->requestBody = file_get_contents('php://input');
            //Storeing current request as string
        }

        //Checking and if body is not null returning the json encoded version
        public function getBodyObject(){
            if($this->requestBody != null){
                $finishCode = ErrorCode::NoError;
                try{
                    //When request body is not empty returning the json encodede version
                    $tmpJson = json_decode($this->requestBody);
                    $emptyParameters = array();
                    foreach ($tmpJson as $key => $value){
                        if($value === null){
                            Logger::getLogger()->log('ERROR', 'parameter '.$key.' on request was empty');
                            $finishCode = ErrorCode::EmptyParameter;
                        }
                    }
                    //If an paramter on the request was empty stop the script
                    if($finishCode == ErrorCode::EmptyParameter){
                        $respondArray = array();
                        sendOutput($finishCode, $respondArray);
                        exit();
                    }
                    Logger::getLogger()->log('DEBUG', 'succesfull parsed request');
                    return $tmpJson;
                }catch(Exception $e){
                    Logger::getLogger()->log('ERROR', "couldn't decode request body");
                    $finishCode = ErrorCode::RequestBodyMalformed;
                    $respondArray = array();
                    sendOutput($finishCode, $respondArray);
                    exit();
                }
            }else{
                Logger::getLogger()->log('ERROR', 'request body was empty');
                $finishCode = ErrorCode::RequestBodyEmpty;
                $respondArray = array();
                sendOutput($finishCode, $respondArray);
                exit();
            }
        }

        //Returning the request body
        public function getBody(){
            return $this->requestBody;
        }

        //Function returning all request keys
        public function getParameters(){
            $parameters = array();
            $requestJSON = json_decode($this->requestBody);
            foreach($requestJSON as $param => $value){
                array_push($parameters, $param);
            }
            return $parameters;
        }

        //Function checking if parameters are in request
        public function hasParameters($parameters){
            $requestJSON = json_decode($this->requestBody);
            foreach($parameters as $param){
                if(!array_key_exists($param, $requestJSON)){
                    return false;
                }
            }
            return true;
        }
    }
?>
