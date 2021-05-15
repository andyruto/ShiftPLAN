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
            Logger::getLogger()->log('DEBUG', 'adding api key');
            // Takes raw data from the request
            $request = (new RequestParser())->getBodyObject();
            var_dump($request);
            //Checkinew executionChecker(ng validation of api key
            $eC = ExecutionChecker::apiKeyChecker($request->apiKey);
            //Preparing output
            $respondArray = array();
            sendOutput(ErrorCode::NoError, $respondArray);
            exit();
        }

        //Method invoked before script execution
        public static function logUrl(){
            //Logging the called script location
            Logger::getLogger()->log('INFO', 'Api path /key/check/ was called');
        }

    }
    Runner::run();
?>