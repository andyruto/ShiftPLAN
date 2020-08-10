<?php
    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2020-08-10 / Maximilian T. | Kontr0x
     */

    require '../prepareExec.php';

    final class Main {

        //Method invoked on script execution
        public static function run() {

            // Takes raw data from the request
            $json = file_get_contents('php://input');
            $request = json_decode($json);
            checkApiKey($request->api_key);
            checkSessionKey($request->session_key);

            // Creating entity manager for doctrine framwork sql access
            $entityManager = Bootstrap::getEntityManager();
            // Setting header for html output type
            header('Content-Type: application/json');

            // Checking if only a session key was given to check if session key is valid
            if(!empty($request->session)){
                Logger::getLogger()->log('INFO', 'session key found in post');
                $session = $entityManager->find('Session', $request->session);
                $entityManager->remove($session);
                $entityManager->flush();
                $entityManager->clear();
                $respondJSON = array('success' => 'true');
                echo(json_encode($respondJSON));
                exit();
            }else{
                // combination of arguments was wrong
                Logger::getLogger()->log('ERROR', 'wrong arguments in post request');
                $respondJSON = array('success' => 'false');
                echo(json_encode($respondJSON));
                exit();
            }
            
        }
    }

    Main::run();
?>