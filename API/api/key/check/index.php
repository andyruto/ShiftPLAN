<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-01 / Maximilian T. | Kontr0x
     */

    require '../../prepareExec.php';

    final class Main extends ApiRunnable{

        //Method invoked on script execution
        static public function run(){
            Logger::getLogger()->log('DEBUG', 'checking api key');
            
            // Takes raw data from the request
            $request = (new RequestParser())->getBodyObject();
            //Checking validation of api key
            if((new ApiKeyManager($request->apiKey))->checkApiKey()){
                $respondJSON = array('success' => true, 'errorCode' => ErrorCode::NoError);
                sendOutput($respondJSON);
            }
        }

        //Method invoked before script execution
        static public function logUrl(){
            //Logging the called script location
            Logger::getLogger()->log('INFO', 'Api path /key/check/ was called');
        }

    }
    Runner::run();
?>