<?php

    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-06-01 / Maximilian T. | Kontr0x
     */

    require '../../prepareExec.php';

    final class Main extends ApiRunnable{

        //Method invoked on script execution
        public static function run(){
            // Takes raw data from the request
            $request = (new RequestParser())->getBodyObject();
            //Checking validation of api key
            $eC = ExecutionChecker::apiKeyChecker($request->apiKey);
            $eC->check(true);
            $ssM = new SslKeyManager();
            $pk = $ssM->getPublicKey();
            //Preparing output
            $respondArray = array('publicKey' => $pk);
            sendOutput(ErrorCode::NoError, $respondArray);
            exit();
        }

        //Method invoked before script execution
        public static function logUrl(){
            //Logging the called script location
            Logger::getLogger()->log('INFO', 'Api path /key/publickey/ was called');
        }
        
    }
    Runner::run();
?>