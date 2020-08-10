<?php
    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2020-08-10 / Maximilian T. | Kontr0x
     */

    require '../../prepareExec.php';

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

            // Checking if only id was given
            if(!empty($request->id)){
                Logger::getLogger()->log('INFO', 'id found in "user remove" post');
                $user = $entityManager->find('User', $request->id);
                if($user->getHidden() == 0){
                    $user->setHidden(1);
                }elseif($user->getHidden() == 1){
                    $user->setHidden(0);
                }else{
                    Logger::getLogger()->log('ERROR', 'data corrupt');
                    $respondJSON = array('success' => 'false');
                    echo(json_encode($respondJSON));
                    exit();
                }
                //$entityManager->remove($user);
                $entityManager->flush();
                //$entityManager->clear();
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